@extends('/layouts/layout')
{{--start Style--}}
@section('cssStyle')
    <link href="{{ asset('/css/Panel.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/openCourses.css') }}" rel="stylesheet">
@endsection
{{--End Style--}}

{{--start section--}}
@section('content')
    <div class="container-fluid" style="margin: 70px auto 100px;">
        <div class="row justify-content-center">
            <div class="col-md-10 panel-container">
                {{-- Start Panel Profiling --}}
                <div class="PanelProfile">

                    <div class="panel-box-header">
                        <h5 class="header col-xs-4" style="float: left">
                            <span class="headerName"><i class="fa fa-cogs"></i>   Admin Panel  </span>
                        </h5>
                        <div class="col-9" style="float: right;" id="long-panel">
                            <ul class="navitrix" style="text-align: center">

                                <li class="col-lg-3 col-xs-3" style="padding: 0px"><a href="{{ url('/Panel/Admin/Statistics/1') }}">Statistics</a></li>

                                <li class="col-lg-3 col-xs-3" style="padding: 0px"><a href="#">Manage Stuff</a>
                                    <ul class="subs">
                                        @if(Auth::user()->userable_id==20161810)
                                            <li><a href="{{ url('/Panel/Admin') }}">Admins</a></li>
                                        @endif
                                        <li><a href="{{ url('/Panel/Doctor') }}">Doctors</a></li>
                                        <li><a href="{{ url('/Panel/SA') }}">Student Affairs</a></li>
                                        <li><a href="{{ url('/Panel/TA') }}">Teacher Assistants</a></li>
                                    </ul>
                                </li>

                                <li class="col-lg-3 col-xs-3" style="padding: 0px"><a href="#">General</a>
                                    <ul class="subs">
                                        <li><a href="{{ url('/Panel/managePlaces') }}">Manage Places</a></li>
                                        <li><a href="{{ url('/Panel/manageCourses') }}">Manage Courses</a></li>
                                    </ul>
                                </li>

                                <li class="col-lg-3 col-xs-3" style="padding: 0px"><a href="#">Manage Semester</a>
                                    <ul class="subs">
                                        <li><a href="{{ url('/Panel/manageSemester') }}">Manage Semesters</a></li>
                                        <li><a href="{{ url('/Panel/openCourses') }}">Open Courses</a></li>
                                        <li><a href="{{ url('/Panel/TimeTable') }}">Manage Timetable</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        {{--min--}}
                        <div id="short-panel" class="col-7 col-xs-1s">
                            <ul class="navitrix" style="text-align: center">
                                <li class="col-lg-3 col-xs-3" style="padding: 0px"><a href="#">Panel options<i class="fas fa-angle-double-down"></i></a>
                                    <ul class="subs">
                                        @if(Auth::user()->userable_id==20161810)
                                            <li><a href="{{ url('/Panel/Admin') }}">Admins</a></li>
                                        @endif
                                        <li><a href="{{ url('/Panel/Doctor') }}">Doctors</a></li>
                                        <li><a href="{{ url('/Panel/SA') }}">Student Affairs</a></li>
                                        <li><a href="{{ url('/Panel/TA') }}">Teacher Assistants</a></li>

                                        <li><a href="{{ url('/Panel/managePlaces') }}">Manage Places</a></li>
                                        <li><a href="{{ url('/Panel/manageCourses') }}">Manage Courses</a></li>

                                        <li><a href="{{ url('/Panel/manageSemester') }}">Manage Semesters</a></li>
                                        <li><a href="{{ url('/Panel/openCourses') }}">Open Courses</a></li>
                                        <li><a href="{{ url('/Panel/TimeTable') }}">Manage Timetable</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        {{--end min--}}
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
                {{-- End Sucess message --}}

                {{-- Start option View --}}
                <div class="optionView col-12">

                    {{--Manage Admins Pages--}}

                    @if(Request::is('Panel/Admin'))
                        @if(Auth::user()->userable_id==20161810)
                            @include('/Portal/admin_panel/manageAdmins')
                        @endif
                    @endif
                    @if(Request::is('Panel/Admin/*/edit'))  @include('/Portal/admin_panel/updateAdmin') @endif
                    @if(Request::is('Panel/Admin/create'))  @include('/Portal/admin_panel/addAdmin') @endif
                    {{-- Manage S.A Pages--}}
                    @if(Request::is('Panel/SA'))  @include('/Portal/admin_panel/manageSA') @endif
                    @if(Request::is('Panel/SA/*/edit'))  @include('/Portal/admin_panel/updateSA') @endif
                    @if(Request::is('Panel/SA/create'))  @include('/Portal/admin_panel/addSA') @endif
                    {{-- Manage TA Pages--}}
                    @if(Request::is('Panel/TA'))  @include('/Portal/admin_panel/manageTA') @endif
                    @if(Request::is('Panel/TA/*/edit'))  @include('/Portal/admin_panel/updateTA') @endif
                    @if(Request::is('Panel/TA/create'))  @include('/Portal/admin_panel/addTA') @endif
                    {{-- Manage Doctors Pages--}}
                    @if(Request::is('Panel/Doctor'))  @include('/Portal/admin_panel/manageDoctor') @endif
                    @if(Request::is('Panel/Doctor/*/edit'))  @include('/Portal/admin_panel/updateDoctor') @endif
                    @if(Request::is('Panel/Doctor/create'))  @include('/Portal/admin_panel/addDoctor') @endif
                    {{-- Manage courses--}}
                    @if (Request::is('Panel/manageCourses')) @include('/Portal/admin_panel/manageCourses')@endif
                    {{-- Manage places--}}
                    @if(Request::is('Panel/managePlaces'))@include('/Portal/admin_panel/managePlaces')@endif
                    {{-- Manage Semster--}}
                    @if(Request::is('Panel/manageSemester')) @include('/Portal/admin_panel/manageSemester') @endif
                    {{-- Add Places,semster,courses --}}
                    @if (Request::is('Panel/managePlaces/create')) @include('/Portal/admin_panel/addPlace') @endif
                    @if(Request::is('Panel/manageCourses/create')) @include('/Portal/admin_panel/addCourse') @endif
                    @if(Request::is('Panel/manageSemester/create'))@include('/Portal/admin_panel/addSemester') @endif
                    {{-- Update places , semster , courses --}}
                    @if(Request::is('Panel/managePlaces/*/edit')) @include('/Portal/admin_panel/editPlace') @endif
                    @if(Request::is('Panel/manageCourses/*/edit'))@include('/Portal/admin_panel/editCourse') @endif
                    @if(Request::is('Panel/manageSemester/*/edit'))@include('/Portal/admin_panel/editSemester') @endif
                    {{-- Manage open courses --}}
                    @if(Request::is('Panel/openCourses'))@include('/Portal/admin_panel/openedCourses')@endif
                    @if(Request::is('Panel/openCourses/*'))@include('/Portal/admin_panel/openedCourses')@endif
                    {{--Manage TimeTable--}}
                    @if(Request::is('Panel/TimeTable'))@include('/Portal/admin_panel/manageTImeTable')@endif
                    @if(Request::is('Panel/Admin/Statistics/*'))@include('/Portal/admin_panel/adminStatistics')@endif

                </div>

                {{-- End option View --}}
            </div>
        </div>
    </div>
@endsection
