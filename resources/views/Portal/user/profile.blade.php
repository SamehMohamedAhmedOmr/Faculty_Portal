@extends('/layouts/layout')

{{--start Style--}}
@section('cssStyle')
    <link href="{{ asset('/css/profile.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/form.css') }}" rel="stylesheet">
@endsection
{{--End Style--}}


{{--start section--}}
@section('content')
    <div class="container-fluid" style="margin: 70px auto 100px;">
        <div class="row justify-content-center">
            <div class="col-md-8 profileCard">
                <!--header-->
                <div id="container">
                    <div id="overlayProfileHeader">My Profile <i class="far fa-user-circle"></i></div>
                    <svg width="450" height="100" viewBox="0 0 450 100" style="position: absolute; top: 0;">
                        <defs>
                            <filter id="blur">
                                <feGaussianBlur in="SourceGraphic" stdDeviation="3" />
                            </filter>
                        </defs>
                    </svg>
                </div>
                <!-- ENd header -->

                <div class="clearfix"></div>

                {{--add new user form--}}
                <div class="form-group formContent" style="margin:20px;">

                {{--Display errors--}}
                @if ($errors->any())
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
                {{-- End Displaying Error--}}

                {{-- Start Success message --}}
                @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                        <i class="fas fa-check-square"></i>
                    </div>
                @endif
                {{-- End Sucess message --}}

                <form action="/profile" class="form-group" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label for="id" class="col-2 col-form-label profFormlbl">ID Number :</label>
                        <input type="text" class=" profFormInp form-control col-10" value="{{ $user->userable_id }}" name="id" readonly>
                    </div>

                    <div class="form-group row">
                        <label for="national_id" class="col-2 col-form-label profFormlbl" >National ID:</label>
                        <input type="text" class="profFormInp form-control col-10" value="{{ $user->national_id }}" name="national_id" readonly>
                    </div>

                    <div class="form-group row">
                        <label for="name_ar" class="col-2 col-form-label profFormlbl">Arabic Name:</label>
                        <input type="text" class="profFormInp form-control col-10" value="{{ $user->name_ar }}" name="name_ar" readonly>
                    </div>

                    <div class="form-group row">
                        <label for="name_en" class="col-2 col-form-label profFormlbl">English Name:</label>
                        <input type="text" class="profFormInp form-control col-10" value="{{ $user->name_en }}" name="name_en" readonly>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-2 col-form-label profFormlbl">Email:</label>
                        <input type="text" class="profFormInp form-control col-10" value="{{ $user->email }}" name="email">
                    </div>

                    <div class="form-group row">
                        <label for="gender" class="col-2 col-form-label profFormlbl">Gender:</label>
                        <input type="text" class="profFormInp form-control col-10" value="@if($user->gender==0){{ 'Male' }} @else{{ 'Female' }}@endif" name="gender" readonly>
                    </div>
                    <div class="form-group row">
                        <label for="DOB" class="col-2 col-form-label profFormlbl">Date of birth:</label>
                        <input type="date" class="profFormInp form-control col-10" name="DOB" value="{{ $user->DOB }}" readonly>
                    </div>
                    <div class="form-group row">
                        <label for="phone" class="col-2 col-form-label profFormlbl">Phone:</label>
                        <input type="text" class="profFormInp form-control col-10" name="phone" value="{{ $user->phone }}">
                    </div>
                    <div class="form-group row">
                        <label for="address" class="col-2 col-form-label profFormlbl">Address:</label>
                        <input type="text" class="profFormInp form-control col-10" name="address" value="{{ $user->address }}">
                    </div>

                    {{--------------------------------Attributes for STUDENT Only--------------------------------}}
                    @if($user->userable_type=='Stu')
                        <div class="form-group row">
                            <label for="department_id" class="col-2 col-form-label profFormlbl">Department:</label>
                            <input type="text" class="profFormInp form-control col-10" name="department_id" value="{{ $SpecializationAttibutes->department->name }}" readonly>
                        </div>
                        <div class="form-group row">
                            <label for="graduated_status" class="col-2 col-form-label profFormlbl">Graduated Status:</label>
                            <input type="text" class="profFormInp form-control col-10" name="graduated_status" value="{{ $SpecializationAttibutes->graduated_status }}" readonly>
                        </div>

                    @endif
                    {{--------------------------------Attribute for Staff--------------------------------}}
                    @if($user->userable_type!='Stu')
                        <div class="form-group row">
                            <label for="hire_date" class="col-2 col-form-label profFormlbl">Hire date:</label>
                            <input type="date" class="profFormInp form-control col-10" name="hire_date" value="{{ $SpecializationAttibutes->hire_date }}" readonly>
                        </div>
                    @endif
                    {{--------------------------------Attribute for Admin--------------------------------}}
                    @if($user->userable_type=='Adm')
                        <div class="form-group row">
                            <label for="working_hours" class="col-2 col-form-label profFormlbl">Working Hours:</label>
                            <input type="text" class="profFormInp form-control col-10" name="working_hours" value="{{ $SpecializationAttibutes->working_hours }}" readonly>
                        </div>
                    @endif
                    {{--------------------------------Attribute for S_A--------------------------------}}
                    @if($user->userable_type=='S_A')
                        <div class="form-group row">
                            <label for="degree" class="col-2 col-form-label profFormlbl">Degree:</label>
                            <input type="text" class="profFormInp form-control col-10" name="degree" value="{{ $SpecializationAttibutes->degree }}" readonly>
                        </div>
                    @endif
                    <br>
                    <button class="btn btn-block btn-light profSubmBtn" type="submit">Save <i class="far fa-edit"></i> </button>
                </form>
            </div>
            <br>
        </div>
    </div>
</div>
@endsection
{{--end section--}}
