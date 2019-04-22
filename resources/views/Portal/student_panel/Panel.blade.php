@extends('/layouts/layout')
{{--start Style--}}
@section('cssStyle')
    <link href="{{ asset('/css/Panel.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/course_evaluate.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/Transcript.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/studentClassRoom.css') }}" rel="stylesheet">
@endsection
{{--End Style--}}

{{--start section--}}
@section('content')
    <div class="container-fluid" style="margin: 70px auto 100px;">
        <div class="row justify-content-center">
            <div class="col-md-10">
                {{-- Start Panel Profiling --}}
                <div class="PanelProfile">
                    <div class="panel-box-header col-12">
                        <h5 class="header col-4" style="float: left">
                            <span class="headerName"><i class="fa fa-cogs"></i> Student Panel  </span>
                        </h5>
                        <div class="col-6" style="float: right">
                            <ul class="navitrix">
                                @if(Auth::user()->userable->graduated_status != 0)
                                    <li><a href="{{ url('/Panel/Student/Classroom') }}">Class Room</a></li>

                                    <li><a href="#">Courses</a>
                                        <ul class="subs">
                                            <li><a href="{{ url('/Panel/registerCourses') }}">Registration</a></li>
                                            <li><a href="{{ url('/Panel/Student/EvaluateCourses') }}">Evaluate Courses</a></li>
                                        </ul>
                                    </li>

                                    <li><a href="#">Schedules</a>
                                        <ul class="subs">
                                            <li><a href="{{ url('/Panel/timetable/view') }}">Timetable</a></li>
                                            <li><a href="{{ url('/Panel/student/showSchedule') }}">Exams Schedule</a></li>
                                        </ul>
                                    </li>
                                @endif
                                <li><a href="{{ url('/Panel/Student/Transcript') }}">Transcript</a></li>
                            </ul>
                        </div>
                    </div>

                </div>
                {{-- End Panel Profiling --}}

                {{--get errors--}}
                @if ($errors->any())
                    <div>
                        <br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li class="alert alert-danger">{{ $error }}
                                    <i class="fa fa-times" style="float: right "></i>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Start Success message --}}
                @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                        <i class="fas fa-check-square"></i>
                    </div>
                @endif

                {{-- Start Alert message --}}
                @if(session()->has('Alert'))
                    <div class="optionView col-12">
                        <div class="center-block" style=" margin: 10px;">
                            <div class="clearfix"></div>
                            <span class="timetable_table_title"><i class="fa fa-lock"></i>{{ session()->get('Alert') }}</span>
                        </div>
                    </div>
                @endif

                @if(session()->has('RegisterSuccessfully'))
                    <div class="alert alert-success">
                        {{session()->pull('RegisterSuccessfully', '')}}
                        <i class="fa fa-times" style="float: right "></i>
                    </div>
                @endif
                {{-- End Sucess message --}}

                {{-- Start option View --}}
                <div class="optionView col-12">
                    {{-- View Timetable Page--}}
                    @if(Request::is('Panel/timetable/view'))  @include('/Portal/student_panel/timetable') @endif
                </div>
                {{-- view Register COurses Page --}}
                <div class="optionView col-12">
                    @if(Request::is('Panel/registerCourses'))  @include('/Portal/student_panel/registerCourses') @endif
                </div>
                {{-- Evaluate Courses --}}
                <div class="optionView col-12">
                    @if(Request::is('Panel/Student/EvaluateCourses'))  @include('/Portal/student_panel/evaluate_course') @endif
                </div>
                {{-- End option View --}}

                {{-- Evaluate Courses --}}
                <div class="optionView col-12">
                    @if(Request::is('Panel/student/showSchedule'))  @include('/Portal/student_panel/showSchedule') @endif
                </div>

                {{-- Show Class  Room --}}
                @if(Request::is('Panel/Student/Classroom'))  @include('/Portal/student_panel/StudentClassRoom') @endif
                {{-- Show specific Class  Room --}}
                @if(Request::is('Panel/Student/Classroom/*'))  @include('/Portal/student_panel/StudentClassRoom') @endif
                {{-- End option View --}}

                {{-- Show Transcript --}}
                @if(Request::is('Panel/Student/Transcript'))  @include('/Portal/student_panel/Transcript') @endif
                {{-- End option View --}}

            </div>
        </div>
    </div>
@endsection
