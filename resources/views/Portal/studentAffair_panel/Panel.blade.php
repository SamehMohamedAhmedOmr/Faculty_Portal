@extends('/layouts/layout')
{{--start Style--}}
@section('cssStyle')
    <link href="{{ asset('/css/Panel.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/manageRegistrations.css') }}" rel="stylesheet">

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
                            <span class="headerName"><i class="fa fa-cogs"></i> Student Affair Panel  </span>
                        </h5>
                        <div class="col-7" style="float: right">
                            <ul class="navitrix">

                                <li><a href="{{ url('/Panel/Student') }}">Manage Students</a></li>
                                <li><a href="{{ url('/Panel/registrations') }}">Manage Registrations</a></li>

                                <li><a href="#">Schedules</a>
                                    <ul class="subs">
                                        <li><a href="{{ url('/Panel/MidtermSchedule') }}">Midterm Schedule</a></li>
                                        <li><a href="{{ url('/Panel/Practical') }}">Practical Schedule</a></li>
                                        <li><a href="{{ url('/Panel/FinalSchedule') }}">Final Schedule</a></li>
                                    </ul>
                                </li>
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

                @if(session()->has('Alert'))
                    <div class="optionView col-12">
                        <div class="center-block" style=" margin: 10px;">
                            <div class="clearfix"></div>
                            <span class="timetable_table_title"><i class="fa fa-lock"></i>{{ session()->get('Alert') }}</span>
                        </div>
                    </div>
                @endif

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
                    {{-- Manage Student --}}
                    @if(Request::is('Panel/Student'))  @include('/Portal/studentAffair_panel/manageStudent') @endif
                    @if(Request::is('Panel/Student/create'))  @include('/Portal/studentAffair_panel/AddStudent') @endif
                    @if(Request::is('Panel/Student/*/edit'))  @include('/Portal/studentAffair_panel/editStudents') @endif
                    @if(Request::is('Panel/registrations'))  @include('/Portal/studentAffair_panel/manageRegistration') @endif
                    @if(Request::is('Panel/newRegistration/*'))  @include('/Portal/studentAffair_panel/studentRegistration') @endif

                    @if(Request::is('Panel/MidtermSchedule'))  @include('/Portal/studentAffair_panel/MidtermSchedule') @endif
                    @if(Request::is('Panel/Practical'))  @include('/Portal/studentAffair_panel/PracticalSchedule') @endif
                    @if(Request::is('Panel/FinalSchedule'))  @include('/Portal/studentAffair_panel/FinalSchedule') @endif
                </div>
                {{-- End option View --}}
            </div>
        </div>
    </div>
@endsection
