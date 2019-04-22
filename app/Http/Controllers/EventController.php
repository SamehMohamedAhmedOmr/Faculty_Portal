<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::all();
        $upcoming = Event::where('date', '>=', today())->first();
        return view ('Portal.public.events',compact('events','upcoming'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        if($user->userable_type == 'Adm')
            return view('Portal.admin_panel.addEvent');
        else
            return view('Portal.public.index');
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
            'name' => 'required|max:50|min:5|string|regex:/^[a-zA-Z0-9\s\-\)\(\[\]]+$/u', // start with letter
            'place' => 'required|max:50|min:5|string|regex:/^[a-zA-Z0-9\s\-\)\(\[\]]+$/u', // start with letter
            'description'=>'required|min:10|max:500|string|regex:"^[^<>?;]+$"',
            'date'=>'required'
        ],
            [
                'name.required' => 'Please write a name',
                'place.required' => 'Please enter a place of event',
                'description.required' => 'Please write a description',
                'description.regex' => 'Please write a valid description (< , > , ? , ; characters not allowed)',
                'name.regex' => 'Please write a valid name (letters and numbers only)',
                'place.regex' => 'Please write a valid place (letters and numbers only)',
                'description.max'=>'Description must be between 10 and 500 character',
                'description.min'=>'Description must be between 10 and 500 character',
                'name.max'=>'Name must be between 5 and 50 character',
                'name.max'=>'Name must be between 10 and 50 character',
                'place.max'=>'Place must be between 5 and 50 character',
                'place.max'=>'Place must be between 10 and 50 character',
                'date.required' => 'Please enter date and time for the event'
            ]);

        $event = new Event();
        $event->name = $request->name;
        $event->description = $request->description;
        $event->date = $request->date;
        $event->place = $request->place;
        if($event->save())
        {
            return redirect()->action('EventController@index');
        }
        else{
            App::abort(500, 'Error');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/Home');
        }
        $event = Event::Find($id);
        if($event)
        {
            $m = date("m",strtotime($event->date));
            $day = date("d",strtotime($event->date));
            $month = date('M', strtotime($m . '01'));
            $output="
                        <div class=\"modal-header\">
                            <H4><i class=\"fa fa-calendar\"></i>". $day ." ". $month ."</H4>
                            <br/>
                            <H4><i class=\"fa fa-map-marker\"></i>$event->place</H4>
                            <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
                        </div>
                        <div class=\"modal-body\" id=\"eventModalBody\">
                            <h3 class=\"event-title\">". $event->name ."</h3>
                            <hr>
                            <span class=\"event-description\">".
                             $event->description
                            ."</span>
                        </div>";
            if(Auth::check())
                if(Auth::user()->userable_type=='Adm')
                    $output.="
                        <form method=\"GET\" action=\"/events/". $event->id ."/edit\">
                            <button class='btn btn-primary edit-event'> Edit <i class=\"fa fa-edit\"></i></button>
                        </form>";
                        
        }
        else{
            $output="
                        <div class=\"modal-header\">
                            <H4>Error ! </H4>
                            <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
                        </div>
                        <div class=\"modal-body\" id=\"eventModalBody\">
                            <span class=\"event-description\">Something wrong happends!</span>
                        </div>";
        }
        return $output;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        if ($user->userable_type == 'Adm')
        {
            $event = Event::findOrFail($id);
            return view('Portal.admin_panel.editEvent',compact('event'));
        }
        else
            return view('Portal.public.index');
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
        $user = Auth::user();
        if ($user->userable_type == 'Adm')
        {
            $this->validate($request, [
                'name' => 'required|max:50|min:5|string|regex:/^[a-zA-Z0-9\s\-\)\(\[\]]+$/u', // start with letter
                'place' => 'required|max:50|min:5|string|regex:/^[a-zA-Z0-9\s\-\)\(\[\]]+$/u', // start with letter
                'description'=>'required|min:10|max:500|string|regex:"^[^<>?;]+$"',
                'date'=>'required'
            ],
                [
                    'name.required' => 'Please write a name',
                    'place.required' => 'Please enter a place of event',
                    'description.required' => 'Please write a description',
                    'description.regex' => 'Please write a valid description (< , > , ? , ; characters not allowed)',
                    'name.regex' => 'Please write a valid name (letters and numbers only)',
                    'place.regex' => 'Please write a valid place (letters and numbers only)',
                    'description.max'=>'Description must be between 10 and 500 character',
                    'description.min'=>'Description must be between 10 and 500 character',
                    'name.max'=>'Name must be between 5 and 50 character',
                    'name.max'=>'Name must be between 10 and 50 character',
                    'place.max'=>'Place must be between 5 and 50 character',
                    'place.max'=>'Place must be between 10 and 50 character',
                    'date.required' => 'Please enter date and time for the event'
                ]);
            $event = Event::FindOrFail($id);
            $event->name = $request->name;
            $event->description = $request->description;
            $event->date = $request->date;
            $event->place = $request->place;
            if($event->save()){
                return redirect()->action('EventController@index');
            }
            else
            {
                App::abort(500, 'Error');
            }
        }
        else
            return view('Portal.public.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Auth_user = Auth::user();
        if($Auth_user->userable_type == 'Adm') {
            $event = Event::findOrFail($id);
            $event->delete();
            return response()->json([
                'success' => 'Event has been deleted successfully!'
            ]);
        }
        else
        {  return view('Portal.public.index'); }
    }
}
