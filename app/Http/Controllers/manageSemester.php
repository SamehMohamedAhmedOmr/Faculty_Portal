<?php

namespace App\Http\Controllers;

use App\Rules\endSemesterDateRule;
use App\Rules\midtermDateRule;
use App\Rules\openRegisterDateRule;
use App\Rules\startSemesterDateRule;
use App\Semester;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class manageSemester extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $semesters = Semester::orderBy('id','desc')->paginate(10);
        return view('Portal.admin_panel.Panel',compact('semesters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $semesters = Semester::all();
        foreach ($semesters as $semester){
            if($semester->complete == 0){
                session()->flash('semester_cannot_open','You can\'t add new semester until the current semester completed');
                return redirect()->action('manageSemester@index');
            }
        }
        return view('Portal.admin_panel.Panel');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'type'                  => ['required', Rule::in([0, 1])],
            'name'                  => 'required|string|max:100|min:5',
            'Start_Date'            => ['required', 'date', 'after_or_equal:today',
                                                  new startSemesterDateRule()],
            'End_Date'              => ['required', 'date', 'after:Start_Date',
                                                  new endSemesterDateRule($request->type,$request->Start_Date)],
            'Midterm'               => ['required', 'date', 'after:Start_Date', 'before:End_Date',
                                                  new midtermDateRule($request->type,$request->Start_Date)]
        ]);

        // new attribute assigned
        $final_monitoring_grades_date = Carbon::parse($request->End_Date)->addMonth(1)->format('Y-m-d');
        $open_register_date = Carbon::parse($request->Start_Date)->addWeek(1)->format('Y-m-d');
        $end_register_date = Carbon::parse($open_register_date)->addMonth(1)->format('Y-m-d');

        Semester::create(['name'=>$request->name,
            'isSummer'=>$request->type,
            'start_date'=>$request->Start_Date,
            'end_date'=>$request->End_Date,
            'final_monitoring_grades_date'=>$final_monitoring_grades_date,
            'midterm_week'=>$request->Midterm,
            'open_register_date'=>$open_register_date,
            'end_register_date'=>$end_register_date,
            'admin_id'=>Auth::user()->userable_id]);

        $request->session()->flash('Add_Semester_Success','Semester add correctly');
        return redirect()->action('manageSemester@index');
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
        try{
            $selected = Semester::findOrFail($id);
            return view('Portal.admin_panel.Panel',compact('selected'));
        }catch (\Exception $e){
            session()->flash('no_semester','There no Semester by that Name');
            return redirect()->action('manageSemester@index');
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
        try{
            $updatedSemester = Semester::findOrFail($id);
        }catch (\Exception $e){
            session()->flash('no_semester','There no Semester by that Name');
            return redirect()->action('manageSemester@index');
        }

        $now = Carbon::now();
        if(strtotime($updatedSemester->open_register_date) <= strtotime($now)){ // Registration is start
            $this->validate($request,[
                'status'                => [Rule::in([0, 1])],
                'End_Date'              => ['required', 'date',
                    new endSemesterDateRule($updatedSemester->isSummer,$updatedSemester->start_date)]
            ]);

            // new attribute assigned
            $final_monitoring_grades_date = Carbon::parse($request->End_Date)->addMonth(1)->format('Y-m-d');

            $updatedSemester->end_date                        = $request->End_Date;
            $updatedSemester->final_monitoring_grades_date    = $final_monitoring_grades_date;
            $updatedSemester->complete                        = $request->status;;
            $updatedSemester->admin_id                        = Auth::user()->userable_id;

            $updatedSemester->save();

            $request->session()->flash('update_Semester_success',
                'Semester\'s End-Date and status updated Successfully , make sure that semester does n\'t exceed Registration Date ');
            return redirect()->action('manageSemester@index');
        }
        else{
            $this->validate($request,[
                'type'                  => ['required', Rule::in([0, 1])],
                'status'                => [Rule::in([0, 1])],
                'name'                  => 'required|string|max:100|min:5',
                'Start_Date'            => ['required', 'date',
                    new startSemesterDateRule()],
                'End_Date'              => ['required', 'date', 'after:Start_Date',
                    new endSemesterDateRule($request->type,$request->Start_Date)],
                'Midterm'               => ['required', 'date', 'after:Start_Date', 'before:End_Date',
                    new midtermDateRule($request->type,$request->Start_Date)]
            ]);

            // new attribute assigned
            $final_monitoring_grades_date = Carbon::parse($request->End_Date)->addMonth(1)->format('Y-m-d');
            $open_register_date = Carbon::parse($request->Start_Date)->addWeek(1)->format('Y-m-d');
            $end_register_date = Carbon::parse($open_register_date)->addMonth(1)->format('Y-m-d');

            $updatedSemester->name                            = $request->name;
            $updatedSemester->isSummer                        = $request->type;
            $updatedSemester->start_date                      = $request->Start_Date;
            $updatedSemester->end_date                        = $request->End_Date;
            $updatedSemester->final_monitoring_grades_date    = $final_monitoring_grades_date;
            $updatedSemester->midterm_week                    = $request->Midterm;
            $updatedSemester->open_register_date              = $open_register_date;
            $updatedSemester->end_register_date               = $end_register_date;
            $updatedSemester->complete                        = $request->status;;
            $updatedSemester->admin_id                        = Auth::user()->userable_id;

            $updatedSemester->save();

            $request->session()->flash('update_Semester_success','Semester Edited Successfully');
            return redirect()->action('manageSemester@index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
