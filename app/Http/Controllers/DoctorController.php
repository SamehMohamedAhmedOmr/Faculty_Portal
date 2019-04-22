<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Doctor;
use App\Grade;
use App\Grade_distribution;
use App\Http\Requests\UserRequest;
use App\Mail;
use App\Material;
use App\Open_course;
use App\Semester;
use App\Student;
use App\Student_affair;
use App\Teacher_assistant;
use App\Timetable;
use App\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use phpDocumentor\Reflection\Types\Null_;
use Validator;
use Illuminate\Validation\Rule;
use App\Exam_timetable;
use App\Place;


//use Illuminate\Validation\Validator;


class DoctorController extends Controller
{
    /*Select ALl doctors to Manage in Admin view Panel */
    public function index()
    {
        $user = Auth::user();
        if($user->userable_type == 'Adm') {
            $list = Auth::user()->where('userable_type','=','doc')->paginate();
            return view('Portal.admin_panel.Panel',compact('list'));
        }
        else
        {  return view('Portal.public.index'); }
    }

    /*Create New Doctor (view) */
    public function create()
    {
        $user = Auth::user();
        if($user->userable_type == 'Adm')
        {
            $user = Auth::user();
            if($user->userable_type == 'Adm')
            {
                return view('Portal.admin_panel.Panel');
            }
            else
            {  return view('Portal.public.index'); }
        }
    }

    /*Store New DOctor (post request and store it in DB) */
    public function store(UserRequest $request)
    {
        $limit = Carbon::parse(today())->addWeek(1)->format('Y-m-d');
        $this->validate($request, [
            'email'=>'unique:users',
            'password'=>'required|max:30|min:8',
            'national_id' => 'unique:users,national_id',
            'hire_date'=>'required|date|after_or_equal:today|before:'.$limit
        ],
            [
                'password.required'  => 'Password is required',
                'hire_date.required'  => 'Hire date is required',

                'password.max'  => 'Password must be between 8 and 30 characters',
                'password.min'  => 'Password must be between 8 and 30 characters',
                'email.unique'  => 'This email already exists',
                'national_id.unique'  => 'The national ID already exists',
                'hire_date.date'  => 'Please enter valid date',
                'hire_date.after_or_equal'  => 'Please enter valid date - after today',
                'hire_date.before'  => 'Please enter valid date - before a week from now',
            ]);

        /*get user last id*/
        $lastID = DB::table('users')->orderBy('userable_id', 'DESC')->first();
        $lastID= $lastID->userable_id+1;

        $user = new Doctor();
        $user->hire_date = $request->hire_date;
        $user->id=$lastID;

        if($user->save())
        {
            $data = Response::json($user->id);
            User::Create(
                [
                    'userable_id' => $data->original ,'userable_type'=>'Doc',
                    'name_ar' => $request->name_ar ,'name_en' => $request->name_en ,
                    'email' => $request->email , 'password' => bcrypt($request->password) ,
                    'national_id' => $request->national_id , 'address' => $request->address ,
                    'phone' => $request->phone ,
                    'DOB' => $request->DOB , 'gender' => $request->gender
                ]);
            return redirect()->action('DoctorController@index')->with('message', 'Doctor added successfully');
        }
        else
        { App::abort(500, 'Error'); }
    }

    /*show Doctors this method is called by AJax Search Request*/
    public function show(Request $request)
    {
        if ($request->ajax()) {
            $output = "";
            if($request->search !== 'non')
                $doctors = User::where('name_en', 'LIKE', '%' . $request->search . "%")->where('userable_type', '=', 'Doc')->get();
            else
                $doctors = User::where('userable_type', '=', 'Doc')->get();
            if ($doctors) {
                foreach ($doctors as $key => $doc) {
                    $output .= '<tr>' .
                        '<td>' . $doc->userable_id . '</td>' .
                        '<td>' . $doc->name_en . '</td>' .
                        '<td>' . $doc->email . '</td>' .
                        '<td>' . $doc->phone . '</td>' .
                        '<td>
                                <form method="GET" action="/Panel/Doctor/' . $doc->userable_id . '/edit">
                                    <button type="submit" class="btn btn-info EditButton"> Edit
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </form>
                            </td>
                            <meta name="csrf-token" content="' . csrf_token() . '">
                           </tr>';
                }
                if (!$output)
                {
                    $output = "<tr>
                                    <td colspan=\"6\">Not Found</td>
                                  </tr>";
                }
                return Response($output);
            }
        }
    }

    /*Edit Specific Doctor (view) */
    public function edit($id)
    {
        try
        {
            $Auth_user = Auth::user();
            if($Auth_user->userable_type == 'Adm') {
                $user = Doctor::findOrFail($id);
                $basics=$user->user;
                return view('Portal.admin_panel.Panel', compact('user','basics'));
            }
            else
            {  return view('Portal.public.index'); }
        }
        catch(ModelNotFoundException $e)
        {
            return redirect()->action('DoctorController@index');
        }
    }

    /*update Doctor data and store it in DB */
    public function update(UserRequest $request, $id)
    {
        $this->validate($request,
            [
                'email' => 'unique:users,email,'. $id.',userable_id',
                'national_id' => 'unique:users,national_id,'. $id.',userable_id',
            ],
            [
                'working_hours.required'  => 'Number of working hours is required',
                'email.unique'  => 'This email already exists',
                'national_id.unique'  => 'The national ID already exists',
            ]);

        $doc = User::find($id);
        $doc->address = $request->address;
        $doc->phone = $request->phone;
        $doc->name_ar = $request->name_ar;
        $doc->name_en = $request->name_en;
        $doc->email = $request->email;
        $doc->gender = $request->gender;
        $doc->DOB = $request->DOB;
        if($doc->save())
        {  return redirect()->action('DoctorController@index')->with('message', 'Doctor updated successfully'); }
        else
        {   App::abort(500, 'Error'); }
    }

    /*remove Doctor From DB*/
    public function destroy($id)
    {
        $Auth_user = Auth::user();
        if($Auth_user->userable_type == 'Adm') {
            $dor = Doctor::findOrFail($id);
            $dor->delete();
            User::where('userable_id',$id)->delete();
            return response()->json([
                'success' => 'Doctor has been deleted successfully!'
            ]);
        }
        else
        {  return view('Portal.public.index'); }
    }

    /*Manage courses which Leader is responsible to*/
    public function Manage_Course_distribution ()
    {
        if(Auth::user()->userable_type == 'Doc')
        {
            $now = Carbon::now();
            $semesterAvilable='false';
            $currentSemester = Semester::where('complete',0)->get();
            /*Check if semester has started or not*/
            if(count($currentSemester)>0)
            {
                if($now>=$currentSemester[0]->open_register_date && $now <= $currentSemester[0]->end_register_date)
                { $semesterAvilable='true';}
                $MaxSemesterID=Semester::max('id');
                $courses = Open_course::where([['doctor_id',Auth::user()->userable_id],['semester_id',$MaxSemesterID] ])->get();
                return view('Portal.Doctor_panel.Panel',compact('courses','MaxSemesterID','semesterAvilable'));
            }
            else
            {
                return redirect()->back()->withErrors(['Error' => 'Register isn\'t Avilable right now , or you are blocked from registration temporarily ']);
            }

        }
        else
        {  return view('Portal.public.index'); }
    }

    public function Save_Course_distribution(Request $request)
    {
        if(Auth::user()->userable_type != 'Doc')
        {return view('Portal.public.index');}
        $AvilableGrades = $request->AvilableGrades;

        if( is_numeric($request->section) && is_numeric($request->quiz) && is_numeric($request->Assignment) &&
            is_numeric($request->Project) && is_numeric($request->Participation) && is_numeric($request->Attendance) &&
            is_numeric($request->Midterm) && is_numeric($request->AvilableGrades))
        {
            if($request->Midterm>=10 && $request->Midterm <= $AvilableGrades)
            {
                if
                (
                    (
                         $request->section + $request->quiz + $request->Assignment + $request->Project+
                         $request->Participation + $request->Attendance + $request->Midterm
                    )==$AvilableGrades
                )
                {
                    $ManageCourse = DB::table('grade_distributions')->where([['subject_id',$request->subject_id],['semester_id',$request->semester_id],['doctor_id',Auth::user()->userable_id]])->first();
                    if($ManageCourse!==null)
                    {
                        $updateDetails = array
                        (
                            'section' => $request->section,
                            'quiz_grade'       => $request->quiz,
                            'midterm'       => $request->Midterm,
                            'participation'       => $request->Participation,
                            'attendance'       => $request->Attendance,
                            'project'       => $request->Project,
                            'assignment'       => $request->Assignment
                        );
                        DB::table('grade_distributions')->where([['subject_id',$request->subject_id],['semester_id',$request->semester_id],['doctor_id',Auth::user()->userable_id]])
                        ->update ($updateDetails);
                        echo "exists";

                    }else
                    {
                        $insertedArray = array
                        (
                            'semester_id'=> $request->semester_id,
                            'subject_id'=> $request->subject_id,
                            'doctor_id'=> Auth::user()->userable_id,
                            'section' => $request->section,
                            'quiz_grade'       => $request->quiz,
                            'midterm'       => $request->Midterm,
                            'participation'       => $request->Participation,
                            'attendance'       => $request->Attendance,
                            'project'       => $request->Project,
                            'assignment'       => $request->Assignment
                        );

                        DB::table('grade_distributions')->insert($insertedArray);
                    }
                    return redirect()->back()->with('message', 'Grades Updated Successfully');
                }
                else
                {
                    return redirect()->back()->withErrors(['Error'=>'total grades Not equal to Available Coursework grades']);
                }
            }
            else
            {
                return redirect()->back()->withErrors(['Error'=>'Midterm grade must Range Between 10 and '.$AvilableGrades.'']);
            }
        }
        else
        {
            return redirect()->back()->withErrors(['Error'=>'grades must be A valid integer Number']);
        }
    }

    public function ManageCourseIndex(Request $request)
    {
        if(Auth::user()->userable_type != 'Doc')
        {return view('Portal.public.index');}

        $allSemester = Semester::orderBy('id', 'desc')->select('name', 'id','end_register_date')->get();
        $endTime = $allSemester[0]->end_register_date;
        $AvilableSemester = $allSemester[0]->id;

        /*check if Avilable Time to edit Started or Not*/
        $now = Carbon::now();
        if($now<$endTime)
        {return redirect()->back()->with('Alert','Manage Course Not Started Yet , it will be started after '.$endTime);}
        /*End check Time*/

        if(count($request->all()) == 0) {
            return view('Portal.Doctor_panel.Panel', compact('allSemester', 'AvilableSemester'));
        }
        else
        {
            /*first get TImeTableID*/
            $timeTable_id = Timetable::select('id')->where([['semester_id',$request->SemesterName],['timetableable_id', Auth::user()->userable_id] , ['subject_id',$request->courseID]])->get();
            /*get all student who register this course with this doctor*/
            $student = DB::table('student_timetable')->select('student_id')->whereIn('timetable_id',$timeTable_id)->get();
            $s_ids=[];
            foreach ($student as $stu){$s_ids[]=$stu->student_id;}
            $student = Student::whereIn('id',$s_ids)->paginate(25);
            /*get grades distributions*/
            $grade_dist = Grade_distribution::where([ ['semester_id',$request->SemesterName] , ['subject_id',$request->courseID] ])->first();
            if(count($grade_dist)==0)
            {
                return redirect()->back()->withErrors(['Error'=>'this page not available, please contact with the leader of the course']);
            }
            $courseID= $request->courseID;
            /*get All Material related to this course*/
            $AllMaterial = Material::where([ ['semester_id', $request->SemesterName], ['subject_id',$request->courseID] ,['doctor_id', Auth::user()->userable_id ]])->get();
            return view('Portal.Doctor_panel.Panel', compact('allSemester', 'AvilableSemester','student','grade_dist','courseID','AllMaterial'));
        }
    }

    public function ManageCourse_showCourses ($semester_id , Request $request)
    {
        if(Auth::user()->userable_type != 'Doc')
        {return view('Portal.public.index');}

        $All_oprn_Courses = Timetable::distinct()->select('subject_id')->where([ ['semester_id',$semester_id],['timetableable_id', Auth::user()->userable_id] ])->get();
        foreach($All_oprn_Courses as $course)
        {
            echo '<option class="" id="CourseID" value="'.$course->subject->subject->id.'">'.$course->subject->subject->name.'</option>';
        }
    }

    public function updateGrades (Request $request)
    {
        if(Auth::user()->userable_type != 'Doc')
        {return view('Portal.public.index');}

        /*get grades distributions*/
        $grade_dist = Grade_distribution::where([ ['semester_id',$request->Sem_id] , ['subject_id',$request->courseID] ])->first();

        /*Transform all empty values into null*/
        $total=0;
        foreach ($request->input() as $key => $value) {
            if (empty($value)) {
                $request->request->set($key, 0);
            }
        }
        $errors='';
        /*validate all request*/
        if(!($request->section >= 0 && $request->section <= $grade_dist->section))
        { $errors.='section must be between 0 and '.$grade_dist->section.'<br>'; }

        if(!($request->quiz >= 0 && $request->quiz <= $grade_dist->quiz_grade))
        { $errors.='Quiz must be between 0 and '.$grade_dist->quiz_grade.'<br>'; }

        if(!($request->midterm >= 0 && $request->midterm <= $grade_dist->midterm))
        { $errors.='Midterm must be between 0 and '.$grade_dist->midterm.'<br>'; }

        if(!($request->participate >= 0 && $request->participate <= $grade_dist->participation))
        { $errors.='Participation must be between 0 and '.$grade_dist->participate.'<br>'; }

        if(!($request->attendance >= 0 && $request->attendance <= $grade_dist->attendance))
        { $errors.='attendance must be between 0 and '.$grade_dist->attendance.'<br>'; }

        if(!($request->project >= 0 && $request->project <= $grade_dist->project))
        { $errors.='project must be between 0 and '.$grade_dist->project.'<br>'; }

        if(!($request->assigment >= 0 && $request->assigment <= $grade_dist->assignment))
        { $errors.='assigment must be between 0 and '.$grade_dist->assigment.'<br>'; }

        /*if there is no error , then update grades*/
        if($errors=='')
        {
            $total = $total + $request->section;
            $total = $total + $request->quiz;
            $total = $total + $request->midterm;
            $total = $total + $request->participate;
            $total = $total + $request->attendance;
            $total = $total + $request->project;
            $total = $total + $request->assigment;

            /*check if student have record or not*/
            $checkExists = Grade::where([ ['student_id',$request->stu_id ], ['semester_id',$request->Sem_id] , ['subject_id',$request->courseID] ])->first();
            if(count($checkExists)>0)
            {
                $updateDetails = array
                (
                    'doctor_id'=>Auth::user()->userable_id ,
                    'section' => $request->section,
                    'quiz'       => $request->quiz,
                    'midterm'       => $request->midterm,
                    'participation'       => $request->participate,
                    'attendance'       => $request->attendance,
                    'project'       => $request->project,
                    'assignment'       => $request->assigment,
                    'total_grade' => $total
                );
                DB::table('grades')->where([ ['student_id',$request->stu_id ], ['semester_id',$request->Sem_id] , ['subject_id',$request->courseID]  ])
                    ->update ($updateDetails);
                return "success";
            }
            else
            {
                $insertedArray = array
                (
                    'student_id' => $request->stu_id,
                    'semester_id' => $request->Sem_id,
                    'subject_id' => $request->courseID,
                    'doctor_id'=>Auth::user()->userable_id ,
                    'section' => $request->section,
                    'quiz'       => $request->quiz,
                    'final'     => null ,
                    'midterm'       => $request->midterm,
                    'participation'       => $request->participate,
                    'attendance'       => $request->attendance,
                    'project'       => $request->project,
                    'assignment'       => $request->assigment,
                    'total_grade' => $total
                );

                DB::table('grades')->insert($insertedArray);
                return "success";
            }
        }
        else
        {
            return $errors;
        }

    }

    public function NotifyStudents (Request $request)
    {
        /*Validate Request Data*/

        $validator = Validator::make($request->all(),[
            'header'=>'required|string|min:3|max:20|regex:"[A-Za-z0-9 ]{3,20}"' ,
            'message'=>'required|string|min:10|max:200|regex:"[A-Za-z0-9 ]{10,200}"'
        ],
        [

            'header.require'=>'header for E-mail is required',
            'header.min'=>'header for E-mail must have at least 3 characters',
            'header.max'=>'header for E-mail must have at most 20 characters',
            'header.regex'=>'pls enter valid header range between (3,20) characters only',

            'message.require'=>'message for E-mail is required',
            'message.min'=>'message for E-mail must have at least 10 character ',
            'message.max'=>'message for E-mail must have at most 200 character',
            'message.regex'=>'pls enter valid message range between (10,200) characters only',
        ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
        else
        {
            foreach ($request->NotifyI_ids as $id)
            {
                $Mail = new Mail();
                $Mail->sender_id=Auth::user()->userable_id;
                $Mail->receiver_id=$id;
                $Mail->description=$request->message;
                $Mail->header=$request->header;
                $Mail->date_time = Carbon::now();
                $Mail->save();
            }
            return redirect()->back()->with('message','All students has Notified successfully');
        }


    }

    public function showSchedule (Request $request)
    {
        if(Auth::user()->userable_type != 'Doc') { return view('Portal.public.index'); }

        if($request->Schedule == null)
        {return view('Portal.Doctor_panel.Panel');}
        else
        {
            $this->validate($request,[
                'Schedule'=>Rule::in('Midterm Exam','Practical Exam','Final Exam')
            ]);
            $show=$request->Schedule;
            if($show=='Midterm Exam')
            {
                $now = Carbon::now();
                $lastSemester = Semester::orderBy('id','desc')->where('isSummer','0')->first();
                $Avilable_Date = Carbon::parse($lastSemester->end_register_date)->addWeek(1)->format('Y-m-d');
                $midtermTime = $lastSemester->midterm_week;
                /*الطالب بيشوف الجدول بتاع الميدترم من الاسبوع السادس للسابع*/
                if($now< $Avilable_Date)
                { return redirect()->back()->with('Alert','you can Access Midterm Schedule only after  ('.$Avilable_Date.') '); }

                /*Now Select all exam_Sechdule where type = 0*/
                $allExamsSchedule = Exam_timetable::orderBy('time','asc')->where([ ['type','0'] , ['semester_id',$lastSemester->id]])->get();
                /*Select all open courses */
                $subjects= Open_course::where('semester_id',$lastSemester->id)->get();
                /*Array of weekDays*/
                $weekDays = array ('Sunday','Monday','Tuesday','Wednesday','Thursday');
                /*Select ALl Halls*/
                $halls = Place::where('type','0')->get();
                /*Select all teacher Assistant*/
                $ta = Teacher_assistant::all();
                return view('Portal.Doctor_panel.Panel',compact('allExamsSchedule','subjects','weekDays','midtermTime','halls','ta','show','midtermTime'));

            }
            elseif($show=='Practical Exam')
            {
                $now = Carbon::now();
                $lastSemester = Semester::orderBy('id','desc')->where('isSummer','0')->first();
                $Avilable_Date = Carbon::parse($lastSemester->midterm_week)->addWeek(1)->format('Y-m-d');
                $midtermTime = $lastSemester->midterm_week;
                $practicalTIme = Carbon::parse($midtermTime)->addWeek(2)->format('Y-m-d');
                if($now< $Avilable_Date)
                { return redirect()->back()->with('Alert','you can Access Practical Schedule only after  ('.$Avilable_Date.') '); }

                /*Now Select all exam_Sechdule where type = 2*/
                $allExamsSchedule = Exam_timetable::orderBy('time','asc')->where([ ['type','2'] , ['semester_id',$lastSemester->id]])->get();
                /*Select all open courses*/
                $subjects= Open_course::where('semester_id',$lastSemester->id)->get();
                /*Array of weekDays*/
                $weekDays = array ('Sunday','Monday','Tuesday','Wednesday','Thursday');
                /*Select ALl Halls*/
                $labs = Place::where('type','1')->get();
                /*Select all teacher Assistant*/
                $ta = Teacher_assistant::all();
                return view('Portal.Doctor_panel.Panel',compact('canEdit','show','allExamsSchedule','subjects','weekDays','practicalTIme','labs','ta'));
            }
            else
            {
                $now = Carbon::now();
                $lastSemester = Semester::orderBy('id','desc')->where('isSummer','0')->first();
                $Avilable_Date = Carbon::parse($lastSemester->midterm_week)->addWeek(1)->format('Y-m-d');
                if($now< $Avilable_Date)
                { return redirect()->back()->with('Alert','you can Access Final Schedule only after  ('.$Avilable_Date.') '); }

                $midtermTime = $lastSemester->midterm_week;
                $Final_Start_Time = Carbon::parse($midtermTime)->addWeek(3)->format('Y-m-d');
                $Final_end_Time = $lastSemester->end_date;

                /*Now Select all exam_Sechdule where type = 1*/
                $allExamsSchedule = Exam_timetable::orderBy('time','asc')->where([ ['type','1'] , ['semester_id',$lastSemester->id]])->get();
                /*Select all open courses */
                $subjects= Open_course::where('semester_id',$lastSemester->id)->get();
                /*Select ALl Halls*/
                $halls = Place::where('type','0')->get();
                /*Select all teacher Assistant*/
                $ta = Teacher_assistant::all();
                $FinalExam_Times= $allExamsSchedule->groupBy('date');
                return view('Portal.Doctor_panel.Panel',compact('canEdit','Final_Start_Time','show','Final_end_Time','allExamsSchedule','subjects','halls','ta','FinalExam_Times'));
            }
        }
    }

    /*set final grade to courses*/
    public function Final_grades_view (Request $request)
    {
        /*check Authentication */
        if(Auth::user()->userable_type != 'Doc')
        {return view('Portal.public.index');}

        /*check Avilable  dates*/
        $last_semester = Semester::orderBy('id', 'desc')->select('name', 'id','end_date','final_monitoring_grades_date')->get();
        if(count($last_semester) > 0 )
        {
            $semester_end_time = $last_semester[0]->end_date;
            $final_monitor_date =  $last_semester[0]->final_monitoring_grades_date;
            $now = Carbon::now();
            if($now > $semester_end_time && $now < $final_monitor_date) {

                /*select all doctor's course to show */
                $All_oprn_Courses = Timetable::distinct()->select('subject_id')->where([['semester_id', $last_semester[0]->id], ['timetableable_id', Auth::user()->userable_id]])->get();
                /*if doctor don't select any course return a list of selected courses*/
                if ($request->has('courseID'))
                {
                    /*first get TImeTableID*/
                    $timeTable_id = Timetable::select('id')->where([['semester_id',$last_semester[0]->id],['timetableable_id', Auth::user()->userable_id] , ['subject_id',$request->courseID]])->get();

                    /*get all student who register this course with this doctor*/
                    $student = DB::table('student_timetable')->select('student_id')->whereIn('timetable_id',$timeTable_id)->get();
                    $s_ids=[];

                    foreach ($student as $stu){$s_ids[]=$stu->student_id;}
                    $student = Student::whereIn('id',$s_ids)->paginate(25);

                    /*get grades distributions*/
                    $grade_dist = Grade_distribution::where([ ['semester_id',$last_semester[0]->id] , ['subject_id',$request->courseID] ])->first();
                    if(count($grade_dist)==0)
                    {
                        return redirect()->back()->withErrors(['Error'=>'this page not available, please contact with the leader of the course']);
                    }
                    $courseID= $request->courseID;
                    $semester_id = $last_semester[0]->id;
                    return view('Portal.Doctor_panel.Panel', compact( 'student','grade_dist','courseID','All_oprn_Courses','semester_id'));
                }
                /* in case of dont select any course show*/
                else
                {
                    return view('Portal.Doctor_panel.Panel', compact('All_oprn_Courses'));
                    /*else return a list of all students with course distributions*/
                }
            }
            else
            {
                return redirect()->back()->with(['Alert'=>'you can access this page only between dates ('.$semester_end_time.' -> '.$final_monitor_date.') ']);
            }
        }
        else
        {
            return redirect()->back()->withErrors(['Error'=>'there is no semester opened yet ']);
        }

    }

    public function save_final_grades (Request $request)
    {
        /*validate data*/
        $grade_ids = [];
        foreach($request->final as $index => $final )
        {
           if(($final<0 || $final >60) && $final!=null)
           {return redirect()->back()->withErrors(['Error'=>'pls check your final grades it\'s not correct ']);}
           $grade_ids[]=array($request->stu_id[$index],$final);
        }

        $semester_id = $request->sem_id;
        $course_id = $request->courseID;
        $doctor_id = Auth::user()->userable_id;


       foreach($grade_ids as $student)
       {

           /*check if student have record or not*/
           $checkExists = Grade::where([ ['student_id',$student[0]], ['semester_id',$semester_id] , ['subject_id',$course_id] ])->first();
           if(count($checkExists)>0)
           {
               $total = $checkExists->section +$checkExists->quiz+$checkExists->midterm+$checkExists->participation+$checkExists->attendance+$checkExists->project+$checkExists->assignment;
               $total=$total+$student[1];
               $updateDetails = array
               (
                   'final'       => $student[1],
                   'total_grade' => $total
               );
               DB::table('grades')->where([ ['student_id',$student[0]], ['semester_id',$semester_id] , ['subject_id',$course_id]])
                   ->update ($updateDetails);
           }
           else
           {
               $total = $student[1];
               $insertedArray = array
               (
                   'student_id' => $student[0],
                   'semester_id' => $semester_id,
                   'subject_id' => $course_id,
                   'doctor_id'=>$doctor_id,
                   'section' => null,
                   'quiz'       => null,
                   'final'     => $student[1] ,
                   'midterm'       => null ,
                   'participation'       => null ,
                   'attendance'       => null ,
                   'project'       => null ,
                   'assignment'       => null ,
                   'total_grade' => $total
               );

               DB::table('grades')->insert($insertedArray);
           }
       }
        return redirect()->back()->with('message','course final grade has been updated successfully');

    }

}
