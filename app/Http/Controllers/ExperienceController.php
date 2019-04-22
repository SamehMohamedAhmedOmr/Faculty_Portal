<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Experience;
use Illuminate\Support\Facades\Validator;


class ExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if($user->userable_type == 'T_A' || $user->userable_type == 'Doc')
        {
            $experiences = Experience::where('experienceable_id',$user->userable_id)->get();
            if($user->userable_type == 'Doc')
            {
                return view('Portal.Doctor_panel.Panel',compact('experiences'));
            }
            else
            {
                return view('Portal.Instructor_panel.Panel',compact('experiences'));
            }
        }
        else
        {return view('Portal.public.index');}
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        if($user->userable_type == 'T_A' || $user->userable_type == 'Doc')
        {

            if($user->userable_type == 'Doc')
            {
                return view('Portal.Doctor_panel.Panel');
            }
            else
            {
                return view('Portal.Instructor_panel.Panel');
            }
        }
        else
        {return view('Portal.public.index');}
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required|max:50|min:5|string|regex:/^[a-zA-Z0-9\s\-\)\(\[\]]+$/u', // start with letter
            'description'=>'required|min:10|max:500|string|regex:"^[^<>?;]+$"',
        ],
            [
                'subject.required' => 'Please write a subject',
                'description.required' => 'Please write a description',
                'description.regex' => 'Please write a valid description (< , > , ? , ; characters not allowed)',
                'subject.regex' => 'Please write a valid subject (letters and numbers only)',
                'description.max'=>'Description must be between 10 and 500 character',
                'description.min'=>'Description must be between 10 and 500 character',
                'subject.max'=>'Subject must be between 5 and 50 character',
                'subject.max'=>'Subject must be between 10 and 50 character',
            ]);

        $user = Auth::user();
        Experience::Create([
            'experienceable_id' => $user->userable_id,
            'experienceable_type' => $user->userable_type,
            'subject' => $request->subject,
            'description'=> $request->description,
            'date' => date('Y-m-d H:i:s')
        ]);
        if($user->userable_type == 'Doc')
        {
            return redirect()->action('ExperienceController@index')->with('message', 'New experience added successfully');
        }
        else
        {
            return redirect()->action('ExperienceController@index')->with('message', 'New experience added successfully');
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try
        {
            $Auth_user = Auth::user();
            if($Auth_user->userable_type == 'Doc' || $Auth_user->userable_type == 'T_A')
            {
                $id=explode('*',$id);
                $date=$id[1];
                $id=$id[0];
                $exp = Experience::where([ ['experienceable_id', '=', $id],['date', '=', $date]])->get()->first();
                return view('Portal.Doctor_panel.Panel', compact('exp'));
            }
            else
            {return view('Portal.public.index');}
        }
        catch(ModelNotFoundException $e)
        {
            return redirect()->action('ExperienceController@index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id=explode('*',$id);
        $date=$id[1];
        $id=$id[0];
        if($id == Auth::user()->userable_id)
        {
            $this->validate($request, [
                'subject' => 'required|max:50|min:5|string|regex:/^[a-zA-Z0-9\s\-\)\(\[\]]+$/u', // start with letter
                'description'=>'required|min:10|max:500|string|regex:"^[^<>?;]+$"',
            ],
                [
                    'subject.required' => 'Please write a subject',
                    'description.required' => 'Please write a description',
                    'description.regex' => 'Please write a valid description (< , > , ? , ; characters not allowed)',
                    'subject.regex' => 'Please write a valid subject (letters and numbers only)',
                    'description.max'=>'Description must be between 10 and 500 character',
                    'description.min'=>'Description must be between 10 and 500 character',
                    'subject.max'=>'Subject must be between 5 and 50 character',
                    'subject.max'=>'Subject must be between 10 and 50 character',
                ]);

//            $exp = Experience::where([ ['experienceable_id', '=', $id],['date', '=', $date]])->get()->first();
//            $exp->subject = $request->subject;
//            $exp->description = $request->description;
            Experience::where([ ['experienceable_id', '=', $id],['date', '=', $date]])->update($request->except(['_method','_token']));
//            $exp->save();
            if(Auth::user()->userable_type == 'Doc')
            {
                return redirect()->action('ExperienceController@index')->with('message', 'Your experience deleted successfully');;
            }
            else
            {
                return redirect()->action('ExperienceController@index')->with('message', 'Your experience deleted successfully');;
            }
        }
        else
        { return view('Portal.public.index'); }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id=explode('*',$id);
        $date=$id[1];
        $id=$id[0];
        if($id == Auth::user()->userable_id)
        {
            Experience::where([ ['experienceable_id', '=', $id],['date', '=', $date]])->delete();
            return response()->json([
                'success' => 'experience has been deleted successfully!'
            ]);
        }
        else
        { return view('Portal.public.index'); }
    }
}
