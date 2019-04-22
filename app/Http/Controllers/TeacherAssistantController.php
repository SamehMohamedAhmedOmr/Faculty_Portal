<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Doctor;
use App\Mail;
use App\Semester;
use App\Http\Requests\UserRequest;
use App\Student_affair;
use Carbon\Carbon;
use App\Teacher_assistant;
use App\User;
use App\Grade;
use App\Timetable;
use DB;
use App\Student;
use App\Grade_distribution;
use App\Material;
//use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Validator;
use Illuminate\Validation\Rule;
use App\Exam_timetable;
use App\Place;
use App\Open_course;

class TeacherAssistantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if($user->userable_type == 'Adm') {
            $list = Auth::user()->where('userable_type','=','t_a')->paginate();
            return view('Portal.admin_panel.Panel',compact('option','list'));
        }
        else
        {  return view('Portal.public.index');}
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
        {
            $user = Auth::user();
            if($user->userable_type == 'Adm')
            {
                return view('Portal.admin_panel.Panel');
            }
            else
            {   return view('Portal.public.index'); }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

        /*get Last id of user*/
        $lastID = DB::table('users')->orderBy('userable_id', 'DESC')->first();
        $lastID= $lastID->userable_id+1;

        $user = new Teacher_assistant();
        $user->hire_date = $request->hire_date;
        $user->id=$lastID;

        if($user->save()) {
            $data = Response::json($user->id);
            User::Create(['userable_id' => $data->original ,'userable_type'=>'T_A',
                'name_ar' => $request->name_ar ,'name_en' => $request->name_en ,
                'email' => $request->email , 'password' => bcrypt($request->password) ,
                'national_id' => $request->national_id , 'address' => $request->address ,
                'phone' => $request->phone ,
                'DOB' => $request->DOB , 'gender' => $request->gender]);
            return redirect()->action('TeacherAssistantController@index')->with('message', 'Teacher Assistant added successfully');
        }
        else
        {   App::abort(500, 'Error'); }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if ($request->ajax()) {
            $output = "";
            if($request->search !== 'non')
                $assistants = User::where('name_en', 'LIKE', '%' . $request->search . "%")->where('userable_type', '=', 'T_A')->get();
            else
                $assistants = User::where('userable_type', '=', 'T_A')->get();
            if ($assistants) {
                foreach ($assistants as $key => $ta) {
                    $output .= '<tr>' .
                        '<td>' . $ta->userable_id . '</td>' .
                        '<td>' . $ta->name_en . '</td>' .
                        '<td>' . $ta->email . '</td>' .
                        '<td>' . $ta->phone . '</td>' .
                        '<td>
                                <form method="GET" action="/Panel/TA/' . $ta->userable_id . '/edit">
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
            if($Auth_user->userable_type == 'Adm') {
                $user = Teacher_assistant::findOrFail($id);
                $basics=$user->user;
                return view('Portal.admin_panel.Panel', compact('user','basics'));
            }
            else
                return view('Portal.public.index');
        }
        catch(ModelNotFoundException $e)
        {
            return redirect()->action('TeacherAssistantController@index');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $this->validate($request, [
            'email' => 'unique:users,email,'. $id.',userable_id',
            'national_id' => 'unique:users,national_id,'. $id.',userable_id',
        ],
        [
            'working_hours.required'  => 'Number of working hours is required',
            'email.unique'  => 'This email already exists',
            'national_id.unique'  => 'The national ID already exists',
        ]);

        $t_a = User::where('userable_id',$id)->get()->first();
        $t_a->address = $request->address;
        $t_a->phone = $request->phone;
        $t_a->name_ar = $request->name_ar;
        $t_a->name_en = $request->name_en;
        $t_a->email = $request->email;
        $t_a->gender = $request->gender;
        $t_a->DOB = $request->DOB;
        if($t_a->save())
        {
            $object = $t_a->userable;
            $object->save();
            return redirect()->action('TeacherAssistantController@index')->with('message', 'Teacher Assistant updated successfully');
        }
        else
        {  App::abort(500, 'Error'); }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try
        {
            $Auth_user = Auth::user();
            if($Auth_user->userable_type == 'Adm') {
                $t_a = Teacher_assistant::findOrFail($id);
                $t_a->delete();
                User::where('userable_id',$id)->delete();
                return response()->json([
                    'success' => 'Instructor has been deleted successfully!'
                ]);
            }
            else
            {  return view('Portal.public.index'); }
        }
        catch(ModelNotFoundException $e)
        {
            return view('Portal.public.index');
        }

    }
    /*View Manage Courses Page*/
    public function ManageCourseIndex (Request $request)
    {
        if(Auth::user()->userable_type != 'T_A')
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
            return view('Portal.Instructor_panel.Panel', compact('allSemester', 'AvilableSemester'));
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
            $AllMaterial = Material::where([ ['semester_id', $request->SemesterName], ['subject_id',$request->courseID]])->get();
            return view('Portal.Instructor_panel.Panel', compact('allSemester', 'AvilableSemester','student','grade_dist','courseID','AllMaterial'));
        }
    }
    /*Select all (open courses) options related to semester option */
    public function ManageCourse_showCourses ($semester_id , Request $request)
    {
        if(Auth::user()->userable_type != 'T_A')
        {return view('Portal.public.index');}

        $All_oprn_Courses = Timetable::distinct()->select('subject_id')->where([ ['semester_id',$semester_id],['timetableable_id', Auth::user()->userable_id] ])->get();
        foreach($All_oprn_Courses as $course)
        {
            echo '<option class="" id="CourseID" value="'.$course->subject->subject->id.'">'.$course->subject->subject->name.'</option>';
        }
    }

    public function updateGrades (Request $request)
    {
        if(Auth::user()->userable_type != 'T_A')
        {return view('Portal.public.index');}

        /*get grades distributions*/
        $grade_dist = Grade_distribution::where([ ['semester_id',$request->Sem_id] , ['subject_id',$request->courseID] ])->first();

        /*Transform all empty values into null*/
        if (empty($request->section))
        {
            $request->section=0;
        }

        $errors='';
        /*validate all request*/
        if(!($request->section >= 0 && $request->section <= $grade_dist->section))
        { $errors.='section must be between 0 and '.$grade_dist->section.'<br>'; }

        /*if there is no error , then update grades*/
        if($errors=='')
        {
            /*check if student have record or not*/
            $checkExists = Grade::where([ ['student_id',$request->stu_id ], ['semester_id',$request->Sem_id] , ['subject_id',$request->courseID] ])->first();
            if(count($checkExists)>0)
            {
                $updateDetails = array
                (
                    'instructor_id'=>Auth::user()->userable_id ,
                    'section' => $request->section,
                    'total_grade' => $request->section+$checkExists->total_grade
                );
                DB::table('grades')->where([ ['student_id',$request->stu_id ], ['semester_id',$request->Sem_id] , ['subject_id',$request->courseID] ])
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
                    'instructor_id'=>Auth::user()->userable_id ,
                    'doctor_id' =>  null ,
                    'section' => $request->section,
                    'total_grade' => $request->section
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

        if(Auth::user()->userable_type != 'T_A') { return view('Portal.public.index'); }

        if($request->Schedule == null)
        {return view('Portal.Instructor_panel.Panel');}
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
                return view('Portal.Instructor_panel.Panel',compact('allExamsSchedule','subjects','weekDays','midtermTime','halls','ta','show','midtermTime'));

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
                return view('Portal.Instructor_panel.Panel',compact('canEdit','show','allExamsSchedule','subjects','weekDays','practicalTIme','labs','ta'));
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
                return view('Portal.Instructor_panel.Panel',compact('canEdit','Final_Start_Time','show','Final_end_Time','allExamsSchedule','subjects','halls','ta','FinalExam_Times'));
            }
        }

    }


}
