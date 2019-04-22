<div class="headerName" style="background-color:#c3cee36e !important;">{{'Midterm Schedule'}} &nbsp; <i class="far fa-calendar-alt" style="color: #2c4167;"></i></div>
<style>
    #overlay
    {
    position: fixed; /* Sit on top of the page content */
    display: none; /* Hidden by default */
    width: 100%; /* Full width (cover the whole page) */
    height: 100%; /* Full height (cover the whole page) */
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.5); /* Black background with opacity */
    z-index: 99; /* Specify a stack order in case you're using a different order for other elements */
    cursor: pointer; /* Add a pointer on hover */
    }
    .form-control {
        /* display: block; */
        /* width: 100%; */
        /* padding: .375rem .75rem; */
        /* font-size: 1rem; */
        /* line-height: 1.5; */
         color: #08344d;
        /* background-color: #fff; */
        background-clip: padding-box;
        border: 1px solid #ced4da;
        /* border-radius: .25rem; */
        /* transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out; */
        height: 45px;
        margin: 0;
        font-size: 14px;
    }
</style>
<div id="overlay" style="display: none;"></div>
@php $StartDate_save = $midtermTime; @endphp
{{----------------------------------------All Courses------------------------------------------------------}}
@if($canEdit==1)
<div class="optionView col-12">
    <br>
    <div class="card" style="margin-bottom: 30px;">
        <div class="panel panel-danger" style="padding-top: 0;border: 3px solid #2c416785;padding-bottom: 10px;">
            <div class="headerName" style="background-color: #c3cee36e;">{{'Select open Courses To Add in Schedule'}} &nbsp;&nbsp;<i class="fas fa-graduation-cap"></i></div>
            <div class="panel-body customBody row" style="padding: 10px 25px;">
                @foreach($subjects as $sbj)
                    <div class="col-xs-4" style="margin: 4px 3px;">
                        @php
                            $TImeTableIDs_in_course = $sbj->subject->timetables->pluck('id');
                            $AllStudents_in_course =  DB::table('student_timetable')->whereIn('timetable_id',$TImeTableIDs_in_course)->count();
                        @endphp
                        <a id="openCourse" onclick="AddMidtermExam(@php echo'\''.$sbj->subject->name.'\','.$sbj->subject_id.','.$AllStudents_in_course; @endphp )" class="btn customCoursesAdding" style="font-size: 15px;padding-left: 15px !important;padding-right:5px !important;background-color: #7996ca59;color: #fff;font-weight: bolder;text-shadow: 1px 1px 1px #000, -1px -1px 1px #2c4167;" data-toggle="model" data-target="openCourseModal">
                            {{$sbj->subject->name}}
                            <b class="subj_num_sec col-2" style="border-radius:0px !important; color: #000;text-shadow: none;" title="number of student who register {{$sbj->subject->name}} course">@php echo $AllStudents_in_course; @endphp</b>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif
{{-------------------------------------ViewMidtermSchedule------------------------------------------}}
<div style="overflow-x: auto;">
    <table class="timetable_table table table-bordered timetable">
        <thead>
            <tr>
                <th>#</th>
                <th>9:10 AM</th>
                <th>10:11 AM</th>
                <th>11:12 PM</th>
                <th>12:1 PM</th>
                <th>1:2 PM</th>
                <th>2:3 PM</th>
                <th>3:4 PM</th>
                <th>4:5 PM</th>
            </tr>
        </thead>
        @foreach($weekDays as $day)
            <tr>
                <td class="timetable_table_day" style="width: 100px !important;">{{$day}}<br>{{$midtermTime}}</td>
                {{--get All exams in specific day--}}
                @php
                    $midtermTime = \Carbon\Carbon::parse($midtermTime)->addDay(1)->format('Y-m-d');
                    $DayExam = $allExamsSchedule->where('day',$day);
                    $T9=0;$T10=0;$T11=0;$T12=0;$T13=0;$T14=0;$T15=0;$T16=0;$total=0;
                @endphp
                {{-- loop for all periods --}}
                @foreach($DayExam as $day)
                    @if($day->time==9)
                        @php
                            if($T9==0){ echo '<td>'; $T9++; $total++;}
                                echo
                                '<div class="set row col-12">
                                    <div class="col-12" style="padding: 2px;">';$subject=\App\Subject::find($day->subject_id);
                                         echo $subject->name.' &nbsp;&nbsp';
                                         if($canEdit==1)
                                         {
                                            echo
                                            '<form method="post" action="/Panel/MidtermSchedule/delete" style="display: inline-block;" >
                                                <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'">
                                                <input type="hidden" name="Semester_id" value="'.$day->semester_id.'">
                                                <input type="hidden" name="subject_id" value="'.$day->subject_id.'">
                                                <button class="btn" onclick="deleteMIdtermSchedule(this); return false;" >
                                                <i class="fas fa-times-circle deleteOPenCourse"></i>
                                                </button>
                                            </form>';
                                          }
                                    echo
                                    '</div>
                                    <div class="col-8 teach">'.$day->ta->user[0]->name_en.'</div>
                                    <div class="col-4 place">'.$day->place->name.'</div>
                                </div>';
                        @endphp
                    @endif

                    @if($day->time==10)
                        @php
                            if($T9==0){echo '<td></td>'; $total++; $T9++;}
                            if($T10==0){ echo '<td>'; $T10++; $total++;}
                            echo
                                '<div class="set row col-12">
                                    <div class="col-12" style="padding: 2px;">';$subject=\App\Subject::find($day->subject_id);
                                         echo $subject->name.' &nbsp;&nbsp';
                                         if($canEdit==1)
                                         {
                                            echo
                                            '<form method="post" action="/Panel/MidtermSchedule/delete" style="display: inline-block;" >
                                                <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'">
                                                <input type="hidden" name="Semester_id" value="'.$day->semester_id.'">
                                                <input type="hidden" name="subject_id" value="'.$day->subject_id.'">
                                                <button class="btn" onclick="deleteMIdtermSchedule(this); return false;" >
                                                <i class="fas fa-times-circle deleteOPenCourse"></i>
                                                </button>
                                            </form>';
                                          }
                                    echo
                                    '</div>
                                    <div class="col-8 teach">'.$day->ta->user[0]->name_en.'</div>
                                    <div class="col-4 place">'.$day->place->name.'</div>
                                </div>';
                        @endphp
                    @endif

                    @if($day->time==11)
                        @php
                            if($T9==0){echo '<td></td>';$total++;$T9++;}
                            if($T10==0){echo '<td></td>';$total++;$T10++;}
                            if($T11==0){ echo '<td>';$T11++;$total++;}
                            echo
                                '<div class="set row col-12">
                                    <div class="col-12" style="padding: 2px;">';$subject=\App\Subject::find($day->subject_id);
                                         echo $subject->name.' &nbsp;&nbsp';
                                        if($canEdit==1)
                                         {
                                            echo
                                            '<form method="post" action="/Panel/MidtermSchedule/delete" style="display: inline-block;" >
                                                <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'">
                                                <input type="hidden" name="Semester_id" value="'.$day->semester_id.'">
                                                <input type="hidden" name="subject_id" value="'.$day->subject_id.'">
                                                <button class="btn" onclick="deleteMIdtermSchedule(this); return false;" >
                                                <i class="fas fa-times-circle deleteOPenCourse"></i>
                                                </button>
                                            </form>';
                                          }
                                    echo
                                    '</div>
                                    <div class="col-8 teach">'.$day->ta->user[0]->name_en.'</div>
                                    <div class="col-4 place">'.$day->place->name.'</div>
                                </div>';
                        @endphp
                    @endif

                    @if($day->time==12)
                        @php
                            if($T9==0){echo '<td></td>';$total++;$T9++;}
                            if($T10==0){echo '<td></td>';$total++;$T10++;}
                            if($T11==0){echo '<td></td>';$total++;$T11++;}
                            if($T12==0){ echo '<td>';$T12++;$total++;}
                            echo
                                '<div class="set row col-12">
                                    <div class="col-12" style="padding: 2px;">';$subject=\App\Subject::find($day->subject_id);
                                         echo $subject->name.' &nbsp;&nbsp';
                                         if($canEdit==1)
                                         {
                                            echo
                                            '<form method="post" action="/Panel/MidtermSchedule/delete" style="display: inline-block;" >
                                                <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'">
                                                <input type="hidden" name="Semester_id" value="'.$day->semester_id.'">
                                                <input type="hidden" name="subject_id" value="'.$day->subject_id.'">
                                                <button class="btn" onclick="deleteMIdtermSchedule(this); return false;" >
                                                <i class="fas fa-times-circle deleteOPenCourse"></i>
                                                </button>
                                            </form>';
                                         }
                                    echo
                                    '</div>
                                    <div class="col-8 teach">'.$day->ta->user[0]->name_en.'</div>
                                    <div class="col-4 place">'.$day->place->name.'</div>
                                </div>';
                        @endphp
                    @endif

                    @if($day->time==13)
                        @php
                            if($T9==0){echo '<td></td>';$total++;$T9++;}
                            if($T10==0){echo '<td></td>';$total++;$T10++;}
                            if($T11==0){echo '<td></td>';$total++;$T11++;}
                            if($T12==0){echo '<td></td>';$total++;$T12++;}
                            if($T13==0) { echo '<td>';$T13++;$total++;}
                            echo
                                '<div class="set row col-12">
                                    <div class="col-12" style="padding: 2px;">';$subject=\App\Subject::find($day->subject_id);
                                         echo $subject->name.' &nbsp;&nbsp';
                                         if($canEdit==1)
                                         {
                                            echo
                                            '<form method="post" action="/Panel/MidtermSchedule/delete" style="display: inline-block;" >
                                                <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'">
                                                <input type="hidden" name="Semester_id" value="'.$day->semester_id.'">
                                                <input type="hidden" name="subject_id" value="'.$day->subject_id.'">
                                                <button class="btn" onclick="deleteMIdtermSchedule(this); return false;" >
                                                <i class="fas fa-times-circle deleteOPenCourse"></i>
                                                </button>
                                            </form>';
                                          }
                                    echo
                                    '</div>
                                    <div class="col-8 teach">'.$day->ta->user[0]->name_en.'</div>
                                    <div class="col-4 place">'.$day->place->name.'</div>
                                </div>';
                        @endphp
                    @endif

                    @if($day->time==14)
                        @php
                            if($T9==0){echo '<td></td>';$total++;$T9++;}
                            if($T10==0){echo '<td></td>';$total++;$T10++;}
                            if($T11==0){echo '<td></td>';$total++;$T11++;}
                            if($T12==0){echo '<td></td>';$total++;$T12++;}
                            if($T13==0){echo '<td></td>';$total++;$T13++;}
                            if($T14==0){ echo '<td>';$T14++;$total++;}
                            echo
                                '<div class="set row col-12">
                                    <div class="col-12" style="padding: 2px;">';$subject=\App\Subject::find($day->subject_id);
                                         echo $subject->name.' &nbsp;&nbsp';
                                         if($canEdit==1)
                                         {
                                            echo
                                            '<form method="post" action="/Panel/MidtermSchedule/delete" style="display: inline-block;" >
                                                <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'">
                                                <input type="hidden" name="Semester_id" value="'.$day->semester_id.'">
                                                <input type="hidden" name="subject_id" value="'.$day->subject_id.'">
                                                <button class="btn" onclick="deleteMIdtermSchedule(this); return false;" >
                                                <i class="fas fa-times-circle deleteOPenCourse"></i>
                                                </button>
                                            </form>';
                                          }
                                    echo
                                    '</div>
                                    <div class="col-8 teach">'.$day->ta->user[0]->name_en.'</div>
                                    <div class="col-4 place">'.$day->place->name.'</div>
                                </div>';
                        @endphp
                    @endif

                    @if($day->time==15)
                        @php
                            if($T9==0){echo '<td></td>';$total++;$T9++;}
                            if($T10==0){echo '<td></td>';$total++;$T10++;}
                            if($T11==0){echo '<td></td>';$total++;$T11++;}
                            if($T12==0){echo '<td></td>';$total++;$T12++;}
                            if($T13==0){echo '<td></td>';$total++;$T13++;}
                            if($T14==0){echo '<td></td>';$total++;$T14++;}
                            if($T15==0){ echo '<td>';$T15++;$total++;}
                            echo
                                '<div class="set row col-12">
                                    <div class="col-12" style="padding: 2px;">';$subject=\App\Subject::find($day->subject_id);
                                         echo $subject->name.' &nbsp;&nbsp';
                                         if($canEdit==1)
                                         {
                                            echo
                                            '<form method="post" action="/Panel/MidtermSchedule/delete" style="display: inline-block;" >
                                                <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'">
                                                <input type="hidden" name="Semester_id" value="'.$day->semester_id.'">
                                                <input type="hidden" name="subject_id" value="'.$day->subject_id.'">
                                                <button class="btn" onclick="deleteMIdtermSchedule(this); return false;" >
                                                <i class="fas fa-times-circle deleteOPenCourse"></i>
                                                </button>
                                            </form>';
                                         }
                                    echo
                                    '</div>
                                    <div class="col-8 teach">'.$day->ta->user[0]->name_en.'</div>
                                    <div class="col-4 place">'.$day->place->name.'</div>
                                </div>';
                        @endphp
                    @endif

                    @if($day->time==16)
                        @php
                            if($T9==0){echo '<td></td>';$total++;$T9++;}
                            if($T10==0){echo '<td></td>';$total++;$T10++;}
                            if($T11==0){echo '<td></td>';$total++;$T11++;}
                            if($T12==0){echo '<td></td>';$total++;$T12++;}
                            if($T13==0){echo '<td></td>';$total++;$T13++;}
                            if($T14==0){echo '<td></td>';$total++;$T14++;}
                            if($T15==0){echo '<td></td>';$total++;$T15++;}
                            if($T16==0){ echo '<td>';$T16++;$total++;}
                            echo
                                '<div class="set row col-12">
                                    <div class="col-12" style="padding: 2px;">';$subject=\App\Subject::find($day->subject_id);
                                         echo $subject->name.' &nbsp;&nbsp';
                                         if($canEdit==1)
                                         {
                                            echo
                                            '<form method="post" action="/Panel/MidtermSchedule/delete" style="display: inline-block;" >
                                                <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'">
                                                <input type="hidden" name="Semester_id" value="'.$day->semester_id.'">
                                                <input type="hidden" name="subject_id" value="'.$day->subject_id.'">
                                                <button class="btn" onclick="deleteMIdtermSchedule(this); return false;" >
                                                <i class="fas fa-times-circle deleteOPenCourse"></i>
                                                </button>
                                            </form>';
                                          }
                                    echo
                                    '</div>
                                    <div class="col-8 teach">'.$day->ta->user[0]->name_en.'</div>
                                    <div class="col-4 place">'.$day->place->name.'</div>
                                </div>';
                        @endphp
                    @endif
                @endforeach
                @php for($i = $total; $i < 8 ; $i++){echo '<td></td>';} @endphp
                @php $T9=0;$T10=0;$T11=0;$T12=0;$T13=0;$T14=0;$T15=0;$T16=0;$total=0; @endphp
            </tr>
        @endforeach
    </table>
</div>
{{---------------------------AddNewSchedule----------------------------------------------}}
<div class="modal-content TimeTableCreate" style="font-size: 14px;z-index: 999 !important;outline: 5px solid #08344d; top: 8%; left: 21%; margin: 0px; width: 800px !important; display: none; height: 493px !important; overflow-y: auto !important;">
    <div class="modal-header" style="text-align: center;border-bottom: none; height: auto; background-color: #08334d;color: #fff;display: block;">
        <h4 class="subjectName" style="display: inline-block;"></h4>
        <span onclick="closeTImeTableform()" class="close" style="color: #fff !important; font-size: 35px!important; opacity: 1; text-shadow: 2px -2px 3px #343a40; display: inline-block !important;">X</span>
    </div>

    <div class="modal-body" style="padding: 2%;">
        <div class="form-group form_style">
            <form id="openCourseForm" method="POST" action="/Panel/MidtermSchedule/Add" style="padding: 15px;" >
                @csrf
                <div class="alert alert-info" style="padding: 8px;"></div>
                <input type="hidden" id="AddMidtermSchedule" name="course_id" value="">
                <input type="hidden" id="studentNUm" name="s_number" value="">
                <input type="hidden" name="startDate" value="{{$StartDate_save}}">

                <div class="form-group row">
                    <label class="col-4 col-form-label">Select Instructor</label>
                    <select class="form-control col-8" style="z-index: 9999 !important;" name="teacher_assistant">
                        @php
                            foreach($ta as $teacher)
                            { echo '<option value="'.$teacher->id.'">'.$teacher->user[0]->name_en.'</option>'; }
                        @endphp
                    </select>
                </div>


                <div class="form-group row">
                    <label class="col-4 col-form-label">Select Day</label>
                    <select class="form-control col-8" style="z-index: 9999 !important;" name="day">
                        @php
                            foreach($weekDays as $day)
                            { echo '<option value="'.$day.'">'.$day.'</option>'; }
                        @endphp
                    </select>
                </div>

                <div class="form-group row">
                    <label class="col-4 col-form-label">Select Time</label>
                    <select class="form-control col-8" style="z-index: 9999 !important;" name="Time">
                        <option value="9">From 9   Am: 10  Am</option>
                        <option value="10">From 10 Am : 11 Am</option>
                        <option value="11">From 11 Am : 12 Am</option>
                        <option value="12">From 12 Am : 1  Pm</option>
                        <option value="13">From 1  Pm : 2  Pm</option>
                        <option value="14">From 2  Pm : 3  Pm</option>
                        <option value="15">From 3  Pm : 4  Pm</option>
                        <option value="16">From 4  Pm : 5  Pm</option>
                    </select>
                </div>

                <div class="form-group row removeCOntainer" id="HallPlaces">
                    <label class="col-4 col-form-label onHallLable">Select Hall</label>
                    <select class="form-control col-7 oneHall" id="Hall[]"  style="z-index: 9999 !important;" name="Hall[]">
                        @php
                            foreach($halls as $h)
                            {
                                echo '<option value="'.$h->id.'">'.$h->name.' capacity ( '.$h->seats.'</p> )</option>';
                                echo '<option style="display:none !important;" class="HallSeat" value="'.$h->seats.'" ></option>';
                            }
                        @endphp
                    </select>
                </div>

                <div class="col-1" id="AddHalls"  onclick="addMidtermHalls()" style="float: right;cursor: pointer; margin-right: -20px;margin-top: -65px;display:none;">
                    <i class="fas fa-plus-square" style="font-size: 40px; margin-top: 5px;color: #3c9847;"></i>
                </div>
                <div class="clearfix"></div>
                <button class="btn btn-outline-info btn-block">Add Schedule &nbsp; <i class="fas fa-calendar-alt"></i></button>

            </form>
        </div>
    </div>
</div>
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
    <script src=" {{ asset('/js/frontend/Exam_schedule.js') }}"></script>
@endsection