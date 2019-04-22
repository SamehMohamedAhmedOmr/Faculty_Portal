@extends('/layouts/layout')
{{--start Style--}}
@section('cssStyle')
    <link href="{{ asset('/css/Panel.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/Emails.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
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
                        <h5 class="header col-3" style="float: left">
                            <span class="headerName"><i class="fa fa-cogs"></i> Doctor Panel  </span>
                        </h5>
                        <div class="col-9" style="float: right">
                            <ul class="navitrix">

                                <li><a href="{{ url('/Panel/experience') }}">Manage Experiences</a></li>

                                <li><a href="#">Courses and Grades</a>
                                    <ul class="subs">
                                        <li><a href="{{ url('/Panel/Doctor/ManageCourse') }}">Manage Courses</a></li>
                                        <li><a href="{{ url('/Panel/Doctor/manageCoursesDistribution') }}">Courses Distributions</a></li>
                                        <li><a href="{{ url('/panel/Doctor/ManageFinalGrades') }}">Final Grades</a></li>
                                    </ul>
                                </li>

                                <li><a href="#">Schedules</a>
                                    <ul class="subs">
                                        <li><a href="{{ url('/Panel/timetable/view') }}">Timetable</a></li>
                                        <li><a href="{{ url('/Panel/Doctor/showSchedule') }}">Exams Schedule</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
                {{-- End Panel Profiling --}}

                {{-- Start Success message --}}
                @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                        <i class="fas fa-check-square"></i>
                    </div>
                @endif

                @if(session()->has('Alert'))
                    <div class="optionView col-12">
                        <div class="center-block" style=" margin: 10px;">
                            <div class="clearfix"></div>
                            <span class="timetable_table_title"><i class="fa fa-lock"></i>{{ session()->get('Alert') }}</span>
                        </div>
                    </div>
                @endif
                {{-- End Sucess message --}}

                {{--get errors--}}
                @if ($errors->any())
                    <br>
                    <div>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li class="alert alert-danger">{{ $error }}
                                    <i class="fa fa-times" style="float: right "></i>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Start option View --}}
                <div class="optionView col-12">
                    {{-- Manage Experience Pages--}}
                    @if(Request::is('Panel/experience'))  @include('/Portal/Doctor_panel/experience') @endif
                    @if(Request::is('Panel/experience/create'))  @include('/Portal/Doctor_panel/create_experience') @endif
                    @if(Request::is('Panel/experience/*/edit'))  @include('/Portal/Doctor_panel/update_experience') @endif
                    {{-- View Timetable Page--}}
                    @if(Request::is('Panel/timetable/view'))  @include('/Portal/Doctor_panel/timetable') @endif
                    {{--Control courses distributions which Doctor Lead--}}
                    @if(Request::is('Panel/Doctor/manageCoursesDistribution')) @include('/Portal/Doctor_panel/course_distribution') @endif
                    {{-- Manage Courses --}}
                    @if(Request::is('Panel/Doctor/ManageCourse')) @include('/Portal/Doctor_panel/ManageCourses') @endif
                    {{--show Schedule--}}
                    @if(Request::is('Panel/Doctor/showSchedule'))  @include('/Portal/Doctor_panel/showSchedule') @endif
                    {{-- Manage Final --}}
                    @if(Request::is('panel/Doctor/ManageFinalGrades'))@include('/Portal/Doctor_panel/manageFinal') @endif

                </div>
                {{-- End option View --}}
            </div>
        </div>
    </div>
@endsection
