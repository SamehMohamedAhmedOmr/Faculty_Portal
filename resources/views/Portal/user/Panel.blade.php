@extends('/layouts/layout')

{{--start Style--}}
@section('cssStyle')
    <link href="{{ asset('/css/Panel.css') }}" rel="stylesheet">
@endsection
{{--start Style--}}

{{--start section--}}
@section('content')

    <div class="container-fluid" style="margin: 70px auto 100px;">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="PanelProfile">
                    <h5 class="header">
                        <span class="headerName">
                            @if(Auth::user()->userable_type=='Stu')
                                {{ 'Student Panel' }}
                            @elseif(Auth::user()->userable_type=='Doc')
                                {{ 'Professor Panel'}}
                            @elseif(Auth::user()->userable_type=='T_A')
                                {{ 'Teacher Assistant Panel'}}
                            @elseif(Auth::user()->userable_type=='Adm')
                                {{ 'Admin DashBoard'}}
                            @elseif(Auth::user()->userable_type=='S_A')
                                {{ 'Student Affair Panel'}}
                            @endif
                        </span>
                    </h5>
                    <div class="panel col-12">

                        @if(Auth::user()->userable_type=='Adm')
                            @if(Auth::user()->userable_id==20161810)
                                <div class="option col-3">
                                    <a class="" href="{{ url('/Panel/manage_admins') }}">Manage Admins</a>
                                </div>
                            @endif
                            <div class="option col-3">
                                <a class="" href="{{ url('/Panel/manage_student_affairs') }}">Manage S_As</a>
                            </div>
                            <div class="option col-3">
                                <a class="" href="{{ url('/Panel/manage_teacher_assistants') }}">Manage T_As</a>
                            </div>
                            <div class="option col-3">
                                <a class="" href="{{ url('/Panel/manage_doctors') }}">Manage Doctors</a>
                            </div>
                        @endif

                        @if(Auth::user()->userable_type=='S_A')
                            <div class="option col-3">
                                <a class="" href="{{ url('/Panel/manage_students') }}">Manage Students</a>
                            </div>
                        @endif

                            @if(Auth::user()->userable_type=='T_A' || Auth::user()->userable_type=='Doc')
                                <div class="option col-3">
                                    <a class="" href="{{ url('/Panel/experiences') }}">Experiences</a>
                                </div>
                            @endif

                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
{{--end section--}}
