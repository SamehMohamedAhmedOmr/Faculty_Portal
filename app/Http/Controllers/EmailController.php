<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;


class EmailController extends Controller
{
    //
    public function inboxMail()
    {
        //get all inbox Mails , also get count (*) from pagination
        $inbox = Mail::where('receiver_id',Auth::user()->userable_id)
            ->orderBy('date_time', 'desc')
            ->paginate(12);
         // we need to get count of all messages in send Mails too
        $totalSend=Mail::where('sender_id',Auth::user()->userable_id)->count();
        return view('Portal.user.Email', compact('inbox','totalSend'));
    }

    public function sendMail()
    {
        // get all send Mails , also get count (*) from pagination
        $send = Mail::where('sender_id',Auth::user()->userable_id)
            ->orderBy('date_time', 'desc')
            ->paginate(12);
        // we need to get count of all messages in inbox too ...
        $totalInbox=Mail::where('receiver_id',Auth::user()->userable_id)->count();
        return view('Portal.user.Email', compact('send','totalInbox'));
    }

    public function storeMail(Request $request)
    {
        $receiver = strip_tags($request->input('reciever'));
        $header = strip_tags($request->input('header'));
        $message = strip_tags($request->input('message'));
        $senderid = strip_tags($request->input('senderId'));

        /* 1 - first check user id was the same id in Auth facade*/
        if(Auth::user()->userable_id==$senderid)
        {
            /*2- check i am not trying to send email to myself*/
            if(Auth::user()->email==$receiver)
            {return redirect('/Email/inbox')->withErrors(['Error'=>'you can\'t send Email to yourself']);}
            else
            {
                /*Validate Request Data*/
                $validator = Validator::make($request->all(),[
                    'reciever'=>'required|email|exists:users,email',
                    'header'=>'required|string|min:3|max:20|regex:"[A-Za-z0-9 ]{3,20}"' ,
                    'message'=>'required|string|min:10|max:200|regex:"[A-Za-z0-9 ]{10,200}"'
                ],
                [
                    'reciever.require'=>'reciever email is required',
                    'reciever.email'=>'reciever email must be a valid email',
                    'reciever.exists'=>'reciever email is not found',

                    'header.require'=>'header for E-mail is required',
//                   'header.string'=>'header for E-mail must be a valid string',
                    'header.min'=>'header for E-mail must have at least 3 characters',
                    'header.max'=>'header for E-mail must have at most 20 characters',
                    'header.regex'=>'pls enter valid header range between (3,20) characters only',

                    'message.require'=>'message for E-mail is required',
//                    'message.string'=>'message for E-mail must be a valid string',
                    'message.min'=>'message for E-mail must have at least 10 character ',
                    'message.max'=>'message for E-mail must have at most 200 character',
                    'message.regex'=>'pls enter valid message range between (10,200) characters only',
                ]
                );
                if ($validator->fails()) {
                    return redirect('/Email/inbox')
                        ->withInput()
                        ->withErrors($validator);
                }
                else
                {
                    // get id equal to the email of the user
                    $recieverID = User::where('email',$receiver)->first();
                    $recieverID=$recieverID->userable_id;
                    // save mail after all
                    $mail = new Mail();
                    $mail->header = $header;
                    $mail->description = $message;
                    $mail->receiver_id = $recieverID;
                    $mail->sender_id = $senderid;
                    $mail->date_time =Carbon::now();
                    $mail->save();
                    return redirect('/Email/inbox')->with('message', 'your message was send successfully ');
                }
            }
        }
        else
        {return redirect('/Email/inbox')->withErrors(['Error' => 'Failed to Send Email Pls try again later ']);}

    }
}
