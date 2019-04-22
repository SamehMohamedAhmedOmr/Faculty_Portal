<div class="headerName">{{ 'Manage Courses Distributions' }} &nbsp; <i class="fas fa-puzzle-piece"></i></div>
@if($semesterAvilable=='true')
    @if(count($courses)>0)
        @foreach($courses as $key=>$course)
            <p>
                <button class="btn btn-block btn-outline-dark" type="button" data-toggle="collapse" data-target="#{{$key}}" aria-expanded="false" aria-controls="{{$key}}">
                    {{  $course->subject->name }}
                </button>
            </p>
            <div class="collapse" id="{{$key}}">
                <div class="card card-body" style="margin: 20px auto; padding: 20px;">
                    <h6 style="text-align: center">Manage {{ $course->subject->name }} Coursework grades</h6><br>
                    @php
                     $grades= $course->subject->grade_distributions->where('semester_id',$MaxSemesterID)->first();
                    @endphp
                    <form method="POST" action="/Panel/Doctor/manageCoursesDistribution">
                        @csrf
                        <div class="alert alert-info">
                            * Note :  <b style="color: #f00;">{{$course->subject->name}}</b> has <b style="color: #f00;">{{ (100-$course->subject->final_grade) }}</b> Coursework grades <br>
                            * Note : Midterm is <b style="color: #f00;">Mandatory</b> and must have at least <b style="color: #f00;">10 grades </b> and Maximum <b style="color: #f00;">{{ 100-$course->subject->final_grade }} grades </b>
                        </div>
                        <input type="hidden" name="subject_id" value="{{ $course->subject_id  }}">
                        <input type="hidden" name="semester_id" value="{{ $course->semester_id }}">
                        <input type="hidden" name="AvilableGrades" value="{{ (100-$course->subject->final_grade) }}">
                        <div class="form-group row">

                            <div class="input-group col-sm-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="SectionGradeDist">Section Grade</span>
                                </div>
                                <input style="text-align: right" min="0" max="{{ (100-$course->subject->final_grade)-10 }}" step="1" type="number" value="@if($grades['section']==''){{'0'}}@else{{$grades['section']}}@endif" name="section" aria-describedby="SectionGradeDist" class="form-control" id="inputSection" placeholder="pls enter Section Grade">
                            </div>

                            <div class="input-group col-sm-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="QuizGradeDist">Quiz Grade</span>
                                </div>
                                <input style="text-align: right" min="0" max="{{ (100-$course->subject->final_grade)-10 }}" step="1" type="number" value="@if($grades['quiz_grade']==''){{'0'}}@else{{$grades['quiz_grade']}}@endif" name="quiz" aria-describedby="QuizGradeDist" class="form-control" id="inputQuiz" placeholder="pls enter Quiz Grade">
                            </div>

                            <div class="input-group col-sm-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="AssignmentGradeDist">assignment Grade</span>
                                </div>
                                <input style="text-align: right" type="number" min="0" max="{{ (100-$course->subject->final_grade)-10 }}" step="1" value="@if($grades['assignment']==''){{'0'}}@else{{$grades['assignment']}}@endif" aria-describedby="AssignmentGradeDist" name="Assignment" class="form-control" id="inputAssignment" placeholder="pls enter Assignment Grade">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="input-group col-sm-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="ProjectGradeDist">Project Grade</span>
                                </div>
                                <input style="text-align: right" type="number" min="0" max="{{ (100-$course->subject->final_grade)-10 }}" step="1" value="@if($grades['project']==''){{'0'}}@else{{$grades['project']}}@endif" aria-describedby="ProjectGradeDist"  name="Project" class="form-control" id="inputProject" placeholder="pls enter Project Grade">
                            </div>

                            <div class="input-group col-sm-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="ParticipationGradeDist">Participation Grade</span>
                                </div>
                                <input style="text-align: right" type="number" min="0" max="{{ (100-$course->subject->final_grade)-10 }}" step="1" value="@if($grades['participation']==''){{'0'}}@else{{$grades['participation']}}@endif" aria-describedby="ParticipationGradeDist" name="Participation" class="form-control" id="inputParticipation" placeholder="pls enter Participation Grade">
                            </div>

                            <div class="input-group col-sm-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="AttendanceGradeDist">Attendance Grade</span>
                                </div>
                                <input style="text-align: right" type="number" min="0" max="{{ (100-$course->subject->final_grade)-10 }}" step="1" value="@if($grades['attendance']==''){{'0'}}@else{{$grades['attendance']}}@endif" aria-describedby="AttendanceGradeDist" name="Attendance" class="form-control" id="inputAttendance" placeholder="pls enter Attendance Grade">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="input-group col-sm-6">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="MidtermGradeDist">Midterm Exam Grade</span>
                                </div>
                                <input style="text-align: center" min="10" max="{{ (100-$course->subject->final_grade) }}" step="1" type="number" value="@if($grades['midterm']==''){{'0'}}@else{{$grades['midterm']}}@endif" class="form-control" aria-describedby="MidtermGradeDist" name="Midterm" id="inputMidterm" placeholder="pls enter Midterm Grade">
                            </div>

                            <div class="input-group col-sm-6">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="FinalGradeDist">Final Exam Grade</span>
                                </div>
                                <input  style="text-align: center" type="number" disabled value="{{ $course->subject->final_grade }}" name="Final" aria-describedby="FinalGradeDist" class="form-control" id="inputFinal" placeholder="pls enter Final Grade">
                            </div>
                        </div>
                        <button class="btn btn-block btn-outline-info">Update Grades <i class="fas fa-graduation-cap"></i> </button>
                    </form>
                </div>
            </div>
        @endforeach
    @else
        <div class="optionView col-12">
            <div class="center-block" style=" margin: 10px;">
                <div class="clearfix"></div>
                <span class="timetable_table_title"><i class="fa fa-lock"></i>You didn't have any courses to lead this semester</span>
            </div>
        </div>
    @endif
@else
    <div class="optionView col-12">
        <div class="center-block" style=" margin: 10px;">
            <div class="clearfix"></div>
            <span class="timetable_table_title"><i class="fa fa-lock"></i>This page not available until the end of registration</span>
        </div>
    </div>
@endif