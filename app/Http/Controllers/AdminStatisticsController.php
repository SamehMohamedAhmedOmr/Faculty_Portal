<?php

namespace App\Http\Controllers;
use App\Grade;
use App\Open_course;
use App\Semester;
use App\Student;
use App\Timetable;
use Illuminate\Support\Facades\DB;
use Khill\Lavacharts\Exceptions\InvalidCellCount;
use Khill\Lavacharts\Exceptions\InvalidColumnRole;
use Khill\Lavacharts\Exceptions\InvalidColumnType;
use Khill\Lavacharts\Exceptions\InvalidConfigValue;
use Khill\Lavacharts\Exceptions\InvalidLabel;
use Khill\Lavacharts\Exceptions\InvalidRowDefinition;
use Khill\Lavacharts\Exceptions\InvalidRowProperty;
use Khill\Lavacharts\Lavacharts as Lava;

class AdminStatisticsController extends Controller
{
    public function studentInEachLevel(){

        $level1 = Student::where('graduated_status',1)->count();
        $level2 = Student::where('graduated_status',2)->count();
        $level3 = Student::where('graduated_status',3)->count();
        $level4 = Student::where('graduated_status',4)->count();

        $lava = new Lava();
        $studentNumber = $lava->DataTable();

        try {
            $studentNumber->addStringColumn('Level')
                ->addNumberColumn('Student')
                ->addRoleColumn('string', 'style');
            $studentNumber->addRows([
                ['Level 1', $level1,  'green'],
                ['Level 2', $level2,  'orange'],
                ['Level 3', $level3,   'red'],
                ['Level 4', $level4,  'blue']
            ]);
        } catch (InvalidColumnType $e) {
        } catch (InvalidLabel $e) {
        } catch (InvalidRowDefinition $e) {
        } catch (InvalidColumnRole $e) {
        } catch (InvalidConfigValue $e) {
        }

        $lava->ColumnChart('studentNumber', $studentNumber, [
            'title' => 'Number of Student in each level',
            'titleTextStyle' => [
                'color'    => '#eb6b2c',
                'fontSize' => 20
            ]
        ]);

        $statistics = 1;
        return view('Portal.admin_panel.Panel',compact('lava','statistics'));
    }

    public function registeredStudent(){

        $currentSemester = Semester::where('complete',0)->get();
        if($currentSemester->count() > 0) {
            $openCourse = Open_course::where('semester_id', $currentSemester[0]->id)->get();

            $registered = [];

            foreach ($openCourse as $open) {
                $timeTable_id = Timetable::select('id')->where([
                    ['semester_id', $currentSemester[0]->id], ['timetableable_type', 'Doc'], ['subject_id', $open->subject->id]
                ])->get();
                $studentsCount = DB::table('student_timetable')->select('student_id')
                    ->whereIn('timetable_id', $timeTable_id)->count();

                $registered [] = $studentsCount;
            }

            $lava = new Lava();
            $studentNumber = $lava->DataTable();

            try {
                $studentNumber->addStringColumn('Course')
                    ->addNumberColumn('Student')
                    ->addRoleColumn('string', 'style');
                $counter = 0;
                foreach ($openCourse as $open){
                    $studentNumber->addRow([$open->subject->name, $registered[$counter], $this->random_color()]);
                    $counter++;
                }
            } catch (InvalidColumnType $e) {
            } catch (InvalidLabel $e) {
            } catch (InvalidRowDefinition $e) {
            } catch (InvalidColumnRole $e) {
            } catch (InvalidCellCount $e) {
            } catch (InvalidRowProperty $e) {
            }

            $lava->ColumnChart('studentNumber', $studentNumber, [
                'title' => 'Number of Student registered in each Course',
                'titleTextStyle' => [
                    'color'    => '#eb6b2c',
                    'fontSize' => 20
                ]
            ]);

            $statistics = 2;
            return view('Portal.admin_panel.Panel',compact('lava','statistics'));
        }
        else{
            $lava = null;
            $statistics = 2;
            return view('Portal.admin_panel.Panel',compact('lava','statistics'));
        }
    }

    public function semesterResult(){

        $currentSemester = Semester::where('complete',1)->max('id');

            $openCourse = Open_course::where('semester_id',$currentSemester)->get();

            $gradeSucceed = [];
            $gradeFailed = [];

            foreach ($openCourse as $open){

                $gradeSuccess = Grade::where('semester_id',$currentSemester)
                    ->where('subject_id',$open->subject->id)
                    ->where('total_grade','>=',50)
                    ->count();
                $gradeFail = Grade::where('semester_id',$currentSemester)
                    ->where('subject_id',$open->subject->id)
                    ->where('total_grade','<',50)
                    ->count();

                $gradeSucceed []  = $gradeSuccess;
                $gradeFailed  []  = $gradeFail;
            }


            $lava = new Lava();
            $studentNumber = $lava->DataTable();

            try {
                $studentNumber->addStringColumn('Course')
                    ->addNumberColumn('Student Success')
                    ->addNumberColumn('Student fail');
                $counter = 0;
                foreach ($openCourse as $open){
                    $studentNumber->addRow([$open->subject->name, $gradeSucceed[$counter] ,$gradeFailed[$counter]]);
                    $counter++;
                }
            } catch (InvalidColumnType $e) {
            } catch (InvalidLabel $e) {
            } catch (InvalidRowDefinition $e) {
            } catch (InvalidCellCount $e) {
            } catch (InvalidRowProperty $e) {
            }

            $lava->ColumnChart('studentNumber', $studentNumber, [
                'title' => 'Result in each Course Last Semester',
                'titleTextStyle' => [
                    'color'    => '#eb6b2c',
                    'fontSize' => 20
                ]
            ]);

            $statistics = 3;
            return view('Portal.admin_panel.Panel',compact('lava','statistics','openCourse'));
    }

    public function averageGPA(){

        $level1 = Student::where('graduated_status',1)->get();
        $level2 = Student::where('graduated_status',2)->get();
        $level3 = Student::where('graduated_status',3)->get();
        $level4 = Student::where('graduated_status',4)->get();

        $levelOneGPA = [];
        $levelTwoGPA = [];
        $levelThreeGPA = [];
        $levelFourGPA = [];

        foreach ($level1 as $level){
            $grades = Grade::where('student_id',$level->id)->get();
            $levelOneGPA [] = $this->calculateGPA($grades);
        }
        foreach ($level2 as $level){
            $grades = Grade::where('student_id',$level->id)->get();
            $levelTwoGPA [] = $this->calculateGPA($grades);
        }
        foreach ($level3 as $level){
            $grades = Grade::where('student_id',$level->id)->get();
            $levelThreeGPA [] = $this->calculateGPA($grades);
        }
        foreach ($level4 as $level){
            $grades = Grade::where('student_id',$level->id)->get();
            $levelFourGPA [] = $this->calculateGPA($grades);
        }

        $lava = new Lava();
        $studentNumber = $lava->DataTable();

        try {
            $studentNumber->addStringColumn('Level')
                ->addNumberColumn('Max GPA')
                ->addNumberColumn('Average GPA')
                ->addNumberColumn('Min GPA');

            if(count($levelOneGPA)>0){
                $averageLevel1GPA = array_sum($levelOneGPA)/count($levelOneGPA);

                $studentNumber ->addRow(['Level 1', max($levelOneGPA), min($levelOneGPA), $averageLevel1GPA]);
            }
            else{
                $studentNumber ->addRow(['Level 1', 0, 0, 0]);
            }
            if(count($levelTwoGPA)>0){
                $averageLevel2GPA = array_sum($levelTwoGPA)/count($levelTwoGPA);
                $studentNumber ->addRow(['Level 2', max($levelTwoGPA), min($levelTwoGPA), $averageLevel2GPA]);
            }
            else{
                $studentNumber ->addRow(['Level 2', 0, 0, 0]);
            }
            if(count($levelThreeGPA)>0){
                $averageLevel3GPA = array_sum($levelThreeGPA)/count($levelThreeGPA);
                $studentNumber ->addRow(['Level 3', max($levelThreeGPA), min($levelThreeGPA), $averageLevel3GPA]);
            }
            else{
                $studentNumber ->addRow(['Level 3', 0, 0, 0]);
            }

            if(count($levelFourGPA)>0){
                $averageLevel4GPA = array_sum($levelFourGPA)/count($levelFourGPA);
                $studentNumber ->addRow(['Level 4', max($levelFourGPA), min($levelFourGPA), $averageLevel4GPA]);
            }
            else{
                $studentNumber ->addRow(['Level 4', 0, 0, 0]);
            }

        } catch (InvalidCellCount $e) {
        } catch (InvalidColumnType $e) {
        } catch (InvalidLabel $e) {
        } catch (InvalidRowDefinition $e) {
        } catch (InvalidRowProperty $e) {
        }


        $lava->LineChart('studentNumber', $studentNumber, [
            'title' => 'GPA of each Level',
            'titleTextStyle' => [
                'color'    => '#eb6b2c',
                'fontSize' => 20
            ]
        ]);

        $statistics = 4;
        return view('Portal.admin_panel.Panel',compact('lava','statistics'));
    }


    function random_color_part() {
        return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
    }

    function random_color() {
        return $this->random_color_part() . $this->random_color_part() . $this->random_color_part();
    }

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
            return $gpa;

        }
        return 0;
    }

}
