<?php

namespace App\Http\Controllers;
use App\Semester;
use App\Timetable;
use App\Student;
use App\User;
use App\Grade;
use Excel;
use Auth;
use DB;
use Illuminate\Support\Facades\Request;

class ExcelController extends Controller
{

    public function exportStudent_Doc($courseID , $semesterID)
    {
        $data = array();
        $data[]=array('ID','StudentName','Email','Level','Midterm','quiz','section','participate','attendance','project','assignment','Total');
        $allSemester = Semester::orderBy('id', 'desc')->select('name', 'id')->get();
        $AvilableSemester = $allSemester[0]->id;
        $timeTable_id = Timetable::select('id')->where([['semester_id',$semesterID],['timetableable_id', Auth::user()->userable_id] , ['subject_id',$courseID]])->get();
        $student = DB::table('student_timetable')->select('student_id')->whereIn('timetable_id',$timeTable_id)->get();
        $s_ids=[];
        foreach ($student as $stu){$s_ids[]=$stu->student_id;}
        $student = Student::whereIn('id',$s_ids)->get();
        foreach($student as $stu)
        {
            $grades = $stu->grades->where('semester_id', $semesterID)->where('subject_id', $courseID)->first();
            if(count($grades)>0)
            $data[]=array($stu->id,$stu->user[0]->name_en,$stu->user[0]->email,$stu->graduated_status,$grades->midterm,$grades->quiz,$grades->section,$grades->participation,$grades->attendance,$grades->project,$grades->assignment,$grades->total_grade);
        }

        Excel::create('StudentReport', function ($excel) use ($data) {

            $excel->sheet('StudentReport', function ($sheet) use ($data){

                $sheet->fromArray($data);
            });
        })->export('xls');
    }

    public function exportStudent_Instructor ($courseID , $semesterID)
    {
        $data = array();
        $data[]=array('ID','StudentName','Email','Level','Midterm','quiz','section','participate','attendance','project','assignment','Total');
        $allSemester = Semester::orderBy('id', 'desc')->select('name', 'id')->get();
        $AvilableSemester = $allSemester[0]->id;
        $timeTable_id = Timetable::select('id')->where([['semester_id',$semesterID],['timetableable_id', Auth::user()->userable_id] , ['subject_id',$courseID]])->get();
        $student = DB::table('student_timetable')->select('student_id')->whereIn('timetable_id',$timeTable_id)->get();
        $s_ids=[];
        foreach ($student as $stu){$s_ids[]=$stu->student_id;}
        $student = Student::whereIn('id',$s_ids)->get();
        foreach($student as $stu)
        {
            $grades = $stu->grades->where('semester_id', $semesterID)->where('subject_id', $courseID)->first();
            if(count($grades)>0)
            $data[]=array($stu->id,$stu->user[0]->name_en,$stu->user[0]->email,$stu->graduated_status,$grades->midterm,$grades->quiz,$grades->section,$grades->participation,$grades->attendance,$grades->project,$grades->assignment,$grades->total_grade);
        }

        Excel::create('StudentReport', function ($excel) use ($data) {

            $excel->sheet('StudentReport', function ($sheet) use ($data){

                $sheet->fromArray($data);
            });
        })->export('xls');
    }
}
