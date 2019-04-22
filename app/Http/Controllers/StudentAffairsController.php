<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Doctor;
use App\Exam_timetable;
use App\Http\Requests\UserRequest;
use App\Open_course;
use App\Place;
use App\Semester;
use App\Student_affair;
use App\Teacher_assistant;
use App\Timetable;
use App\User;
use DB;
use Carbon\Carbon;
//use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Validator;
class StudentAffairsController extends Controller
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
            $list = Auth::user()->where('userable_type','=','S_A')->paginate(15);
            return view('Portal.admin_panel.Panel',compact('list'));
        }
        else
        {  return view('Portal.public.index'); }
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
            { return view('Portal.public.index'); }
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
            'hire_date'=>'required|date|after_or_equal:today|before:'.$limit,
            'degree'=>'required|max:50|regex:"(?=.*[A-Za-z])[A-Za-z0-9\'\.\-\s\,]{3,}"'
        ],
            [
                'password.required'  => 'Password is required',
                'hire_date.required'  => 'Hire date is required',
                'degree.required'  => 'Degree is required',

                'password.max'  => 'Password must be between 8 and 30 characters',
                'password.min'  => 'Password must be between 8 and 30 characters',
                'email.unique'  => 'This email already exists',
                'national_id.unique'  => 'The national ID already exists',
                'hire_date.date'  => 'Please enter valid date of hiring',
                'hire_date.after_or_equal'  => 'Please enter valid date of hiring - after today',
                'hire_date.before'  => 'Please enter valid date of hiring - before a week from now',
                'degree.regex'  => 'Please enter valid degree between 3 and 50 characters (contains letters)',
                'degree.max'  => 'The degree can not be more than 50 characters',
            ]);


        /*get Last id of user*/
        $lastID = DB::table('users')->orderBy('userable_id', 'DESC')->first();
        $lastID= $lastID->userable_id+1;

        $user = new Student_affair();
        $user->degree = $request->degree;
        $user->hire_date = $request->hire_date;
        $user->id=$lastID;

        if($user->save()) {
            $data = Response::json($user->id);
            User::Create(
                [
                    'userable_id' => $data->original ,'userable_type'=>'S_A',
                    'name_ar' => $request->name_ar ,'name_en' => $request->name_en ,
                    'email' => $request->email , 'password' => bcrypt($request->password) ,
                    'national_id' => $request->national_id , 'address' => $request->address ,
                    'phone' => $request->phone ,
                    'DOB' => $request->DOB , 'gender' => $request->gender
                ]);
            return redirect()->action('StudentAffairsController@index')->with('message', 'Student affair added successfully');
        }
        else
        {  App::abort(500, 'Error'); }
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
                $affairs = User::where('name_en', 'LIKE', '%' . $request->search . "%")->where('userable_type', '=', 'S_A')->get();
            else
                $affairs = User::where('userable_type', '=', 'S_A')->get();
            if ($affairs) {
                foreach ($affairs as $key => $sa) {
                    $output .= '<meta name="csrf-token" content="' . csrf_token() . '"> '.
                        '<tr>' .
                        '<td>' . $sa->userable_id . '</td>' .
                        '<td>' . $sa->name_en . '</td>' .
                        '<td>' . $sa->email . '</td>' .
                        '<td>' . $sa->phone . '</td>' .
                        '<td>' . $sa->userable->degree . '</td>' .
                        '<td>
                                <form method="GET" action="/Panel/SA/' . $sa->userable_id . '/edit">
                                    <button type="submit" class="btn btn-info EditButton"> Edit
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </form>
                            </td>
                            </tr>';
                }
                if (!$output)
                {
                    $output = "<tr>
                                <td colspan=\"7\">Not Found</td>
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
                $user = Student_affair::findOrFail($id);
                $basics=$user->user;
                return view('Portal.admin_panel.Panel', compact('user','basics'));
            }
            else
                return view('Portal.public.index');
        }
        catch(ModelNotFoundException $e)
        {
            return redirect()->action('StudentAffairsController@index');
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
            'degree'=>'required|max:50|regex:"(?=.*[A-Za-z])[A-Za-z0-9\'\.\-\s\,]{3,}"'
        ],
        [
            'working_hours.required'  => 'Number of working hours is required',
            'email.unique'  => 'This email already exists',
            'national_id.unique'  => 'The national ID already exists',
            'degree.regex'  => 'Please enter valid degree between 3 and 50 characters (contains letters)',
            'degree.max'  => 'The degree can not be more than 50 characters',
        ]);

        $s_a = User::where('userable_id',$id)->get()->first();
        $s_a->address = $request->address;
        $s_a->phone = $request->phone;
        $s_a->name_ar = $request->name_ar;
        $s_a->name_en = $request->name_en;
        $s_a->email = $request->email;
        $s_a->gender = $request->gender;
        $s_a->DOB = $request->DOB;
        $s_a->userable->degree = $request->degree;
        if($s_a->save())
        {
            $object = $s_a->userable;
            $object->save();
            return redirect('/Panel/SA')->with('message', 'Student affair updated successfully');
        }
        else
        { App::abort(500, 'Error');}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

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
                $s_a = Student_affair::findOrFail($id);
                $s_a->delete();
                User::where('userable_id',$id)->delete();
                return response()->json([
                    'success' => 'Student Affair has been deleted successfully!'
                ]);
            }
            else
            { return view('Portal.public.index'); }
        }
        catch(ModelNotFoundException $e)
        {
            return view('Portal.public.index');
        }
    }

    public function MidtermSchedule()
    {
        if(Auth::user()->userable_type != 'S_A') { return view('Portal.public.index'); }
        $now = Carbon::now();
        $lastSemester = Semester::orderBy('id','desc')->where('isSummer','0')->first();
        /*check if can access this page at current TIme or Not*/
        if($now<$lastSemester->end_register_date)
        {return redirect()->back()->with('Alert','you can Access Midterm Schedule only after students Complete their Registration ('.$lastSemester->end_register_date.') ');}

        /*check if Modification Date has passed (i can edit Schedule)*/

        $canEdit=0;
        $midtermTime = $lastSemester->midterm_week;
        if($now > $lastSemester->end_register_date && $now < Carbon::parse($midtermTime)->subWeek(1)->format('Y-m-d'))
        {$canEdit=1;}
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
        return view('Portal.studentAffair_panel.Panel',compact('canEdit','allExamsSchedule','subjects','weekDays','midtermTime','halls','ta'));
    }

    public function AddMidtermSchedule(Request $request)
    {
        if(Auth::user()->userable_type != 'S_A') { return view('Portal.public.index'); }

        /*first of all check if this course aiready exists in exam_timetable or not*/
                /*1- (sub task) get last semester id*/
                 $lastSemester = Semester::orderBy('id','desc')->where('isSummer','0')->first();
                 $check_exists = DB::table('exam_timetables')->where('type','0')->where('semester_id',$lastSemester->id)->where('subject_id',$request->course_id)->get();
                if(count($check_exists)!=0)
                /*if course Exists Already return to previous page with error  */
                {return redirect()->back()->withErrors(['Error'=>'this course Already exists in MidtermSchedule']);}

        /*second if place and time for that exam , has been blocked by another exam or not*/
                $exam_block = Exam_timetable::where([['semester_id',$lastSemester->id],['type','0'],['day',$request->day] ,['time',$request->Time]])->get();
                if(count($exam_block)!=0)
                /* current date time is being busy already */
                {return redirect()->back()->withErrors(['Error'=>'Current DateTime has been taken by another Exam , pls change Schedule']);}

        /*third check if teacher assistant is avialble or not */
                $ta_avilable = Exam_timetable::where([['semester_id',$lastSemester->id],['type','0'],['ta_id',$request->teacher_assistant],['day',$request->day] ,['time',$request->Time]])->get();
                if(count($ta_avilable)!=0)
                /*if course Exists Already return to previous page with error  */
                {return redirect()->back()->withErrors(['Error'=>'teacher assistant not Available at this TIme (day & time)']);}

        /*fourth check if this place is empty at this time*/
                foreach ($request->Hall as $place)
                {
                    $place_available = Exam_timetable::where([['semester_id',$lastSemester->id],['type','0'],['place_id',$place],['day',$request->day] ,['time',$request->Time]])->get();
                    if(count($place_available)!=0)
                        /*if course Exists Already return to previous page with error  */
                    {return redirect()->back()->withErrors(['Error'=>'Hall is not Available at this Time , another Exam Already their ']);}
                }

        /*Regular validation*/
        $checkExists = [];
        foreach ($request->Hall as $key => $value) {
              if(in_array($value,$checkExists))
              {return redirect()->back()->withErrors(['Error'=>'place can\'t be duplicated for the same Exam ']);}
              else {$checkExists[]=$value;}
        }
        /*second make validation on Time , day and course_id */
        $this->validate($request, [
                'Time'=>Rule::in(['9','10','11','12','13','14','15','16']),
                 'day'=>Rule::in(['Sunday','Monday','Tuesday','Wednesday','Thursday']),
                 'course_id' => 'exists:subjects,id',
                 'teacher_assistant' => 'exists:teacher_assistants,id',
            ]);
        /*create date*/
        $date='';
        if($request->day=='Sunday'){$date=$request->startDate;}
        elseif($request->day=='Monday'){$date = Carbon::parse($request->startDate)->addDay(1)->format('Y-m-d');}
        elseif($request->day=='Tuesday'){$date = Carbon::parse($request->startDate)->addDay(2)->format('Y-m-d');}
        elseif($request->day=='Wednesday'){$date = Carbon::parse($request->startDate)->addDay(3)->format('Y-m-d');}
        elseif($request->day=='Thursday'){$date = Carbon::parse($request->startDate)->addDay(4)->format('Y-m-d');}
        /*finally if all variables is correctly then store it into DB*/
        foreach ($request->Hall as $place)
        {
            $midterm = new  Exam_timetable();
            $midterm->semester_id= $lastSemester->id;
            $midterm->subject_id= $request->course_id;
            $midterm->place_id= $place;
            $midterm->sa_id= Auth::user()->userable_id;
            $midterm->ta_id= $request->teacher_assistant;
            $midterm->duration= 1;
            $midterm->day= $request->day;
            $midterm->time= $request->Time;
            $midterm->type= 0;
            $midterm->date=$date;
            $midterm->save();
        }
        return redirect()->back()->with('message','Midterm Exam has been placed Successfully in the schedule');
    }

    public function removeMidtermSchedule(Request $request)
    {
        if(Auth::user()->userable_type != 'S_A') { return view('Portal.public.index'); }

        $check = DB::table('exam_timetables')->where('semester_id',$request->Semester_id)->where('subject_id',$request->subject_id)->where('type','0')->delete();
        if($check)
        { return redirect()->back()->with('message',' course Exam deleted successfully ');}
        else
        {
            return redirect()->back()->withErrors(['Error'=>' failed to delete Exam Course ']);
        }
    }

    public function PracticalSchedule ()
    {
        if(Auth::user()->userable_type != 'S_A') { return view('Portal.public.index'); }

        $now = Carbon::now();
        $lastSemester = Semester::orderBy('id','desc')->where('isSummer','0')->first();
        $midtermTime = $lastSemester->midterm_week;
        $practicalTIme = Carbon::parse($midtermTime)->addWeek(2)->format('Y-m-d');

        /*يقدر يعدل على الصفحة دى من اول اسبوع الميدترم لغاية اخر الاسبوع يعنى من الاسبوع السابع للتامن */

        /*check if can access this page at current TIme or Not*/
        if($now<$midtermTime)
        {return redirect()->back()->with('Alert','you can Access Practical Schedule only after  ('.$midtermTime.') ');}

        /*check if Modification Date has passed (i can edit Schedule)*/
        $canEdit=0;
        if($now > $midtermTime && $now < Carbon::parse($midtermTime)->addWeek(1)->format('Y-m-d'))
        {$canEdit=1;}

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
        return view('Portal.studentAffair_panel.Panel',compact('canEdit','allExamsSchedule','subjects','weekDays','practicalTIme','labs','ta'));
    }

    public function AddPracticalSchedule (Request $request)
    {
        if(Auth::user()->userable_type != 'S_A') { return view('Portal.public.index'); }
        /*first of all check if this course aiready exists in exam_timetable or not*/
        /*1- (sub task) get last semester id*/
                $lastSemester = Semester::orderBy('id','desc')->where('isSummer','0')->first();
                $check_exists = DB::table('exam_timetables')->where('type','2')->where('semester_id',$lastSemester->id)->where('subject_id',$request->course_id)->get();
                if(count($check_exists)!=0)
                    /*if course Exists Already return to previous page with error  */
                {return redirect()->back()->withErrors(['Error'=>'this course Already exists in PracticalSchedule']);}

        /*second if place and time for that exam , has been blocked by another exam or not*/
                $exam_block = Exam_timetable::where([['semester_id',$lastSemester->id],['type','2'],['day',$request->day] ,['time',$request->Time]])->get();
                if(count($exam_block)!=0)
                    /*current date time is being busy already */
                {return redirect()->back()->withErrors(['Error'=>'Current DateTime has been taken by another Exam , pls change Schedule']);}

        /*third check if teacher assistant is avialble or not */
        $ta_avilable = Exam_timetable::where([['semester_id',$lastSemester->id],['type','2'],['ta_id',$request->teacher_assistant],['day',$request->day] ,['time',$request->Time]])->get();
                if(count($ta_avilable)!=0)
                    /*if course Exists Already return to previous page with error  */
                {return redirect()->back()->withErrors(['Error'=>'teacher assistant not Available at this TIme (day & time)']);}

        /*fourth check if this place is empty at this time*/
                foreach ($request->lab as $place)
                {
                    $place_available = Exam_timetable::where([['semester_id',$lastSemester->id],['type','2'],['place_id',$place],['day',$request->day] ,['time',$request->Time]])->get();
                    if(count($place_available)!=0)
                        /*if course Exists Already return to previous page with error  */
                    {return redirect()->back()->withErrors(['Error'=>'Hall is not Available at this Time , another Exam Already their ']);}
                }
        /*Regular validation*/
        $checkExists = [];
        foreach ($request->lab as $key => $value) {
            if(in_array($value,$checkExists))
            {return redirect()->back()->withErrors(['Error'=>'place can\'t be duplicated for the same Practical Exam ']);}
            else {$checkExists[]=$value;}
        }
        /*second make validation on Time , day and course_id */
        $this->validate($request, [
            'Time'=>Rule::in(['9','10','11','12','13','14','15','16']),
            'day'=>Rule::in(['Sunday','Monday','Tuesday','Wednesday','Thursday']),
            'course_id' => 'exists:subjects,id',
            'teacher_assistant' => 'exists:teacher_assistants,id',
        ]);
        $date='';
        if($request->day=='Sunday'){$date=$request->startDate;}
        elseif($request->day=='Monday'){$date = Carbon::parse($request->startDate)->addDay(1)->format('Y-m-d');}
        elseif($request->day=='Tuesday'){$date = Carbon::parse($request->startDate)->addDay(2)->format('Y-m-d');}
        elseif($request->day=='Wednesday'){$date = Carbon::parse($request->startDate)->addDay(3)->format('Y-m-d');}
        elseif($request->day=='Thursday'){$date = Carbon::parse($request->startDate)->addDay(4)->format('Y-m-d');}

        /*finally if all variables is correctly then store it into DB*/
        foreach ($request->lab as $place)
        {
            $midterm = new  Exam_timetable();
            $midterm->semester_id= $lastSemester->id;
            $midterm->subject_id= $request->course_id;
            $midterm->place_id= $place;
            $midterm->sa_id= Auth::user()->userable_id;
            $midterm->ta_id= $request->teacher_assistant;
            $midterm->duration= 1;
            $midterm->day= $request->day;
            $midterm->time= $request->Time;
            $midterm->date=$date;
            $midterm->type= 2;
            $midterm->save();
        }
        return redirect()->back()->with('message','Practical Exam has been placed Successfully in the schedule');

    }

    public function removePracticalSchedule (Request $request)
    {
        if(Auth::user()->userable_type != 'S_A') { return view('Portal.public.index'); }
        $check = DB::table('exam_timetables')->where('semester_id',$request->Semester_id)->where('subject_id',$request->subject_id)->where('type','2')->delete();
        if($check)
        { return redirect()->back()->with('message',' Practical Exam deleted successfully ');}
        else
        {
            return redirect()->back()->withErrors(['Error'=>' failed to delete Practical Exam Course ']);
        }
    }

    public function FinalSchedule()
    {
        if(Auth::user()->userable_type != 'S_A') { return view('Portal.public.index'); }
        $now = Carbon::now();
        $lastSemester = Semester::orderBy('id','desc')->where('isSummer','0')->first();
        $midtermTime = $lastSemester->midterm_week;
        $Final_Start_Time = Carbon::parse($midtermTime)->addWeek(3)->format('Y-m-d');
        $Final_end_Time = $lastSemester->end_date;

        /*يقدر يدخل على الصفحة دى من بعد الميدترم ما يبدأ*/
        /*check if can access this page at current TIme or Not*/
        if($now<$midtermTime)
        {return redirect()->back()->with('Alert','you can Access Final Schedule only after  ('.$midtermTime.') ');}
        /*يقدر يعدل على الصفحة دى من اول اسبوع الميدترم لغاية اخر الاسبوع يعنى من الاسبوع السابع للتامن */
        /*check if Modification Date has passed (i can edit Schedule)*/
        $canEdit=0;
        if($now > $midtermTime && $now < Carbon::parse($midtermTime)->addWeek(1)->format('Y-m-d'))
        {$canEdit=1;}

        /*Now Select all exam_Sechdule where type = 1*/
        $allExamsSchedule = Exam_timetable::orderBy('time','asc')->where([ ['type','1'] , ['semester_id',$lastSemester->id]])->get();
        /*Select all open courses */
        $subjects= Open_course::where('semester_id',$lastSemester->id)->get();
        /*Select ALl Halls*/
        $halls = Place::where('type','0')->get();
        /*Select all teacher Assistant*/
        $ta = Teacher_assistant::all();
        $FinalExam_Times= $allExamsSchedule->groupBy('date');
        return view('Portal.studentAffair_panel.Panel',compact('canEdit','Final_Start_Time','Final_end_Time','allExamsSchedule','subjects','halls','ta','FinalExam_Times'));
    }

    public function AddFinalSchedule (Request $request)
    {
        if(Auth::user()->userable_type != 'S_A') { return view('Portal.public.index'); }
        /*first of all check if this course aiready exists in exam_timetable or not*/
        /*1- (sub task) get last semester id*/
        $lastSemester = Semester::orderBy('id','desc')->where('isSummer','0')->first();
        $check_exists = DB::table('exam_timetables')->where('type','1')->where('semester_id',$lastSemester->id)->where('subject_id',$request->course_id)->get();
        if(count($check_exists)!=0)
            /*if course Exists Already return to previous page with error  */
        {return redirect()->back()->withErrors(['Error'=>'this course Already exists in FinalSchedule']);}

        /*second if  time for that exam , has been blocked by another exam or not*/
        $exam_block = Exam_timetable::where([['semester_id',$lastSemester->id],['type','1'],['date',$request->date] ,['time',$request->Time]])->get();
        if(count($exam_block)!=0)
            /* current date time is being busy already */
        {return redirect()->back()->withErrors(['Error'=>'Current DateTime has been taken by another Exam , pls change Schedule']);}

        /*third if exam was put in friday or saturday*/
        $checkDate = Carbon::parse($request->date);
        $checkDate = $checkDate->dayOfWeek;
        if($checkDate==5 ) {return redirect()->back()->withErrors(['Error'=>'Exam can\'t be placed  in Friday ']);}
        elseif($checkDate==6){return redirect()->back()->withErrors(['Error'=>'Exam can\'t be placed in Saturday']);}

        /*third check if teacher assistant is avialble or not */
        $ta_avilable = Exam_timetable::where([['semester_id',$lastSemester->id],['type','1'],['ta_id',$request->teacher_assistant],['date',$request->date] ,['time',$request->Time]])->get();
        if(count($ta_avilable)!=0)
            /*if course Exists Already return to previous page with error  */
        {return redirect()->back()->withErrors(['Error'=>'teacher assistant not Available at this TIme (day & time)']);}

        /*fourth check if this place is empty at this time*/
        foreach ($request->Hall as $place)
        {
            $place_available = Exam_timetable::where([['semester_id',$lastSemester->id],['type','1'],['place_id',$place],['date',$request->date] ,['time',$request->Time]])->get();
            if(count($place_available)!=0)
                /*if course Exists Already return to previous page with error  */
            {return redirect()->back()->withErrors(['Error'=>'Hall is not Available at this Time , another Exam Already their ']);}
        }

        /*Regular validation*/
        $checkExists = [];
        foreach ($request->Hall as $key => $value) {
            if(in_array($value,$checkExists))
            {return redirect()->back()->withErrors(['Error'=>'place can\'t be duplicated for the same Exam ']);}
            else {$checkExists[]=$value;}
        }

        /*second make validation on Time , day and course_id */
        $this->validate($request, [
            'Time'=>Rule::in(['9','12','15']),
            'course_id' => 'required|exists:subjects,id',
            'teacher_assistant' => 'required|exists:teacher_assistants,id',
            'date'=>'required|after_or_equal:'.$request->Start_date.'|before_or_equal:'.$request->end_date.''
        ]);
        /*finally if all variables is correctly then store it into DB*/
        foreach ($request->Hall as $place)
        {
            $midterm = new  Exam_timetable();
            $midterm->semester_id= $lastSemester->id;
            $midterm->subject_id= $request->course_id;
            $midterm->place_id= $place;
            $midterm->sa_id= Auth::user()->userable_id;
            $midterm->ta_id= $request->teacher_assistant;
            $midterm->duration= $request->subject_hours;
            $midterm->day= '';
            $midterm->time= $request->Time;
            $midterm->type= 1;
            $midterm->date=$request->date;
            $midterm->save();
        }
        return redirect()->back()->with('message','FInal Exam has been placed Successfully in the schedule');
    }

    public function removeFinalSchedule(Request $request)
    {
        if(Auth::user()->userable_type != 'S_A') { return view('Portal.public.index'); }
        $check = DB::table('exam_timetables')->where('semester_id',$request->Semester_id)->where('subject_id',$request->subject_id)->where('type','1')->delete();
        if($check)
        { return redirect()->back()->with('message',' Final Exam deleted successfully ');}
        else
        {
            return redirect()->back()->withErrors(['Error'=>' failed to delete Final Exam Course ']);
        }
    }
}
