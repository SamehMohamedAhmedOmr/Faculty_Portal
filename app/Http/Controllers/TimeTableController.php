<?php

namespace App\Http\Controllers;

use App\Doctor;
use App\Place;
use App\Teacher_assistant;
use Illuminate\Http\Request;
use App\Open_course;
use App\Semester;
use App\Timetable;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;

class TimeTableController extends Controller
{


    public function index()
    {
        //
        if(Auth::user()->userable_type!="Adm")
        {return redirect('/home');}

        $semester = Semester::orderBy('id','desc')->get(['name','id','start_date','complete']);
        return view('Portal.admin_panel.Panel',compact('semester'));
    }

    public function show($id , Request $request)
    {
        if(Auth::user()->userable_type!="Adm")
        {return redirect('/home');}

        /*Request must get from Ajax*/
        if(!$request->ajax()){return redirect('/Panel/TimeTable');}
        // check if semester exist or not
        $semester = Semester::find($id);
        if($semester)
        {
            /*check if semester is completed or can be modified in available 7 days */
            $startSemster_plus_7days = Carbon::parse($semester->start_date)->addWeek(1)->format('Y-m-d');
            $now =  Carbon::now()->format('Y-m-d');
            // if both two condition meet , then we can modify it
            if($semester->complete==0  && $now < $startSemster_plus_7days)
            {
                $openCourses = Open_course::where('semester_id',DB::table('semesters')->max('id'))->get();
                echo '
                <div class="optionView col-12">                         
                    <br>        
                        <div class="card" style="margin-bottom: 50px;">
                            <div class="panel panel-danger" style="padding-top: 0;border: 3px solid #5a5757;padding-bottom: 40px;">
                                <div class="headerName">All Open Courses &nbsp;&nbsp;<i class="fas fa-graduation-cap"></i></div>
                                    <div class="panel-body customBody row" style="padding: 25px 35px;"><br>';
                foreach ($openCourses as $course)
                {
                    $LectureAvilable= Timetable::where([ ['semester_id',$id] ,['subject_id', $course->subject_id] , ['timetableable_type','Doc'] ])->get()->count();
                    $SectionAvilable= Timetable::where([ ['semester_id',$id] ,['subject_id', $course->subject_id] , ['timetableable_type','T_A'] ])->get()->count();
                    echo '
                            <div class="col-xs-4" style="margin-bottom: 8px;">
                                <a id="openCourse" class="btn customCoursesAdding" style="padding-left: 15px !important;background-color:#3e3e3e75;"
                                    data-toggle="model" data-target="openCourseModal" '.(((($course->num_dr-$LectureAvilable)==0)&&(($course->num_ta-$SectionAvilable)==0))?" ":"onclick='showCreateForm(".$course->subject_id.",\"".$course->subject->name."\",".$course->semester_id.",".($course->num_dr-$LectureAvilable).",".($course->num_ta-$SectionAvilable).")'").'>
                                     '.$course->subject->name.'
                                     <b'.((($course->num_dr-$LectureAvilable)==0)?">":" class='subj_num_sec col-2' title='number of remaning Avilabel lectures'>".($course->num_dr-$LectureAvilable)."").'</b>
                                     <b'.((($course->num_ta-$SectionAvilable)==0)?">":" class='subj_num_sec col-2' title='number of remaning Avilabel sections'>".($course->num_ta-$SectionAvilable)."").'</b>
                                </a>
                            </div>                                                        
                             ';
                }
                echo '</div>
                            </div>
                        </div>
                </div>              
                ';
            }
            // if condition was broken , we will create a view but with little change (some semester can modify timetable and another not)

            $Sunday     = Timetable::where([['semester_id','=',$id],['day','=','Sunday']])->orderBy('time','asc')->get();
            $Monday     = Timetable::where([['semester_id','=',$id],['day','=','Monday']])->orderBy('time','asc')->get();
            $Tuesday    = Timetable::where([['semester_id','=',$id],['day','=','Tuesday']])->orderBy('time','asc')->get();
            $Wednesday  = Timetable::where([['semester_id','=',$id],['day','=','Wednesday']])->orderBy('time','asc')->get();
            $Thursday   = Timetable::where([['semester_id','=',$id],['day','=','Thursday']])->orderBy('time','asc')->get();

            /*Define Counter*/
            $sun_count=0;$mon_count=0;$tu_count=0;$we_count=0;$thu_count=0;$from5to7=0;$total_num_eachrow=0;
            echo  '
            <div style="overflow-x: auto;">
                <table class="timetable_table table table-bordered timetable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>7:9 AM</th>
                            <th>9:11 AM</th>
                            <th>11:1 PM</th>
                            <th>1:3 PM</th>
                            <th>3:5 PM</th>
                            <th>5:7 PM</th>
                        </tr>
                    </thead>
                    <tr>
                        <td class="even timetable_table_day">Sunday</td>
                     ';
            foreach($Sunday as $su)
            {
                /* From 7 to 9*/
                if($su->time==7)
                {
                    if($sun_count==0){echo '<td>'; $total_num_eachrow++;$sun_count++;}

                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST" style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }
                /*From 9 to 11*/
                if($su->time==9)
                {
                    if($sun_count==0){$total_num_eachrow++; $sun_count++;echo '<td></td>';}
                    if($mon_count==0)
                    {echo '<td>'; $total_num_eachrow++;$mon_count++;}

                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST"  style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }
                /*From 11 to 13*/
                if($su->time==11)
                {
                    if($sun_count==0){$total_num_eachrow++; $sun_count++; echo '<td></td>';}
                    if($mon_count==0){$total_num_eachrow++; $mon_count++; echo '<td></td>';}

                    if($tu_count==0)
                    {echo '<td>'; $total_num_eachrow++;$tu_count++;}

                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST"  style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }
                /*From 13 to 15*/
                if($su->time==13)
                {
                    if($sun_count==0){$total_num_eachrow++; $sun_count++; echo '<td ></td>';}
                    if($mon_count==0){$total_num_eachrow++; $mon_count++; echo '<td ></td>';}
                    if($tu_count==0) {$total_num_eachrow++; $tu_count++; echo '<td></td>';}

                    if($we_count==0)
                    {echo '<td>'; $total_num_eachrow++;$we_count++;}
                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST"  style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }

                /*From 15 to 17*/
                if($su->time==15)
                {
                    if($sun_count==0){$total_num_eachrow++; $sun_count++; echo '<td></td>';}
                    if($mon_count==0){$total_num_eachrow++; $mon_count++; echo '<td></td>';}
                    if($tu_count==0){ $total_num_eachrow++; $tu_count++; echo '<td></td>';}
                    if($we_count==0){ $total_num_eachrow++; $we_count++; echo '<td></td>';}

                    if($thu_count==0)
                    {echo '<td>'; $total_num_eachrow++;$thu_count++;}
                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST" style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }
                /*From 17 to 19*/
                if($su->time==17)
                {
                    if($sun_count==0){ $total_num_eachrow++; $sun_count++; echo '<td></td>';}
                    if($mon_count==0){ $total_num_eachrow++; $mon_count++; echo '<td></td>';}
                    if($tu_count==0) { $total_num_eachrow++; $tu_count++;  echo '<td></td>';}
                    if($we_count==0) { $total_num_eachrow++; $we_count++; echo ' <td></td>';}
                    if($thu_count==0){ $total_num_eachrow++; $thu_count++; echo '<td></td>';}

                    if($from5to7==0)
                    {echo '<td>'; $total_num_eachrow++;$from5to7++;}
                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST"  style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }
            }
            for ($x=$total_num_eachrow;$x<6;$x++){echo '<td></td>';}
            $total_num_eachrow=0;
            /*ReAssign Counter*/$sun_count=0;$mon_count=0;$tu_count=0;$we_count=0;$thu_count=0;$from5to7=0;$total_num_eachrow=0;

            echo'
              </tr> 
              <tr>
               <td class="timetable_table_day">Monday</td>
              ';
            foreach($Monday as $su)
            {
                /* From 7 to 9*/
                if($su->time==7)
                {
                    if($sun_count==0){echo '<td>'; $total_num_eachrow++;$sun_count++;}

                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST" style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }
                /*From 9 to 11*/
                if($su->time==9)
                {
                    if($sun_count==0){$total_num_eachrow++; $sun_count++;echo '<td class="empty"></td>';}
                    if($mon_count==0)
                    {echo '<td>'; $total_num_eachrow++;$mon_count++;}

                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST"  style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }
                /*From 11 to 13*/
                if($su->time==11)
                {
                    if($sun_count==0){$total_num_eachrow++; $sun_count++; echo '<td class="empty"></td>';}
                    if($mon_count==0){$total_num_eachrow++; $mon_count++; echo '<td class="empty"></td>';}

                    if($tu_count==0)
                    {echo '<td>'; $total_num_eachrow++;$tu_count++;}

                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST"  style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }
                /*From 13 to 15*/
                if($su->time==13)
                {
                    if($sun_count==0){$total_num_eachrow++; $sun_count++; echo '<td class="empty"></td>';}
                    if($mon_count==0){$total_num_eachrow++; $mon_count++; echo '<td class="empty"></td>';}
                    if($tu_count==0) {$total_num_eachrow++; $tu_count++; echo '<td class="empty"></td>';}

                    if($we_count==0)
                    {echo '<td>'; $total_num_eachrow++;$we_count++;}
                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST"  style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }

                /*From 15 to 17*/
                if($su->time==15)
                {
                    if($sun_count==0){$total_num_eachrow++; $sun_count++; echo '<td class="empty"></td>';}
                    if($mon_count==0){$total_num_eachrow++; $mon_count++; echo '<td class="empty"></td>';}
                    if($tu_count==0){ $total_num_eachrow++; $tu_count++; echo '<td class="empty"></td>';}
                    if($we_count==0){ $total_num_eachrow++; $we_count++; echo '<td class="empty"></td>';}

                    if($thu_count==0)
                    {echo '<td>'; $total_num_eachrow++;$thu_count++;}
                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST" style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }
                /*From 17 to 19*/
                if($su->time==17)
                {
                    if($sun_count==0){ $total_num_eachrow++; $sun_count++; echo '<td class="empty"></td>';}
                    if($mon_count==0){ $total_num_eachrow++; $mon_count++; echo '<td class="empty"></td>';}
                    if($tu_count==0) { $total_num_eachrow++; $tu_count++;  echo '<td class="empty"></td>';}
                    if($we_count==0) { $total_num_eachrow++; $we_count++; echo '<td class="empty"></td>';}
                    if($thu_count==0){ $total_num_eachrow++; $thu_count++; echo '<td class="empty"></td>';}

                    if($from5to7==0)
                    {echo '<td>'; $total_num_eachrow++;$from5to7++;}
                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST"  style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }
            }
            for ($x=$total_num_eachrow;$x<6;$x++){echo '<td class="empty"></td>';}
            $total_num_eachrow=0;
            /*ReAssign Counter*/$sun_count=0;$mon_count=0;$tu_count=0;$we_count=0;$thu_count=0;$from5to7=0;$total_num_eachrow=0;
            echo
            '</tr>
                         <tr>
                            <td class="even timetable_table_day">Tuesday</td>
                         ';
            foreach($Tuesday as $su)
            {
                /* From 7 to 9*/
                if($su->time==7)
                {
                    if($sun_count==0){echo '<td>'; $total_num_eachrow++;$sun_count++;}

                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST" style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }
                /*From 9 to 11*/
                if($su->time==9)
                {
                    if($sun_count==0){$total_num_eachrow++; $sun_count++;echo '<td class="empty"></td>';}
                    if($mon_count==0)
                    {echo '<td>'; $total_num_eachrow++;$mon_count++;}

                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST"  style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }
                /*From 11 to 13*/
                if($su->time==11)
                {
                    if($sun_count==0){$total_num_eachrow++; $sun_count++; echo '<td class="empty"></td>';}
                    if($mon_count==0){$total_num_eachrow++; $mon_count++; echo '<td class="empty"></td>';}

                    if($tu_count==0)
                    {echo '<td>'; $total_num_eachrow++;$tu_count++;}

                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST"  style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }
                /*From 13 to 15*/
                if($su->time==13)
                {
                    if($sun_count==0){$total_num_eachrow++; $sun_count++; echo '<td class="empty"></td>';}
                    if($mon_count==0){$total_num_eachrow++; $mon_count++; echo '<td class="empty"></td>';}
                    if($tu_count==0) {$total_num_eachrow++; $tu_count++; echo '<td class="empty"></td>';}

                    if($we_count==0)
                    {echo '<td>'; $total_num_eachrow++;$we_count++;}
                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST"  style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }

                /*From 15 to 17*/
                if($su->time==15)
                {
                    if($sun_count==0){$total_num_eachrow++; $sun_count++; echo '<td class="empty"></td>';}
                    if($mon_count==0){$total_num_eachrow++; $mon_count++; echo '<td class="empty"></td>';}
                    if($tu_count==0){ $total_num_eachrow++; $tu_count++; echo '<td class="empty"></td>';}
                    if($we_count==0){ $total_num_eachrow++; $we_count++; echo '<td class="empty"></td>';}

                    if($thu_count==0)
                    {echo '<td>'; $total_num_eachrow++;$thu_count++;}
                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST" style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }
                /*From 17 to 19*/
                if($su->time==17)
                {
                    if($sun_count==0){ $total_num_eachrow++; $sun_count++; echo '<td class="empty"></td>';}
                    if($mon_count==0){ $total_num_eachrow++; $mon_count++; echo '<td class="empty"></td>';}
                    if($tu_count==0) { $total_num_eachrow++; $tu_count++;  echo '<td class="empty"></td>';}
                    if($we_count==0) { $total_num_eachrow++; $we_count++; echo '<td class="empty"></td>';}
                    if($thu_count==0){ $total_num_eachrow++; $thu_count++; echo '<td class="empty"></td>';}

                    if($from5to7==0)
                    {echo '<td>'; $total_num_eachrow++;$from5to7++;}
                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST"  style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }
            }
            for ($x=$total_num_eachrow;$x<6;$x++){echo '<td class="empty"></td>';}
            $total_num_eachrow=0;
            /*ReAssign Counter*/ $sun_count=0;$mon_count=0;$tu_count=0;$we_count=0;$thu_count=0;$from5to7=0;$total_num_eachrow=0;
            echo'
                          </tr>
                          <tr>
                            <td class="timetable_table_day">Wednesday</td>
                         ';
            foreach($Wednesday as $su)
            {
                /* From 7 to 9*/
                if($su->time==7)
                {
                    if($sun_count==0){echo '<td>'; $total_num_eachrow++;$sun_count++;}

                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST" style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }
                /*From 9 to 11*/
                if($su->time==9)
                {
                    if($sun_count==0){$total_num_eachrow++; $sun_count++;echo '<td class="empty"></td>';}
                    if($mon_count==0)
                    {echo '<td>'; $total_num_eachrow++;$mon_count++;}

                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST"  style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }
                /*From 11 to 13*/
                if($su->time==11)
                {
                    if($sun_count==0){$total_num_eachrow++; $sun_count++; echo '<td class="empty"></td>';}
                    if($mon_count==0){$total_num_eachrow++; $mon_count++; echo '<td class="empty"></td>';}

                    if($tu_count==0)
                    {echo '<td>'; $total_num_eachrow++;$tu_count++;}

                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST"  style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }
                /*From 13 to 15*/
                if($su->time==13)
                {
                    if($sun_count==0){$total_num_eachrow++; $sun_count++; echo '<td class="empty"></td>';}
                    if($mon_count==0){$total_num_eachrow++; $mon_count++; echo '<td class="empty"></td>';}
                    if($tu_count==0) {$total_num_eachrow++; $tu_count++; echo '<td class="empty"></td>';}

                    if($we_count==0)
                    {echo '<td>'; $total_num_eachrow++;$we_count++;}
                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST"  style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }

                /*From 15 to 17*/
                if($su->time==15)
                {
                    if($sun_count==0){$total_num_eachrow++; $sun_count++; echo '<td class="empty"></td>';}
                    if($mon_count==0){$total_num_eachrow++; $mon_count++; echo '<td class="empty"></td>';}
                    if($tu_count==0){ $total_num_eachrow++; $tu_count++; echo '<td class="empty"></td>';}
                    if($we_count==0){ $total_num_eachrow++; $we_count++; echo '<td class="empty"></td>';}

                    if($thu_count==0)
                    {echo '<td>'; $total_num_eachrow++;$thu_count++;}
                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST" style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }
                /*From 17 to 19*/
                if($su->time==17)
                {
                    if($sun_count==0){ $total_num_eachrow++; $sun_count++; echo '<td class="empty"></td>';}
                    if($mon_count==0){ $total_num_eachrow++; $mon_count++; echo '<td class="empty"></td>';}
                    if($tu_count==0) { $total_num_eachrow++; $tu_count++;  echo '<td class="empty"></td>';}
                    if($we_count==0) { $total_num_eachrow++; $we_count++; echo '<td class="empty"></td>';}
                    if($thu_count==0){ $total_num_eachrow++; $thu_count++; echo '<td class="empty"></td>';}

                    if($from5to7==0)
                    {echo '<td>'; $total_num_eachrow++;$from5to7++;}
                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST"  style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }
            }
            for ($x=$total_num_eachrow;$x<6;$x++){echo '<td class="empty"></td>';}
            $total_num_eachrow=0;
            /*ReAssign Counter*/ $sun_count=0;$mon_count=0;$tu_count=0;$we_count=0;$thu_count=0;$from5to7=0;$total_num_eachrow=0;
            echo'
                          </tr> 
                          <tr>
                            <td class="even timetable_table_day">Thursday</td>
                          ';
            foreach($Thursday as $su)
            {
                /* From 7 to 9*/
                if($su->time==7)
                {
                    if($sun_count==0){echo '<td>'; $total_num_eachrow++;$sun_count++;}

                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST" style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }
                /*From 9 to 11*/
                if($su->time==9)
                {
                    if($sun_count==0){$total_num_eachrow++; $sun_count++;echo '<td class="empty"></td>';}
                    if($mon_count==0)
                    {echo '<td>'; $total_num_eachrow++;$mon_count++;}

                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST"  style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }
                /*From 11 to 13*/
                if($su->time==11)
                {
                    if($sun_count==0){$total_num_eachrow++; $sun_count++; echo '<td class="empty"></td>';}
                    if($mon_count==0){$total_num_eachrow++; $mon_count++; echo '<td class="empty"></td>';}

                    if($tu_count==0)
                    {echo '<td>'; $total_num_eachrow++;$tu_count++;}

                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST"  style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }
                /*From 13 to 15*/
                if($su->time==13)
                {
                    if($sun_count==0){$total_num_eachrow++; $sun_count++; echo '<td class="empty"></td>';}
                    if($mon_count==0){$total_num_eachrow++; $mon_count++; echo '<td class="empty"></td>';}
                    if($tu_count==0) {$total_num_eachrow++; $tu_count++; echo '<td class="empty"></td>';}

                    if($we_count==0)
                    {echo '<td>'; $total_num_eachrow++;$we_count++;}
                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST"  style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }

                /*From 15 to 17*/
                if($su->time==15)
                {
                    if($sun_count==0){$total_num_eachrow++; $sun_count++; echo '<td class="empty"></td>';}
                    if($mon_count==0){$total_num_eachrow++; $mon_count++; echo '<td class="empty"></td>';}
                    if($tu_count==0){ $total_num_eachrow++; $tu_count++; echo '<td class="empty"></td>';}
                    if($we_count==0){ $total_num_eachrow++; $we_count++; echo '<td class="empty"></td>';}

                    if($thu_count==0)
                    {echo '<td>'; $total_num_eachrow++;$thu_count++;}
                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST" style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }
                /*From 17 to 19*/
                if($su->time==17)
                {
                    if($sun_count==0){ $total_num_eachrow++; $sun_count++; echo '<td class="empty"></td>';}
                    if($mon_count==0){ $total_num_eachrow++; $mon_count++; echo '<td class="empty"></td>';}
                    if($tu_count==0) { $total_num_eachrow++; $tu_count++;  echo '<td class="empty"></td>';}
                    if($we_count==0) { $total_num_eachrow++; $we_count++; echo '<td class="empty"></td>';}
                    if($thu_count==0){ $total_num_eachrow++; $thu_count++; echo '<td class="empty"></td>';}

                    if($from5to7==0)
                    {echo '<td>'; $total_num_eachrow++;$from5to7++;}
                    echo '<div '.(($su->timetableable_type=='Doc')?"style='background-color:#659dbf75;'":"").' class="set row col-12"> <div class="col-12" style="padding: 2px;">'.$su->subject->subject->name.'&nbsp;&nbsp;';
                    /*check if it can be editable or not*/
                    if($semester->complete==0  && $now < $startSemster_plus_7days)
                    {
                        echo '<form action="/Panel/TimeTable/'.$su->id.'" method="POST"  style="display: inline-block;" >
                                  <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                                  <button class="btn" type="submit" >
                                        <i class="fas fa-times-circle deleteOPenCourse"></i>
                                  </button>
                              </form>';
                    }
                    echo '</div>';
                    if($su->timetableable_type=='Doc'){ echo '<div class="col-8 teach">Doc.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    else { echo '<div class="col-8 teach">T_A.'.$su->timetableable->user[0]->name_en.'</div>'; }
                    echo '<div class="col-4 place">'.$su->place->name.'</div></div>';
                }
            }
            for ($x=$total_num_eachrow;$x<6;$x++){echo '<td class="empty"></td>';}
            $total_num_eachrow=0;
            /*ReAssign Counter*/ $sun_count=0;$mon_count=0;$tu_count=0;$we_count=0;$thu_count=0;$from5to7=0;$total_num_eachrow=0;
            echo'
                          </tr>                               
                    </table>
                </div>
                  ';
        }

        // if semester not exists in database redirect
        else
        {
            if(Request::ajax()){return 'no';}
            return redirect('/Panel/TimeTable');
        }
    }

    public function TimeTableCourse($courseID , $courseName , $semsterID , $AvilableLectures , $AvilableSections)
    {
        if(Auth::user()->userable_type!="Adm")
        {return redirect('/home');}

        $All_doctors = Doctor::all();
        $All_instructors = Teacher_assistant::all();
        $labs = Place::where('type','1')->get();
        $halls = Place::where('type','0')->get();
        /*get id of subject leader */
        $courseID_Leader = Open_course::select('doctor_id')->where([ ['semester_id', $semsterID], ['subject_id',$courseID] ])->get();
        $courseID_Leader = $courseID_Leader[0]->doctor_id;
        /*check if leader take place in this course in timeTable our not*/
        $leaderExists = Timetable::where([ ['semester_id', $semsterID], ['subject_id',$courseID] , ['timetableable_id',$courseID_Leader] ])->first();
        $form = '
         <div class="modal-header">
            <h2 id="TimeTablecourseName">'.$courseName.'</h2>
            <span onclick="closeTImeTableform()" class="close" style="color: white;font-size: 40px;color: #0a0a0a !important;opacity: 1;text-shadow: 2px -2px 3px #343a40;">×</span>
         </div>
         <div class="modal-body">
            <div class="form-group form_style">
                <form id="openCourseForm" method="POST" action="/Panel/TimeTable/store" style="padding: 15px;">
                    <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'"> 
                    <input type="hidden" name="semesterID" value="'.$semsterID.'">
                    <input type="hidden" name="CourseID" value="'.$courseID.'">                    
                        <div class="form-group row">
                            <label  class="col-4 col-form-label">Type: </label>
                            <div id="LectureType"  class="col-4">
                                <label class="radio-inline form" '.(($AvilableLectures==0)?"title=\"No Remaining Lectures Available\" style=\"color:#f00;\" ":"").' ><input type="radio" '.(($AvilableLectures==0)?"disabled":"").' value="Doc"  class="" name="TimeTable_type" onclick="showFormParts(this.value)"> Lecture</label>
                            </div>
                        
                            <div id="LectureType"  class="col-4">
                                 <label class="radio-inline"  '.(($AvilableSections==0)?"title=\"No Remaining Section Available\" style=\"color:#f00;\" ":"").' ><input type="radio" value="T_A" '.(($AvilableSections==0)?"disabled":"").' class="" name="TimeTable_type" onclick="showFormParts(this.value)"> Section</label>
                            </div>                        
                        </div>
                        
                        <div class="doc_field" style="display: none;">   
                            <div class="form-group row"> 
                                <label  class="col-4 col-form-label">Select Doctor: </label>
                                <select class="form-control col-8" name="doctorID" >';
        foreach ($All_doctors as $doctor)
        {
            /*if user Not Select leader for subject yet*/
            if($leaderExists===null && $AvilableLectures==1)
            {
                if($doctor->user[0]->userable_id==$courseID_Leader)
                {
                    $form.='<option value="'.$doctor->user[0]->userable_id.'">* '.$doctor->user[0]->name_en.' ( Leader )</option>';
                }
            }
            else
            {$form.='<option value="'.$doctor->user[0]->userable_id.'">'.$doctor->user[0]->name_en.'</option>';}
        }
        $form.='
                                </select>
                            </div>
                            
                             <div class="form-group row"> 
                                <label  class="col-4 col-form-label">Select Halls: </label>
                                <select class="form-control col-8" name="hallID">';
        foreach ($halls as $place)
        {
            $form.='<option value="'.$place->id.'">'.$place->name.'</option>';
        }
        $form.='
                                </select>
                            </div>                               
                        </div>

                        <div class="AT_field" style="display: none;">
                            <div class="form-group row"> 
                                <label  class="col-4 col-form-label">Select Instructor: </label>
                                <select class="form-control col-8" name="instructorID">';
        foreach ($All_instructors as $instructor)
        {
            $form.='<option value="'.$instructor->user[0]->userable_id.'">'.$instructor->user[0]->name_en.'</option>';
        }
        $form.='
                                </select>
                            </div>                            
                             <div class="form-group row"> 
                                <label  class="col-4 col-form-label">Select Labs: </label>
                                <select class="form-control col-8" name="labID">';
        foreach ($labs as $place)
        {
            $form.='<option value="'.$place->id.'">'.$place->name.'</option>';
        }
        $form.='
                                </select>
                            </div>  
                        </div>
                        
                        <div class="form-group row days" style="display:none;">
                            <label  class="col-4 col-form-label">select Day </label>
                            <select class="form-control col-8" name="day">
                                <option value="Sunday">Sunday</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>                    
                            </select>                            
                        </div>
                        
                        <div class="form-group row hours" style="display: none;">
                            <label  class="col-4 col-form-label">select Day </label>
                            <select class="form-control col-8" name="hour">
                                <option value="7">7 AM : 9 AM</option>
                                <option value="9">9 AM : 11 AM</option>
                                <option value="11">11 AM : 13 PM</option>
                                <option value="13">13 PM : 15 PM</option>
                                <option value="15">15 PM : 17 PM</option>   
                                <option value="17">17 PM : 19 PM</option>                           
                            </select>                            
                        </div>

                        <br>
                        <div class="form-group row TimeTableSaveButton" style="margin-bottom: 0;display:none;">
                            <button id="" type="submit" class="btn btn-block btn-outline-dark">Save <i class="fas fa-graduation-cap"></i></button>
                        </div>
                </form>
            </div>
         </div>';
        return $form;
    }

    public function store (Request $request)
    {
        if(Auth::user()->userable_type!="Adm")
        {return redirect('/home');}

        $timeTable = new Timetable();
        if($request->TimeTable_type=='Doc')
        {
            /*check place & time has not conflict */
            $checkP_T=Timetable::where([['place_id', $request->hallID],['time',$request->hour],['day',$request->day] ])->first();
            if($checkP_T===null)
            {
                /*check if Doctor Avilable in this dateTIme or not */
                $checkDoctorAvilable = Timetable::where([['timetableable_id', $request->doctorID],['time',$request->hour],['day',$request->day] ])->first();
                if($checkDoctorAvilable===null)
                {
                    /*doctor Avilable*/
                    $timeTable->timetableable_id = $request->doctorID ;
                    $timeTable->timetableable_type = 'Doc';
                    $timeTable->place_id = $request->hallID;
                }
                else
                { return redirect()->back()->withErrors(['Error'=>'doctor is not Avilable at this dateTime']); }
            }
            else
            { return redirect()->back()->withErrors(['Error'=>'there is conflict between dateTime & place of course you wanna to add ']);  }

        }
        else
        {
            /*check place & time has not conflict */
            $checkP_T=Timetable::where([['place_id', $request->labID],['time',$request->hour],['day',$request->day] ])->first();
            if($checkP_T===null)
            {
                /*check if Instructor Avilable in this dateTIme or not */
                $checkInstructorAvilable = Timetable::where([['timetableable_id', $request->instructorID],['time',$request->hour],['day',$request->day] ])->first();
                if($checkInstructorAvilable===null)
                {
                    /*Avilable*/
                    $timeTable->timetableable_id = $request->instructorID ;
                    $timeTable->timetableable_type = 'T_A';
                    $timeTable->place_id = $request->labID;
                }
                else
                {  return redirect()->back()->withErrors(['Error'=>'Instructor is not Avilable at this dateTime']); }
            }
            else
            { return redirect()->back()->withErrors(['Error'=>'there is conflict between dateTime & place of section you wanna to add ']);}
        }

        $timeTable->semester_id = $request->semesterID;
        $timeTable->subject_id = $request->CourseID;
        $timeTable->admin_id = Auth::user()->userable_id;
        $timeTable->time = $request->hour;
        $timeTable->day=$request->day;

        $timeTable->save();
        return redirect()->back()->with('message','your course Added to time table successfully');
    }

    public function destroy ($id)
    {
        if(Auth::user()->userable_type!="Adm")
        {return redirect('/home');}

        $TimTable = Timetable::find($id);
        if($TimTable)
        {
            $TimTable->delete();
            return redirect()->back()->with('message','Course in Time Table deleted successfully ');
        }
        else
        { return redirect('')->back()->withErrors(['Error'=>'course you wanna to delete isn\'t exists ']); }
    }

    public function view()
    {

        $user = Auth::user();
        if($user->userable_type == 'T_A' || $user->userable_type == 'Doc' || $user->userable_type == 'Stu')
        {
            $avlb = 0;
            $lectures = [];
            $currentSemester = Semester::where('complete',0)->where('start_date', '<=', today())->first(); // current semester
            if($currentSemester)
            {
                $limit = Carbon::parse($currentSemester->start_date)->addWeek(1)->format('Y-m-d'); // first week end
                if(today()>$limit)
                    $avlb = 1;
                if($avlb)
                {
                    if($user->userable_type == 'Doc')
                        $lectures = Timetable::where([['semester_id', $currentSemester->id], ['timetableable_type', 'doc'], ['timetableable_id',$user->userable_id]])->get();
                    elseif($user->userable_type == 'T_A')
                        $sections = Timetable::where([['semester_id', $currentSemester->id], ['timetableable_type', 't_a'], ['timetableable_id',$user->userable_id]])->get();
                    else
                        $timetables = $user->userable->timetables->where('semester_id', $currentSemester->id);
                }
            }
            if($user->userable_type == 'Doc')
                return view('Portal.Doctor_panel.Panel',compact('avlb','lectures','currentSemester'));
            elseif($user->userable_type == 'T_A')
                return view('Portal.Instructor_panel.Panel',compact('avlb','sections','currentSemester'));
            else
                return view('Portal.student_panel.Panel',compact('avlb','timetables','currentSemester'));
        }
        else
            return view('Portal.public.index');
    }
}