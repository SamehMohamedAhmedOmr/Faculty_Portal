<?php

namespace App\Http\Controllers;

use App\Grade;
use App\Semester;
use App\Subject;
use App\Timetable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use \Carbon\Carbon;

class registerCoursesController extends Controller
{

    public function selectOpenCourses($currentSemester,$student){
        $coursesOpened= $currentSemester[0]->open_courses;

        // [Condition 1] Select Course with the same Department_id of Student
        // =================================================================================================
        $coursesInStudentDept   = Subject::where('department_id',$student->userable->department->id)->orWhere('department_id',1)->get();

        $selected1 = [];
        foreach ($coursesOpened as $open){
            foreach ($coursesInStudentDept as $subject){
                if($open->subject_id  === $subject->id){
                    $selected1 [] = $open;
                    break;
                }
            }
        }
        // =================================================================================================

        // [Condition 2] Condition of get the open Courses that student passed {Shouldn't apperaed}
        // =================================================================================================
        $paasedCourses   = Grade::where([['student_id',$student->userable->id], ['total_grade','>=', 50]])->get();

        $selected2 = [];
        foreach ($selected1 as $open){
            $check = false;
            foreach ($paasedCourses as $subject){
                if($open->subject_id  === $subject->subject_id){
                    $check = true;
                    break;
                }
            }
            if ($check == false){
                $selected2 [] = $open;
            }
        }
        // =================================================================================================

        // [Condition 3] Condition of get the open Courses that student passed
        // =================================================================================================
        $allCourses   = Subject::where('prerequisite','!=',null)->get();

        $grades = Grade::where([['student_id',$student->userable->id], ['total_grade','>=', 50]])->get();

        $paasedPrerqeuiste = [];
        foreach ($allCourses as $course){
            foreach ($grades as $grade) {
                if($course->prerequisite == $grade->subject_id) {
                    $paasedPrerqeuiste [] = $course->prerequisite;
                    break;
                }
            }
        }
        // =================================================================================================
        // Select open Course with Same Deprtment ID with Student and that didn't passed by student and passed Prerequiste
        $selected3 = [];
            foreach ($selected2 as $open) {
                if ($open->subject->prerequisite === null) {
                    $selected3 [] = $open;
                }
                else if($paasedPrerqeuiste != null) {
                    foreach ($paasedPrerqeuiste as $paasedPrerequiste) {
                        if ($open->subject->prerequisite === $paasedPrerequiste) {
                            $selected3 [] = $open;
                            break;
                        }
                    }
                }
            }

        return $selected3;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $now = Carbon::now();
        $student = Auth::user();
        $currentSemester = Semester::where('complete',0)->get();
        /*Check if semester has started or not*/
        if(count($currentSemester)>0) {
            if ($now >= $currentSemester[0]->open_register_date && $now <= $currentSemester[0]->end_register_date) {
                $lectures = Timetable::where('semester_id', $currentSemester[0]->id)->where('timetableable_type', 'Doc')->get()->sortByDesc('day')->sortBy('time');
                $sections = Timetable::where('semester_id', $currentSemester[0]->id)->where('timetableable_type', 'T_A')->get()->sortBy('time');
                $openCourses = $this->selectOpenCourses($currentSemester, $student);
                return view('Portal.student_panel.Panel', compact('openCourses', 'currentStudent', 'lectures', 'sections', 'timetables'));
            } else {
                return redirect()->back()->withErrors(['Error' => 'Register is not Avilable yet , it is Avilable between  ( ' . $currentSemester[0]->open_register_date . ' ) -> ( ' . $currentSemester[0]->end_register_date . ' ) only']);
            }
        }
        else{
            return redirect()->back()->withErrors(['Error' => 'Register isn\'t Avilable right now , or you are blocked from registration temporarily ']);
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
        $student = Auth::user();
        $currentSemester = Semester::where('complete',0)->get();
        $openCourses =  $this->selectOpenCourses($currentSemester,$student);

        $TimeTablesID = [];
        $counter = 0 ;

        for ($i=1;$i<=count($openCourses);$i++){
            $timetableSection = 'timetableSection'.$i;
            $timetableLecture = 'timetableLecture'.$i;
            if($request->$timetableSection === null && $request->$timetableLecture === null){
                continue;
            }
            elseif($request->$timetableSection === null || $request->$timetableLecture === null ){
                return redirect()->back()->withErrors(
                    ['error'=>'You Should Select Both Lecture and Section Time for '.$openCourses[$i-1]->subject->name.' Course']
                );
            }
            else{
                $TimeTablesID[$counter]=$i;
                $counter++;
            }
        }

        if($counter === 0){
            return redirect()->back()->withErrors(['error'=>'You Should select from 3 to 6 Courses in Semester']);
        }
        elseif($counter < 3 && $student->userable->graduated_status !== 4){
            return redirect()->back()->withErrors(['error'=>'You Should select from 3 to 6 Courses in Semester']);
        }
        elseif($counter > 6){
            return redirect()->back()->withErrors(['error'=>'You Shouldn\'t select more than 6 Courses in Semester']);
        }
        else{

            $sections = [];
            $lectures = [];

            for($j=0;$j<$counter;$j++){

                $this->validate($request,[
                    'timetableSection'.$TimeTablesID[$j]    => 'exists:timetables,id',
                    'timetableLecture'.$TimeTablesID[$j]    => 'exists:timetables,id'
                ]);

                $timetableSection  = 'timetableSection'.$TimeTablesID[$j];
                $timetableLecture  = 'timetableLecture'.$TimeTablesID[$j];


                $sections [] = Timetable::where('id',$request->$timetableSection)->first();

                $lectures [] = Timetable::where('id',$request->$timetableLecture)->first();

                if($sections[$j]->day === $lectures[$j]->day ){ // check conflict with Lecture and section with the Same Subject
                    if($sections[$j]->time === $lectures[$j]->time ){
                        return redirect()->back()->withErrors(
                            ['error'=>'You cannot Register, because you select '.$sections[$j]->subject->subject->name.
                                ' Lecture and Section which have a Conflict between them, Please choose anther time ']); }
                }

                if($sections[$j]->subject_id === $lectures[$j]->subject_id) {
                    $check = false;
                    foreach ($openCourses as $openCourse) { // check that the ID of Both Lecture and Section in OpenCourse
                        if ($openCourse->subject->id === $lectures[$j]->subject_id) {
                            $check = true;
                            break;
                        }
                    }
                    if($check === false){return redirect()->back()->withErrors(['Error4'=>'Please re-select your subject']);}
                }
                else{ return redirect()->back()->withErrors(['Error4'=>'Please re-select your subject']); }
            }

            // check conflict with all Lectures
            for ($k=0;$k<count($lectures);$k++){
                for($m=$k+1;$m<count($lectures);$m++){
                    if($lectures[$k]->day === $lectures[$m]->day ) {
                        if ($lectures[$k]->time === $lectures[$m]->time) {
                            return redirect()->back()
                                ->withErrors(['error' => 'You cannot Register because ' . $lectures[$k]->subject->subject->name . ' Lecture conflict with '
                                    .$lectures[$m]->subject->subject->name.' Lecture']);
                        }
                    }
                }
            }

            // check conflict with all Sections
            for ($k=0;$k<count($sections);$k++){
                for($m=$k+1;$m<count($sections);$m++){
                    if($sections[$k]->day === $sections[$m]->day ) { // check conflict with Lecture and section with the Same Subject
                        if ($sections[$k]->time === $sections[$m]->time) {
                            return redirect()->back()
                                ->withErrors(['error' => 'You cannot Register because ' . $sections[$k]->subject->subject->name . ' Section conflict with '
                                    .$sections[$m]->subject->subject->name.' Section']);
                        }
                    }
                }
            }

            // check conflict with all Lecture and Sections
            for ($k=0;$k<count($lectures);$k++){
                for($m=0;$m<count($sections);$m++){
                    if($lectures[$k]->day === $sections[$m]->day ) { // check conflict with Lecture and section with the Same Subject
                        if ($lectures[$k]->time === $sections[$m]->time) {
                            return redirect()->back()
                                ->withErrors(['error' => 'You cannot Register because ' . $lectures[$k]->subject->subject->name . ' Lecture conflict with '
                                    .$sections[$m]->subject->subject->name.' Section']);
                        }
                    }
                }
            }

            DB::table('student_timetable')->where([['student_id',$student->userable->id],['semester_id',$currentSemester[0]->id]])->delete();

            for ($k=0;$k<count($lectures);$k++) {
                $timetableLecture  = 'timetableLecture'.$TimeTablesID[$k];
                DB::table('student_timetable')->insert(
                    ['student_id' => $student->userable->id ,
                        'semester_id' =>$currentSemester[0]->id,
                        'timetable_id' => $request->$timetableLecture]
                );
            }

            for ($k=0;$k<count($sections);$k++) {
                $timetableSection  = 'timetableSection'.$TimeTablesID[$k];
                DB::table('student_timetable')->insert(
                    ['student_id' => $student->userable->id ,
                        'semester_id' =>$currentSemester[0]->id,
                        'timetable_id' => $request->$timetableSection]
                );
            }

        }
            session()->flash('RegisterSuccessfully', $counter.' Subject Register Successfully');
            return redirect('/Panel/timetable/view');
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
     * @return \Illuminate\Http\Response-
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
     * @return \Illuminate\Http\Response|string
     */
    public function destroy($id)
    {

    }
}
