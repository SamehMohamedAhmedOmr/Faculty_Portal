<?php

namespace App\Http\Controllers;

use App\Grade;
use App\Semester;
use App\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TranscriptController extends Controller
{
    public function calculateGPA($grades){
        $allFinalGrades = [];
        $indexes = [];
        $totalHours = [];
        $succeededHour = [];
        // select non-duplicated grade for Courses
        for ($i=0;$i<count($grades);$i++){
            $check = 0;
            foreach ($indexes as $index){
                if($i === $index){
                    $check = 1;
                }
            }
            if($check === 1){continue;}

            $finalGrade = null;
            $hours = null;
            for ($j=$i+1;$j<count($grades);$j++){
                if($grades[$i]->subject_id === $grades[$j]->subject_id){
                    if($grades[$i]->total_grade > $grades[$j]->total_grade){
                        $finalGrade = $grades[$i]->total_grade;
                    }
                    else{
                        $finalGrade = $grades[$j]->total_grade;
                    }
                    $indexes [] = $j;
                    $hours  = $grades[$i]->subject->credit_hours;
                }
            }
            if($finalGrade === null){
                $finalGrade = $grades[$i]->total_grade;
                $hours  = $grades[$i]->subject->credit_hours;
            }
            if($finalGrade >= 50){
                $succeededHour [] = $hours;
            }
            $allFinalGrades [] = $finalGrade;
            $totalHours [] = $hours;
        }

        // calculated GPA
        $TOTALHOURS = 0;

        foreach ($totalHours as $totalHour){
            $TOTALHOURS += $totalHour;
        }

        $Points = 0;
        $counter =0;
        foreach ($allFinalGrades as $allFinalGrade){
            if($allFinalGrade < 50){ $Points += ($totalHours[$counter]*1); }
            elseif($allFinalGrade >= 50 && $allFinalGrade < 60){ $Points += ($totalHours[$counter]*2); }
            elseif($allFinalGrade >= 60 && $allFinalGrade < 65){ $Points += ($totalHours[$counter]*2.25); }
            elseif($allFinalGrade >= 65 && $allFinalGrade < 70){ $Points += ($totalHours[$counter]*2.5); }
            elseif($allFinalGrade >= 70 && $allFinalGrade < 75){ $Points += ($totalHours[$counter]*2.8); }
            elseif($allFinalGrade >= 75 && $allFinalGrade < 80){ $Points += ($totalHours[$counter]*3.1); }
            elseif($allFinalGrade >= 80 && $allFinalGrade < 85){ $Points += ($totalHours[$counter]*3.4); }
            elseif($allFinalGrade >= 85 && $allFinalGrade < 90){ $Points += ($totalHours[$counter]*3.75); }
            elseif($allFinalGrade >= 90){ $Points += ($totalHours[$counter]*4); }
            $counter++;
        }

        $allSuccessesHours = null;
        foreach ($succeededHour as $success){
            $allSuccessesHours += $success;
        }

        $return = [];

        if($TOTALHOURS != 0){
            $divide = $Points/$TOTALHOURS;

            $gpa = round($divide,2);

            $return [0] = $gpa;
            $return [1] = $allSuccessesHours;
        }

        return $return ;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $student = Auth::user();
        $availableCourses   = Subject::where('department_id',$student->userable->department->id)->orWhere('department_id',1)->get();
        $currentSemester = Semester::where('complete',0)->get();

        $grades = Grade::where('student_id',$student->userable->id)->get();

        $calculated = $this->calculateGPA($grades);
        //dd($grade);
        return view('Portal.student_panel.Panel',
            compact('student','availableCourses','grades','calculated','currentSemester'));
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
