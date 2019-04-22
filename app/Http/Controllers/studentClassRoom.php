<?php

namespace App\Http\Controllers;

use App\Grade;
use App\Grade_distribution;
use App\Material;
use App\Semester;
use App\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class studentClassRoom extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $student = Auth::user();
        $currentSemester = Semester::where('complete',0)->get();
        $registerCourses = $student->userable->timetables->where('semester_id',$currentSemester[0]->id)
            ->where('timetableable_type','Doc')->sortByDesc('subject_id');
        $check = 0;
        return view('Portal.student_panel.Panel',compact('registerCourses','check','currentSemester'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = Auth::user();
        $currentSemester = Semester::where('complete',0)->get();
        $registerCourses = $student->userable->timetables->where('semester_id',$currentSemester[0]->id)
            ->where('timetableable_type','Doc')->sortByDesc('subject_id');

        $checkExist = false;
        foreach ($registerCourses as $registerCourse){
            if($registerCourse->subject_id == $id){
                $checkExist = true;
                break;
            }
        }
        if($checkExist === false){return redirect()->back()->withErrors(['error'=>'Please Re-Select The Course']);}

        $currentCourse = Subject::where('id',$id)->first();
        $AllMaterial = Material::where([ ['semester_id', $currentSemester[0]->id], ['subject_id',$id]])->get();

        $grades = Grade::where([
            ['semester_id', $currentSemester[0]->id],['subject_id',$id] ,['student_id',$student->userable->id]
        ])->first();

        $check = 1;
        $grade_dist = Grade_distribution::where([ ['semester_id',$currentSemester[0]->id] , ['subject_id',$id] ])->first();
        if(count($grade_dist)==0)
        {
            return redirect()->back()->withErrors(['Error'=>'nothing to show currently pls try again later ']);
        }
        return view('Portal.student_panel.Panel',
            compact('registerCourses','check','grade_dist','currentCourse','AllMaterial','grades','currentSemester'));
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
        //
    }
}
