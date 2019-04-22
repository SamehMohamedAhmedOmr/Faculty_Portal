<div class="headerName"> update Student Affair {{ $basics[0]->name_ar }}'s profile </div>

{{--get errors--}}
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

<div class="form-group form_style">
    <form id="" action="/Panel/SA/{{ $user->id }}"  class="form-group" method="Post">
        @method('PATCH')
        @csrf
        {{--{{ method_field('PUT') }}--}}

        <div class="form-group row">
            <label for="NationalID-input" class="col-2 col-form-label">National ID </label>
            <div class="col-10">
                <input type="text" class="form-control" value="{{ $basics[0]->national_id }}" name="national_id" id="NationalID-input" readonly>
            </div>
        </div>


        <div class="form-group row">
            <label for="AR-input" class="col-2 col-form-label">Arabic Name </label>
            <div class="col-10">
                <input type="text" class="form-control input-sm" value="{{ $basics[0]->name_ar }}" name="name_ar" id="AR-input" >
            </div>
        </div>

        <div class="form-group row">
            <label for="name_en" class="col-2 col-form-label">English Name:</label>
            <div class="col-10">
                <input type="text" class="form-control input-sm" value="{{ $basics[0]->name_en }}" name="name_en" >
            </div>
        </div>

        <div class="form-group row">
            <label for="email" class="col-2 col-form-label">Email:</label>
            <div class="col-10">
                <input type="text" class="form-control input-sm" value="{{ $basics[0]->email }}" name="email" >
            </div>
        </div>

        <div class="form-group row">
            <label for="gender" class="col-2 col-form-label" >Gender:</label>
            <div class="col-5">
                <label class="radio-inline" ><input  type="radio" value="0" name="gender" @if($basics[0]->gender==0){{ 'checked' }} @endif> Male</label>
            </div>
            <div class="col-5">
                <label class="radio-inline" ><input type="radio" value="1" name="gender" @if($basics[0]->gender==1){{ 'checked' }} @endif> Female</label>
            </div>
        </div>

        <div class="form-group row">
            <label for="DOB" class="col-2 col-form-label">Date of birth:</label>
            <div class="col-10">
                <input type="date" class="form-control input-sm" name="DOB" value="{{ $basics[0]->DOB }}" >
            </div>
        </div>

        <div class="form-group row">
            <label for="phone" class="col-2 col-form-label">Phone:</label>
            <div class="col-10">
                <input type="text" class="form-control input-sm" name="phone" value="{{ $basics[0]->phone }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="address" class="col-2 col-form-label">Address:</label>
            <div class="col-10">
                <input type="text" class="form-control input-sm" name="address" value="{{ $basics[0]->address }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="hire_date" class="col-2 col-form-label" >Hire date:</label>
            <div class="col-10">
                <input type="date" class="form-control input-sm" name="hire_date" value="{{ $user->hire_date }}" required readonly>
            </div>
        </div>

        <div class="form-group row">
            <label for="degree" class="col-2 col-form-label">Degree:</label>
            <div class="col-10">
                <input type="text" class="form-control input-sm" name="degree" value="{{ $user->degree }}" required>
            </div>
        </div>

        <button class="btn btn-block btn-info" type="submit">Edit</button>
    </form>
</div>