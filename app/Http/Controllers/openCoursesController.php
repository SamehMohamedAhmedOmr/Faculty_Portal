<?php

namespace App\Http\Controllers;

use App\Open_course;
use App\Semester;
use App\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class openCoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currentSemester = Semester::where('complete',0)->get();
        $courses = Subject::all();
        if(count($currentSemester)>0) {
            $openedCourses = Open_course::where('semester_id', '=', $currentSemester[0]->id)->get();
            $Doctors = Auth::user()->where('userable_type', '=', 'Doc')->get();
            return view('Portal.admin_panel.Panel',
                compact('currentSemester', 'courses', 'openedCourses', 'Doctors'));
        }
        else{
            return view('Portal.admin_panel.Panel',
                compact('currentSemester'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'LectureNumber'         => 'required|integer|max:3|min:1',
            'sectionNumber'          => 'required|integer|max:5|min:1',
            'Leader'         => 'required|exists:doctors,id',
            'selectedCourse' => 'required|exists:subjects,id'
        ]);
        $currentSemester = Semester::where('complete',0)->max('id');

        //dd($currentSemester);
        Open_course::create([
            'semester_id'=>$currentSemester,
            'subject_id'=>$request->selectedCourse,
            'doctor_id'=>$request->Leader,
            'num_dr'=>$request->LectureNumber,
            'num_ta'=>$request->sectionNumber,
            'admin_id'=>Auth::user()->userable_id]);

        session()->flash('openCourse_Success','Course open Successfully');
        return redirect('/Panel/openCourses');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $currentSemester = Semester::where('id',$id)->get();
        $courses = Subject::all();
        $openedCourses = Open_course::where('semester_id',$currentSemester[0]->id)->get();
        $Doctors = Auth::user()->where('userable_type', '=', 'Doc')->get();
        return view('Portal.admin_panel.Panel',
            compact('currentSemester','courses','openedCourses','Doctors'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $currentSemester = Semester::where('complete',0)->max('id');
            $selected = Open_course::where('subject_id',$id)->where('semester_id',$currentSemester);
            $selected->delete();
            session()->flash('Delete_Successfully','The Open Course Canceled Successfully');
            return redirect()->action('openCoursesController@index');
        }catch (\Exception $e){
            session()->flash('no_open_course','There no Course open by that Name');
            return redirect('/Panel/openCourses');
        }
    }
}
