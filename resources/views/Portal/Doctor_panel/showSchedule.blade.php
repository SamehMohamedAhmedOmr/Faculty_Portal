<div class="headerName" style="background-color:#c3cee36e !important;">{{'show Schedule'}} &nbsp; <i class="far fa-calendar-alt" style="color: #2c4167;"></i></div>
<form action="/Panel/Doctor/showSchedule" method="GET">
    @csrf
    <div class="form-row align-items-center">

        <div class="col-9">
            <label class="sr-only" for="inlineFormInputGroup">Select Schedule</label>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <div class="input-group-text">{{'Select Schedule'}}</div>
                </div>
                <select type="text" name="Schedule" class="form-control" id="inlineFormInputGroup">
                    <option>Midterm Exam</option>
                    <option>Practical Exam</option>
                    <option>Final Exam</option>
                </select>
            </div>
        </div>

        <div class="col-3">
            <button type="submit" class="btn btn-block btn-outline-secondary">Show Schedule &nbsp; <i class="fas fa-table"></i></button>
        </div>

    </div>
</form>
@if(isset($show) && $show=='Midterm Exam')
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
                                             echo $subject->name.' &nbsp;&nbsp
                                        </div>
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
                                             echo $subject->name.' &nbsp;&nbsp
                                        </div>
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
                                             echo $subject->name.' &nbsp;&nbsp
                                        </div>
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
                                             echo $subject->name.' &nbsp;&nbsp
                                        </div>
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
                                             echo $subject->name.' &nbsp;&nbsp
                                        </div>
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
                                             echo $subject->name.' &nbsp;&nbsp
                                        </div>
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
                                             echo $subject->name.' &nbsp;&nbsp
                                        </div>
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
                                             echo $subject->name.' &nbsp;&nbsp
                                        </div>
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

@elseif( isset($show) && $show=='Practical Exam')
    {{-------------------------------------ViewPraticalSchedule------------------------------------------}}
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
                    <td class="timetable_table_day" style="width: 100px !important;">{{$day}}<br>{{$practicalTIme}}</td>
                    {{--get All exams in specific day--}}
                    @php
                        $practicalTIme = \Carbon\Carbon::parse($practicalTIme)->addDay(1)->format('Y-m-d');
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
                                             echo $subject->name.' &nbsp;&nbsp
                                        </div>
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
                                             echo $subject->name.' &nbsp;&nbsp
                                        </div>
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
                                             echo $subject->name.' &nbsp;&nbsp
                                        </div>
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
                                             echo $subject->name.' &nbsp;&nbsp
                                        </div>
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
                                             echo $subject->name.' &nbsp;&nbsp
                                        </div>
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
                                             echo $subject->name.' &nbsp;&nbsp
                                        </div>
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
                                             echo $subject->name.' &nbsp;&nbsp
                                        </div>
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
                                             echo $subject->name.' &nbsp;&nbsp
                                        </div>
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


@elseif(isset($show) && $show=='Final Exam')
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
                                             echo '<p style="display:inline-block;">'.$subject->name.'<br>  <b style="color:#cf4545;">From 9 to '.(9+$subject->credit_hours).'&nbsp; <i class="far fa-clock"></i></b></p>
                                        </div>
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
                                             echo '<p style="display:inline-block;">'.$subject->name.'<br>  <b style="color:#cf4545;">From 12 to '.(12+$subject->credit_hours).' &nbsp; <i class="far fa-clock"></i></b></p>
                                             </div>
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
                                             echo '<p style="display:inline-block;">'.$subject->name.'<br>  <b style="color:#cf4545;">From 3 to '.(3+$subject->credit_hours).' &nbsp;<i class="far fa-clock"></i></b></p>
                                            </div>
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
@endif