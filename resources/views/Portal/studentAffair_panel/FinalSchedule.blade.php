<div class="headerName" style="background-color:#c3cee36e !important;">{{'Final Schedule'}} &nbsp; <i class="far fa-calendar-alt" style="color: #2c4167;"></i></div>
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
{{----------------------------------------All Courses------------------------------------------------------}}
@if($canEdit==1)
    <div class="optionView col-12">
        <br>
        <div class="card" style="margin-bottom: 30px;">
            <div class="panel panel-danger" style="padding-top: 0;border: 3px solid #2c416785;padding-bottom: 10px;">
                <div class="headerName" style="background-color: #c3cee36e;">{{'Select open Courses To Add in Final Schedule'}} &nbsp;&nbsp;<i class="fas fa-graduation-cap"></i></div>
                <div class="panel-body customBody row" style="padding: 10px 25px;">
                    @foreach($subjects as $sbj)
                        <div class="col-xs-4" style="margin: 4px 3px;">
                            @php
                                $TImeTableIDs_in_course = $sbj->subject->timetables->pluck('id');
                                $AllStudents_in_course =  DB::table('student_timetable')->whereIn('timetable_id',$TImeTableIDs_in_course)->count();
                            @endphp
                            <a id="openCourse" onclick="AddFinalExam(@php echo'\''.$sbj->subject->name.'\','.$sbj->subject_id.','.$AllStudents_in_course.','.$sbj->subject->credit_hours; @endphp )" class="btn customCoursesAdding" style="font-size: 15px;padding-left: 15px !important;padding-right:5px !important;background-color: #7996ca59;color: #fff;font-weight: bolder;text-shadow: 1px 1px 1px #000, -1px -1px 1px #2c4167;" data-toggle="model" data-target="openCourseModal">
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

{{-------------------------------------ViewFinalSchedule------------------------------------------}}
<div style="overflow-x: auto;">
    <table class="timetable_table table table-bordered timetable">
        <thead>
        <tr>
            <th>Date</th>
            <th>First Exam</th>
            <th>Second Exam</th>
            <th>Third Exam</th>
        </tr>
        </thead>
        <tbody>
            @foreach($FinalExam_Times as $date)
            <tr>
                @php
                    $T9=0;$T12=0;$T15=0;$total=0;
                    $Exams = $allExamsSchedule->where('date',$date[0]->date);
                    $dayName=\Carbon\Carbon::parse($date[0]->date);
                @endphp
                <td class="timetable_table_day" style="width: 130px;">{{$date[0]->date}}<br>{{$dayName->format('l')}}</td>
                @foreach($Exams as $exam)
                    @if($exam->time==9)
                        @php
                            if($T9==0){ echo '<td>'; $T9++; $total++;}
                                echo
                                '<div class="set row col-12">
                                    <div class="col-12" style="padding: 2px;">';$subject=\App\Subject::find($exam->subject_id);
                                         echo '<p style="display:inline-block;">'.$subject->name.'<br>  <b style="color:#cf4545;">From 9 to '.(9+$subject->credit_hours).'&nbsp; <i class="far fa-clock"></i></b></p>';
                                         if($canEdit==1)
                                         {
                                            echo
                                            '<form method="post" action="/Panel/FinalSchedule/remove" style="display: inline-block;float:right;" >
                                                <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'">
                                                <input type="hidden" name="Semester_id" value="'.$exam->semester_id.'">
                                                <input type="hidden" name="subject_id" value="'.$exam->subject_id.'">
                                                <button class="btn" onclick="deleteMIdtermSchedule(this); return false;" >
                                                <i class="fas fa-times-circle deleteOPenCourse"></i>
                                                </button>
                                            </form>';
                                          }
                                    echo
                                    '</div>
                                    <div class="col-8 teach">'.$exam->ta->user[0]->name_en.'</div>
                                    <div class="col-4 place">'.$exam->place->name.'</div>
                                </div>';
                        @endphp
                    @endif

                    @if($exam->time==12)
                        @php
                            if($T9==0){echo '<td></td>'; $total++; $T9++;}
                            if($T12==0){ echo '<td>'; $T12++; $total++;}
                            echo
                                '<div class="set row col-12">
                                    <div class="col-12" style="padding: 2px;">';$subject=\App\Subject::find($exam->subject_id);
                                         echo '<p style="display:inline-block;">'.$subject->name.'<br>  <b style="color:#cf4545;">From 12 to '.(12+$subject->credit_hours).' &nbsp; <i class="far fa-clock"></i></b></p>';
                                         if($canEdit==1)
                                         {
                                            echo
                                            '<form method="post" action="/Panel/FinalSchedule/remove" style="display: inline-block;float:right;" >
                                                <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'">
                                                <input type="hidden" name="Semester_id" value="'.$exam->semester_id.'">
                                                <input type="hidden" name="subject_id" value="'.$exam->subject_id.'">
                                                <button class="btn" onclick="deleteMIdtermSchedule(this); return false;" >
                                                <i class="fas fa-times-circle deleteOPenCourse"></i>
                                                </button>
                                            </form>';
                                          }
                                    echo
                                    '</div>
                                    <div class="col-8 teach">'.$exam->ta->user[0]->name_en.'</div>
                                    <div class="col-4 place">'.$exam->place->name.'</div>
                                </div>';
                        @endphp
                    @endif

                    @if($exam->time==15)
                        @php
                            if($T9==0){echo '<td></td>';$total++;$T9++;}
                            if($T12==0){echo '<td></td>';$total++;$T12++;}
                            if($T15==0){ echo '<td>';$T15++;$total++;}
                            echo
                                '<div class="set row col-12">
                                    <div class="col-12" style="padding: 2px;">';$subject=\App\Subject::find($exam->subject_id);
                                         echo '<p style="display:inline-block;">'.$subject->name.'<br>  <b style="color:#cf4545;">From 3 to '.(3+$subject->credit_hours).' &nbsp;<i class="far fa-clock"></i></b></p>';
                                         if($canEdit==1)
                                         {
                                            echo
                                            '<form method="post" action="/Panel/FinalSchedule/remove" style="display: inline-block;float:right;" >
                                                <input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'">
                                                <input type="hidden" name="Semester_id" value="'.$exam->semester_id.'">
                                                <input type="hidden" name="subject_id" value="'.$exam->subject_id.'">
                                                <button class="btn" onclick="deleteMIdtermSchedule(this); return false;" >
                                                <i class="fas fa-times-circle deleteOPenCourse"></i>
                                                </button>
                                            </form>';
                                          }
                                    echo
                                    '</div>
                                    <div class="col-8 teach">'.$exam->ta->user[0]->name_en.'</div>
                                    <div class="col-4 place">'.$exam->place->name.'</div>
                                </div>';
                        @endphp
                    @endif
                @endforeach
                @php
                    $T9=0;$T12=0;$T15=0;
                @endphp
            </tr>
            @endforeach
        </tbody>
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
            <form id="openCourseForm" method="POST" action="/Panel/FinalSchedule/Add" style="padding: 15px;" >
                @csrf
                <div class="alert alert-info" style="padding: 8px;"></div>
                <input type="hidden" id="AddFinalSchedule" name="course_id" value="">
                <input type="hidden" id="studentNUm" name="s_number" value="">
                <input type="hidden"  name="Start_date" value="{{$Final_Start_Time}}">
                <input type="hidden"  name="end_date" value="{{$Final_end_Time}}">
                <input type="hidden" name="subject_hours" id="subject_hours" value="">

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
                    <input type="date" name="date" class="form-control col-8" min="{{$Final_Start_Time}}" max="{{$Final_end_Time}}">
                </div>

                <div class="form-group row">
                    <label class="col-4 col-form-label">Select Exam Block</label>
                    <select class="form-control col-8" style="z-index: 9999 !important;" name="Time">
                        <option value="9">   9   Am : 12  Pm (First Block)</option>
                        <option value="12">  12   Pm : 3 Pm (Second Block)</option>
                        <option value="15">   3   Pm : 6 Pm (Third Block)</option>
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

                <div class="col-1" id="AddHalls"  onclick="addFinalHalls()" style="float: right;cursor: pointer; margin-right: -20px;margin-top: -65px;display:none;">
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