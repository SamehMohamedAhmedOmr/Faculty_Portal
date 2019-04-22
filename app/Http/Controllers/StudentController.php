<?php

namespace App\Http\Controllers;

use App\Department;
use App\Open_course;
use App\Semester;
use App\Student;
use App\Http\Requests\UserRequest;
use App\Timetable;
use Carbon\Carbon;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use App\Mail;
use Illuminate\Support\Facades\Session;
use Validator;
use App\Grade;
use Illuminate\Validation\Rule;
use App\Exam_timetable;
use App\Place;
use App\Teacher_assistant;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    //
    public function index()
    {
        $user = Auth::user();
        if ($user->userable_type == 'S_A') {
            $students = Auth::user()->where('userable_type', '=', 'Stu')->paginate(15);
            return view('Portal.studentAffair_panel.Panel', compact('students'));
        }
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->userable_type == 'S_A') {
            $departments = Department::all(['name', 'id']);
            return view('Portal.studentAffair_panel.Panel', compact('departments'));
        } else {
            return view('Portal.public.index');
        }
    }

    public function store(UserRequest $request)
    {
        $this->validate($request, [
            'email' => 'unique:users',
            'password' => 'required|max:30|min:8',
            'national_id' => 'unique:users,national_id',
//            'department_id' => 'required|numeric',
//            'account_status' => 'required|boolean',
        ],
            [
                'password.required' => 'Password is required',
//                'department_id.required' => 'Department ID is required',
//                'account_status.required' => 'Please select account status',
                'password.max' => 'Password must be between 8 and 30 characters',
                'password.min' => 'Password must be between 8 and 30 characters',
                'email.unique' => 'This email already exists',
                'national_id.unique' => 'The national ID already exists',
//                'department_id.numeric' => 'Department ID should be a number',
//                'account_status.boolean' => 'Account status either active or deactivated',
            ]);

        /*get Last id of user*/
        $lastID = DB::table('users')->orderBy('userable_id', 'DESC')->first();
        $lastID = $lastID->userable_id + 1;

        $stu = new Student();
        $stu->id = $lastID;
        $stu->department_id = 1; // General by default
        $stu->account_status = 1; // Active by default
        $stu->graduated_status = 1; // Level 1 by default
        $stu->sa_id = Auth::user()->userable_id;

        if ($stu->save()) {
            $data = Response::json($stu->id);
            User::Create(['userable_id' => $data->original, 'userable_type' => 'Stu',
                'name_ar' => $request->name_ar, 'name_en' => $request->name_en,
                'email' => $request->email, 'password' => bcrypt($request->password),
                'national_id' => $request->national_id, 'address' => $request->address,
                'phone' => $request->phone,
                'DOB' => $request->DOB, 'gender' => $request->gender]);

            return redirect()->action('StudentController@index')->with('message', 'Student added successfully');
        } else
            App::abort(500, 'Error');
    }

    public function show(Request $request)
    {
        if ($request->ajax()) {
            $output = "";
            if ($request->search !== 0)
                $students = User::where('userable_id', 'LIKE', '%' . $request->search . "%")->where('userable_type', '=', 'Stu')->get();
            else
                $students = User::where('userable_type', '=', 'Stu')->get();
            if ($students) {
                foreach ($students as $key => $stu) {
                    if($stu->userable->account_status == 0)
                        $output .= '<tr style="background-color: #ffaaaac7;">';
                    else
                        $output .= '<tr style="background-color: #aaffadc7;">';

                    $output .=
                        '<td>' . $stu->userable_id . '</td>' .
                        '<td>' . $stu->name_en . '</td>' .
                        '<td>' . $stu->phone . '</td>' .
                        '<td>' . $stu->userable->department->name . '</td>' .
                        '<td>' . (($stu->userable->graduated_status == 0) ? 'Graduated' : $stu->userable->graduated_status) . '</td>' .
                        '<td>
                                <form method="GET" action="/Panel/Student/' . $stu->userable_id . '/edit">
                                    <button type="submit" class="btn btn-info EditButton"> Edit
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </form>
                            </td>
                                 <meta name="csrf-token" content=" csrf_token()">
                            </tr>';
                }
                if (!$output || $request->search=='invalid_search_key') {
                    $output = "<tr>
                                    <td colspan=\"6\">Not Found</td>
                                  </tr>";
                }
                return Response($output);
            }
        }
    }

    public function edit($id)
    {

        try {
            $Auth_user = Auth::user();
            if ($Auth_user->userable_type == 'S_A') {
                $stu = Student::findOrFail($id);
                $basics = $stu->user;
                $departments = Department::all(['name', 'id']);
                return view('Portal.studentAffair_panel.Panel', compact('stu', 'basics', 'departments'));
            } else {
                return view('Portal.public.index');
            }
        } catch (ModelNotFoundException $e) {
            return view('Portal.public.index');
        }
    }

    public function update(UserRequest $request, $id)
    {
        $request->validated(
            [
                'email' => 'unique:users,email,' . $id . ',userable_id',
                'national_id' => 'unique:users,national_id,' . $id . ',userable_id',
                'department_id' => 'required|numeric',
                'account_status' => 'required|boolean',
            ]);
        $this->validate($request, [
            'email' => 'unique:users,email,' . $id . ',userable_id',
            'national_id' => 'unique:users,national_id,' . $id . ',userable_id',
            'department_id' => 'required|numeric',
            'graduated_status' => 'required|numeric',
            'account_status' => 'required|boolean',
        ],
            [
                'department_id.required' => 'Department ID is required',
                'account_status.required' => 'Please select account status',
                'graduated_status.required' => 'Please select level',
                'email.unique' => 'This email already exists',
                'national_id.unique' => 'The national ID already exists',
                'department_id.numeric' => 'Department ID should be a number',
                'graduated_status.numeric' => 'Please select level from available options',
                'account_status.boolean' => 'Account status either active or deactivated',
            ]);

        $stu = User::where('userable_id', $id)->get()->first();
        $stu->address = $request->address;
        $stu->phone = $request->phone;
        $stu->gender = $request->gender;
        $stu->email = $request->email;
        $stu->save();

        $stu = $stu->userable;
        $stu->department_id = $request->department_id;
        $stu->graduated_status = $request->graduated_status;
        $stu->account_status = $request->account_status;
        if ($stu->save()) {
            if (Auth::user()->userable_type == 'S_A') {
                return redirect()->back()->with('message', 'Student updated successfully');
            }
        } else {
            App::abort(500, 'Error');
        }
    }

    public function destroy($id)
    {
        try {
            $Auth_user = Auth::user();
            if ($Auth_user->userable_type == 'S_A') {
                $stu = Student::findOrFail($id);
                $stu->delete();
                User::where('userable_id', $id)->delete();
                return response()->json([
                    'success' => 'student has been deleted successfully!'
                ]);
            } else {
                return view('Portal.public.index');
            }
        } catch (ModelNotFoundException $e) {
            return view('Portal.public.index');
        }
    }

    public function manageRegistration()
    {
        $user = Auth::user();
        if ($user->userable_type == 'S_A') {
//            $students = Auth::user()->where('userable_type','=','Stu')->paginate(25);
            $departments = Department::all(['name', 'id']);
            return view('Portal.studentAffair_panel.Panel', compact('departments'));
        }
    }

    public function showRegistration($keys, Request $request)
    {
        if (Auth::user()->userable_type != "S_A") {
            return redirect('/home');
        }
        if (!$request->ajax()) {
            return redirect('/Panel/registrations');
        } else {
            $id = explode('*', $keys);
            if (isset($_GET['reg_search'])) {
                $dep = $id[2];
                $level = $id[1];
                $key = $id[0];
            } else {
                $dep = $id[1];
                $level = $id[0];
            }
            $currentSemester = Semester::where('complete', 0)->where('start_date', '<=', today())->first(); // current semester
            if ($currentSemester) {
                $opening = 0; //on
                if (today() > $currentSemester->open_register_date) // registration opened
                {
                    if (today() > $currentSemester->end_register_date) // registration closed
                        $opening = 1; //closed
                    $students_fltr = Student::where([['department_id', $dep], ['graduated_status', $level]])->get();

                    if (isset($_GET['reg_search']) && $key != null)
                    {
                        $students = Student::where([['department_id', $dep], ['graduated_status', $level]])->where('id', 'LIKE', '%' . $key . "%")->paginate(25);
                        if (sizeof($students) == 0)
                        {
                            $students_users = User::where('name_en', 'LIKE', '%' . $key . "%")->pluck('userable_id')->toArray();
                            $students = Student::where([['department_id', $dep], ['graduated_status', $level]])->whereIn('id',$students_users)->paginate(25);
                        }
                    }
                    else
                        $students = Student::where([['department_id', $dep], ['graduated_status', $level]])->paginate(25);

                    $reg_stu = [];
                    $non_reg_stu = [];
                    foreach ($students_fltr as $stu)
                        $stu->timetables->where('timetableable_type', 'Doc')->where('semester_id',$currentSemester->id)->count() >= 3 ? $reg_stu[] = $stu->id : $non_reg_stu[] = $stu->id;
                    $output = "";
                    $search_output = "";
                    if (sizeof($students) > 0 || isset($_GET['reg_search'])) {
                        $output .= "

                        <div class=\"input-group\">
                           <input type=\"text\" class=\"form-control\" onkeyup=\"reg_student_search(this.value)\" id=\"reg_student_search\" name=\"reg_student_search\" placeholder=\"Search by ID or English Name\">
                        </div>";

                        $search_output .= "
                                <div class='ManageRegistrationTable'>
                                <div style=\"margin-bottom: 0; font-size: 14px;\">
                                    " . $students->total() . " Total Students <br>
                                    <b style=\"font-size: 12px; color: #104b69;\"> In this page : " . $students->count() . " </b>
                                </div>
                                
                                <button type=\"button\" class=\"btn btn-primary col-2 btn-sm selectBtn\" id=\"checkAll\" value=\"Check All\" onclick=\"reg_student_selectAll()\" ><i class=\"fa fa-check\"></i> Check All</button> 
                                <button type=\"button\" class=\"btn btn-primary col-2 btn-sm selectBtn\" id=\"uncheckAll\" value=\"Uncheck All\" onclick=\"reg_student_unselectAll()\" ><i class=\"fa fa-times\"></i> Uncheck All</button> 
                                <button type=\"button\" class=\"btn btn-primary col-3 btn-sm selectBtn\" id=\"nonReg_stu\" value=\"Non-registered Students\" onclick=\"reg_student_nonRegestered()\">Non-registered Students</button>
                                <button type=\"button\" class=\"btn btn-primary col-2 btn-sm selectBtn\" id=\"reg_stu\" value=\"Registered Students\" onclick=\"reg_student_regestered()\">Registered Students</button>                                        
                                <table class=\"table table-hover col-md-5 mngRegTbl \" style=\"margin-top: 3px; text-align: center;\">
                                    <thead>
                                    <tr class=\"mngRegTbl_th\">
                                        <th> Registration </th>
                                        <th> ID   </th>
                                        <th> Name </th>
                                        <th> Email </th>                   
                                    </tr>
                                    </thead>
                                    <tbody id=\"student_tbl\">";
//                        Table Body
                        if (sizeof($students) == 0 && isset($_GET['reg_search'])) {
                            $search_output .=
                                "<tr>
                                    <td colspan=\"4\">Not Found</td>
                                 </tr>";
                        } else { // Foreach on Student array
                            $counter = 0;
                            foreach ($students as $student) {
                                $counter++;
                                if (in_array($student->id, $non_reg_stu)) $search_output .=  //special style for non-registered students + Checkbox
                                    "<tr style='background-color: #c9c9c9;'>
                                        <td>
                                        <input type=\"checkbox\" class=\"form-control input cb-element non_reg_stu\" onclick='ctrlShowBtn()' value='$student->id' name=\"chkbox[]\">
                                        </td>";
                                else $search_output .= //special style for registered students + Checkbox
                                    "<tr style='background-color: #f2f2f2;'>
                                        <td>
                                        <input type=\"checkbox\" class=\"form-control input cb-element reg_stu\" onclick='ctrlShowBtn()' value='$student->id' name=\"chkbox[]\">
                                        </td>";
                                $search_output .= " 
                                                      <td> <a href='javascript:showStuReg(\"". $student->id ."\");'>" . $student->id . " </a></td>
                                                      <td> " . $student->user[0]->name_en . " </td>
                                                      <td> " . $student->user[0]->email . " </td>
                                             </tr>";
                            }
                        }
                        $search_output .= " 
                                     </tbody>
                                </table>";
                        $search_output .= "
                        <div class=\"alert alert-danger\" id='regMailErrors' style=\"display:none \">
                            <strong><i class=\"fa fa-exclamation-circle\"></i></strong>
                        </div>
                        <div class=\"alert alert-info\" id='regMailSuccess'  style=\"display:none \">
                            <strong><i class=\"	fa fa-check-square\"></i></strong> Your message sent successfully.
                        </div>
                       <button class=\"form-control col-3 btn-md\" id='regMailBtn' data-toggle=\"modal\" data-target=\"#regMailModal\" disabled='true'><i class=\"fa fa-envelope\"></i>  Send Mail</button>
                                </div>
                        </div>
                        <div class=\"pagination text-center\"> " . $students->links() . " </div>";

                        if (isset($_GET['reg_search'])) return $search_output;
                        return $output .= $search_output;
                    } else
                        return "
                        <div class=\"clearfix\"></div>
                        <span class=\" \"><i class=\"fa fa-exclamation-circle\"></i>There're no students in this department in level " . $level . "  !</span>
                        ";
                } else
                    return "
                    <div class=\"clearfix\"></div>
                    <span class=\" \"><i class=\"fa fa-lock\"></i>There's no registration information to show currently !</span>
                    ";
            }
            else
            {
                return "
                    <div class=\"clearfix\"></div>
                    <span class=\" \"><i class=\"fa fa-lock\"></i>There's no registration information to show currently !</span>
                    ";
            }
        }
    }

    public function registrationMail(Request $request)
    {
        if (Auth::user()->userable_type != "S_A") {
            return redirect('/home');
        }
        if (!$request->ajax()) {
            return redirect('/Panel/registrations');
        }
        $validator = Validator::make($request->all(),[
            'regMailSubj' => 'required|string|min:3|max:20|regex:"[A-Za-z0-9 ]{3,20}"',
            'regMailMsg' => 'required|string|min:10|max:200|regex:"[A-Za-z0-9 ]{3,100}"',
        ],
            [
                'regMailSubj.required' => 'You must enter a subject',
                'regMailMsg.required' => 'You must enter a message',
                'regMailSubj.min' => 'The subject must be at least 3 characters',
                'regMailSubj.max' => 'The subject must be at most 20 characters',
                'regMailMsg.min' => 'The message must be at least 3 characters',
                'regMailSubj.max' => 'The subject must be at most 100 characters',
                'regMailMsg.regex' => 'The message must contains numbers and characters',
                'regMailSubj.regex' => 'The subject must contains numbers and characters',
            ]
        );
        if ($validator->fails()) {
            return Response($validator->errors()->all());
        }
        else {
            $errors=[];
            if ($request->ids != 0) {
                if ($request->regMailSubj != "" && $request->regMailMsg != "") {
                    foreach ($request->ids as $id) {
                        $mail = new Mail();
                        $mail->header = $request->regMailSubj;
                        $mail->description = $request->regMailMsg;
                        $mail->sender_id = Auth::user()->userable_id;
                        $mail->date_time =Carbon::now();
                        $stu = Student::findOrFail($id);
                        if ($stu) {
                            $mail->receiver_id = $id;
                            $mail->save();
                        }
                        else
                        {
                            $errors[] = "Error: Message couldn't send!";
                            return Response($errors);
                        }
                    }
                }
                else{
                    $errors[] = "Message Data incomplete!";
                    return Response($errors);
                }
            }
            else{
                $errors[] = "No Ids selected!";
                return Response($errors);
            }
        }
        return Response("success");
    }

    public function studentRegistration (Request $request){
        if (Auth::user()->userable_type != "S_A") {
            return redirect('/home');
        }
        if (!$request->ajax()) {
            return redirect('/Panel/registrations');
        }
        if (isset($_GET['semester']))
        {
            $selectedSemester = Semester::where('id', $request->semester)->first();
            $currentSemester = Semester::where('complete', 0)->where('start_date', '<=', today())->first();
            $stu = Student::findOrFail($request->id);
            $courses = $stu->timetables->where('semester_id', $selectedSemester->id)->where('timetableable_type','Doc');
            $output ="";
            $output.="
                    <table class=\"table stuRegTbl\">
                        <thead>
                          <tr>
                            <th colspan=\"3\"> Semester : ". $selectedSemester->name ."  <i class=\"fa fa-sort-down\"></i></th>
                          </tr>
                        </thead>
                        <tbody>";
            if(sizeof($courses)>0)
            {
                foreach ($courses as $course)
                {
                    $output.="<tr>
                            <td class='stuRegCourse'>". $course->Subject->Subject->name ."<i class=\"fas fa-angle-double-right\"></i></td>
                            <td>Dr-". $course->timetableable->User[0]->name_en ."<br>";
                    $sec = $stu->timetables->where('semester_id', $selectedSemester->id)->where('timetableable_type','T_A')->where('subject_id',$course->Subject->subject_id)->first();
                    $output.="          Eng-". $sec->timetableable->User[0]->name_en ."</td>";
                          if($currentSemester->id == $selectedSemester->id){
                              $output.="<td>
                              <meta name=\"csrf-token\" content=\" csrf_token()\">
                              <a href='/Panel/registrations/drop/". $course->subject_id. "/". $stu->id ."'><button class='btn btn-danger' style='border-radius: 0px; height: 40px; width: 40px'>×</button></a>
                          </td>";
                          }
                          $output.="
                          </tr>";
                }

            }
            else
            {
                $output.="<tr style='text-align: center'><td colspan=\"3\"><i class=\"fa fa-exclamation-circle\"></i> No courses registered !</td></tr>";
            }
            $output.="    </tbody>
                    </table>
                    ";
            return $output;
        }
        else
        {
            $currentSemester = Semester::where('complete', 0)->where('start_date', '<=', today())->first(); // current semester
            $stu = Student::findOrFail($request->id);
            $semesters = Semester::orderBy('id','desc')->get();
            $courses = $stu->timetables->where('semester_id', $currentSemester->id)->where('timetableable_type','Doc');
            $output = "
            <div class=\"form-group row\">    
                <select onchange=\"changeSemester()\" class=\"form-control mngRegSemester\" id=\"\" name=\"\" value=\"Choose Semester\">";
            foreach ($semesters as $semester)
            {
                $output .= " <option class=\"mngRegOpt\" value=\"".$semester->id."\">".$semester->name ."</option> ";
            }
            $output .= "
                </select>
            </div>
        ";
            $output.="
                <div id='stuRegCourseTbl'>
                    <table class=\"table stuRegTbl\">
                        <thead>
                          <tr>
                            <th colspan=\"3\"> Semester : ". $currentSemester->name ."  <i class=\"fa fa-sort-down\"></i></th>
                          </tr>
                        </thead>
                        <tbody>";
            if(sizeof($courses)>0)
            {
                foreach ($courses as $course)
                {
                    $output.="    <tr>
                            <td class='stuRegCourse'>". $course->Subject->Subject->name ."<i class=\"fas fa-angle-double-right\"></i></td>
                            <td>Dr-". $course->timetableable->User[0]->name_en ."<br>";
                    $sec = $stu->timetables->where('semester_id', $currentSemester->id)->where('timetableable_type','T_A')->where('subject_id',$course->Subject->subject_id)->first();
                    $output.="Eng-". $sec->timetableable->User[0]->name_en ."</td>
                            <td><meta name=\"csrf-token\" content=\" csrf_token()\">
                            <a href='/Panel/registrations/drop/". $course->subject_id. "/". $stu->id ."'><button class='btn btn-danger' style='border-radius: 0px; height: 40px; width: 40px'>×</button></a></td>
                    </tr>";
                }
                $output.="    </tbody>
                    </table>
                 </div>";
            }
            else
            {
                $output.="<tr style='text-align: center'><td colspan=\"3\"><i class=\"fa fa-exclamation-circle\"></i> No courses registered !</td></tr>";
            }
            return $output;
        }

    }

//    NEW________________________________________________________
    public function studentAvailableCourses($stu){

        $dep = [$stu->department_id,1];
        $avlbl_subjects = DB::table('subjects')
            ->where('level_req','<=',$stu->graduated_status)
            ->whereIn('department_id',$dep)->get(); // courses in specific department.

        $passed = DB::table('grades')
            ->where([['student_id',$stu->id],['total_grade','>=',50]])
            ->select('subject_id')->pluck('subject_id')->toArray();

        $courses = [];
        foreach ($avlbl_subjects as $c)
        {
            if(!in_array($c->id, $passed)) // not passed
            {
                if($c->prerequisite!=null)  // course have prerequisite
                    if(in_array($c->prerequisite, $passed)) // pass in prerequisite
                        $courses[] = $c->id;
                    else
                        continue;
                else
                    $courses[] = $c->id;
            }
        }
        $avlbl_courses = DB::table('subjects')->whereIn('id',$courses)->select('subjects.id','subjects.name')->pluck('subjects.id','subjects.name');
        return ($avlbl_courses);

    }

    public function register (Request $request) {

        if (Auth::user()->userable_type != "S_A") {
            return redirect('/home');
        }
        $status = 0;
        try{
            $stu = Student::findOrFail($request->id)->first(); // Just for test
            $now = Carbon::now();
            $currentSemester = Semester::where('complete',0)->where('start_date', '<=', today())->get(); // Current Semester
            $status = 0;
            if(count($currentSemester)>0) { // Check if there's Current Semester
                if ($now >= $currentSemester[0]->open_register_date && $now <= $currentSemester[0]->midterm_week) { // Check registration period (before registration week)
                    //get courses that have been already registered by the student in this semester to be excepted.
                    $stu_id = $stu->id;
                    $semester_id = $currentSemester[0]->id;
                    $registered_courses = DB::table('timetables')->join('student_timetable',function ($join) use($stu_id,$semester_id)  // courses that student didn't pass.
                    {
                        $join->on('timetables.id','=','student_timetable.timetable_id')->where([['student_timetable.student_id',$stu_id],['student_timetable.semester_id',$semester_id]]);
                    })->select('timetables.subject_id')->pluck('timetables.subject_id');
                    if(sizeof($registered_courses)<14){ // Registration doesn't complete
                        // Courses available for the student to register currently:-
                        $avlbl_courses = Open_course::whereIn('subject_id',$this->studentAvailableCourses($stu))->whereNotIn('subject_id',$registered_courses)->get();
                        $avlbl_courses_ids = Open_course::whereIn('subject_id',$this->studentAvailableCourses($stu))->whereNotIn('subject_id',$registered_courses)->pluck('subject_id');
                        if(sizeof($avlbl_courses_ids)>0){
                            // Lectures and Sections available for the student to register in this semester:-
                            $status = 1;
                            Session::flash('hint','This Student has registered '.(sizeof($registered_courses)/2).' courses until now.');
                            $lectures = Timetable::where([['semester_id', $currentSemester[0]->id],['timetableable_type', 'Doc']])->whereIn('subject_id',$avlbl_courses_ids)->get()->sortByDesc('day')->sortBy('time');
                            $sections = Timetable::where([['semester_id', $currentSemester[0]->id],['timetableable_type', 'T_A']])->whereIn('subject_id',$avlbl_courses_ids)->get()->sortBy('time');
                            return view('Portal.studentAffair_panel.Panel', compact('stu','avlbl_courses', 'lectures', 'sections','status'));
                        } else
                            return view('Portal.studentAffair_panel.Panel',compact('status'))->withErrors("There is no available courses to register !");
                    } else
                        return view('Portal.studentAffair_panel.Panel',compact('status'))->withErrors("Student's registration is already complete !");
                } else
                    return view('Portal.studentAffair_panel.Panel',compact('status'))->withErrors('Registration is not availbale now !');
            } else
                return view('Portal.studentAffair_panel.Panel',compact('status'))->withErrors('Registration is not availbale yet !');
        } catch (ModelNotFoundException $e){
            return view('Portal.studentAffair_panel.Panel',compact('status'))->withErrors('Something went wrong !');
        }
    }
    public function registrationSubmit(Request $request){
        if (Auth::user()->userable_type != "S_A") {
            return redirect('/home');
        }
        try{
            $status = 1;
            $stu = Student::findOrFail($request->id)->first();

            $courses = Open_course::all('subject_id')->whereIn('subject_id',$this->studentAvailableCourses($stu));
            $registrations = []; // Array will contain the IDs of registered timetables
            // Get all selected lectures and sections.
            foreach ($courses as $c){
                // Check The Lectures
                $lec_field = "lec".$c->subject_id;
                $sec_field = "sec".$c->subject_id;
                //add selected lecture and section of specific course
                if($request->$lec_field!==null && $request->$sec_field!==null){
                    $registrations[] = $request->$lec_field;
                    $registrations[] = $request->$sec_field;
                }
                elseif($request->$lec_field===null && $request->$sec_field===null)
                    continue; // skip it
                else // Select course lecture without section or vise versa.
                    return back()->withErrors('You must choose both lecture and section times for each selected course! Try again.');
            }
            $stu_id = $stu->id;
            $currentSemester = Semester::where('complete',0)->where('start_date', '<=', today())->first(); // Current Semester
            $semester_id = $currentSemester->id;
            $registered_courses = DB::table('timetables')->join('student_timetable',function ($join) use($stu_id,$semester_id)  // courses that student didn't pass.
            {$join->on('timetables.id','=','student_timetable.timetable_id')->where([['student_timetable.student_id',$stu_id],['student_timetable.semester_id',$semester_id]]);
            })->select('timetables.subject_id')->pluck('timetables.subject_id');
            // Check the total registered courses
            if(sizeof($registrations)==0)
                return back()->withErrors('Please select courses !');
            if((sizeof($registrations)+sizeof($registered_courses))>14 || (sizeof($registrations)+sizeof($registered_courses))<6) // Check the number of selected courses
                return back()->withErrors('Student must register at least 3 courses and at most 6! Try again.');
            else
            { // Check the conflict
                $timetables = Timetable::whereIn('id',$registrations)->get();
                for ($reg=0 ; $reg<sizeof($registrations)-1 ; $reg++)
                {
                    for($comp=$reg+1 ; $comp<sizeof($registrations) ; $comp++)
                    {
                        if($timetables[$reg]->day == $timetables[$comp]->day && $timetables[$reg]->time == $timetables[$comp]->time)
                        {
                            $first="";
                            $second="";
                            ($timetables[$reg]->timetableable_type=='Doc')? $first=" lecture":$first=" section";
                            ($timetables[$comp]->timetableable_type=='Doc')? $second=" lecture":$second=" section";
                            return  back()->withErrors('There is a conflict in '. $timetables[$reg]->Subject->Subject->name.$first.' and ' . $timetables[$comp]->Subject->Subject->name.$second.' in '.$timetables[$reg]->day.' at '.$timetables[$reg]->time);
                        }
                    }
                }
                // Saving the registration
                foreach ($registrations as $tt){
                    DB::table('student_timetable')->insert(
                        ['student_id' => $request->id ,
                            'semester_id' =>$semester_id,
                            'timetable_id' => $tt]
                    );
                }
                return redirect('/Panel/registrations')->with('success','registration saved');
            }
        }
        catch (ModelNotFoundException $e){
            return view('Portal.studentAffair_panel.Panel',compact('status'))->withErrors('Something went wrong !');
        }
    }
    public function dropCourse($subjID,$stuID){
        if (Auth::user()->userable_type != "S_A") {
            return redirect('/home');
        }
        try{
            $currentSemester = Semester::where('complete', 0)->where('start_date', '<=', today())->first(); // current semester
            $semester_id = $currentSemester->id;

            $stu = Student::findOrFail($stuID);
            $total = DB::table('student_timetable')->where('student_id',$stu->id)->where('student_timetable.semester_id',$semester_id)->get();
            if((sizeof($total)/2)>3){
                DB::table('student_timetable')->where('student_id',$stu->id)
                    ->join('timetables',function ($join) use($subjID,$semester_id)// courses that student didn't pass.
                    {
                        $join->on('timetables.id','=','student_timetable.timetable_id')
                            ->where([['timetables.subject_id','=', $subjID],['timetables.semester_id',$semester_id]]);
                    })
                    ->where('student_timetable.semester_id',$semester_id)->delete();

                return redirect('/Panel/registrations')->with('success','course dropped successfully');
            }
            else{
                return redirect('/Panel/registrations')->withErrors('Drop is failed ! Student must have at least 3 registered courses.');
            }

        }
        catch (ModelNotFoundException $e){
            return view('Portal.studentAffair_panel.Panel',compact('status'))->withErrors('Something went wrong !');
        }

    }

    public function updateLevels(){
        $students = Student::where('graduated_status','!=',0)->get();
        foreach ($students as $student){
            $check = false;
            $newLevel = null;
            $grades = Grade::where('student_id',$student->id)->get();
            $successHour = $this->calculateSucceedHours($grades);
            if($successHour < 29){
                $newLevel = 1;
                $check = true;
            }
            if($successHour >= 29){
                $newLevel = 2;
                $check = true;
            }
            elseif ($successHour >= 57){
                $newLevel = 3;
                $check = true;
            }
            elseif ($successHour >=99){
                $newLevel = 4;
                $check = true;
            }
            elseif ($successHour == 129){
                $newLevel = 0;
                $check = true;
            }

            if($check === true){
                $student->graduated_status = $newLevel;
                $student->save();
            }
        }
        session()->flash('update_level','Levels Updated Successfully');
        return redirect()->back();
    }

    public function calculateSucceedHours($grades){
        $indexes = [];
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
        }

        $allSuccessesHours = null;
        foreach ($succeededHour as $success){
            $allSuccessesHours += $success;
        }

        return $allSuccessesHours;
    }

    public function showSchedule (Request $request)
    {
        if(Auth::user()->userable_type != 'Stu') { return view('Portal.public.index'); }

        if($request->Schedule == null)
        {return view('Portal.student_panel.Panel');}
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
                return view('Portal.student_panel.Panel',compact('allExamsSchedule','subjects','weekDays','midtermTime','halls','ta','show','midtermTime'));

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
                return view('Portal.student_panel.Panel',compact('canEdit','show','allExamsSchedule','subjects','weekDays','practicalTIme','labs','ta'));
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
                return view('Portal.student_panel.Panel',compact('canEdit','Final_Start_Time','show','Final_end_Time','allExamsSchedule','subjects','halls','ta','FinalExam_Times'));
            }
        }
    }

}
