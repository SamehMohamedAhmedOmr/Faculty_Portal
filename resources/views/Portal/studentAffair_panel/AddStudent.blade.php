
    <div class="headerName"> Add New Student  <i class="fas fa-user-plus"></i> </div>
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

    <form action="/Panel/Student" class="form-group" style="padding:36px;background-color: #f7f7f7;" method="POST">
        @csrf
        <div class="form-group row">
            <label for="national_id" class="col-2 col-form-label">National ID:</label>
            <input type="text" class="form-control col-10" name="national_id" value="{{ old('national_id') }}">
        </div>

        <div class="form-group row">
            <label for="name_ar" class="col-2 col-form-label">Arabic Name:</label>
            <input type="text" class="form-control col-10" name="name_ar" value="{{ old('name_ar') }}">
        </div>

        <div class="form-group row">
            <label for="name_en" class="col-2 col-form-label">English Name:</label>
            <input type="text" class="form-control col-10" name="name_en" value="{{ old('name_en') }}">
        </div>

        <div class="form-group row">
            <label for="email" class="col-2 col-form-label">Email:</label>
            <input type="text" class="form-control col-10" name="email" value="{{ old('email') }}">
        </div>

        <div class="form-group row">
            <label for="password" class="col-2 col-form-label">Password:</label>
            <input type="password" class="form-control col-10" name="password" value="{{ old('password') }}">
        </div>

        <div class="form-group row">
            <label for="gender" class="col-2 col-form-label">Gender:</label>
            <div class="col-5">
                <label class="radio-inline" ><input type="radio" value="0" name="gender" @if(old('gender')==0){{ 'checked="checked"' }} @endif> Male</label>
            </div>

            <div class="col-5">
                <label class="radio-inline" ><input type="radio" value="1" name="gender" @if(old('gender')==1){{ 'checked="checked"' }} @endif> Female</label>
            </div>
        </div>

        <div class="form-group row">
            <label for="DOB" class="col-2 col-form-label">Date of birth:</label>
            <input type="date" class="form-control col-10" name="DOB" value="{{ old('DOB') }}">
        </div>

        <div class="form-group row">
            <label for="phone" class="col-2 col-form-label">Phone:</label>
            <input type="text" class="form-control col-10" name="phone" value="{{ old('phone') }}">
        </div>

        <div class="form-group row">
            <label for="address" class="col-2 col-form-label">Address:</label>
            <input type="text" class="form-control col-10" name="address" value="{{ old('address') }}">
        </div>

        {{--<div class="form-group row">--}}
            {{--<label for="department_id" class="col-2 col-form-label">Department:</label>--}}
            {{--<select class="form-control col-10" name="department_id" value="{{ old('department_id') }}">--}}
                {{--@foreach($departments as $dep)--}}
                    {{--<option value="{{$dep->id}}"> {{$dep->name}} </option>--}}
                {{--@endforeach--}}
            {{--</select>--}}
        {{--</div>--}}

        {{--<div class="form-group row">--}}
            {{--<label for="gender" class="col-2 col-form-label">Status:</label>--}}
            {{--<div class="col-5">--}}
                {{--<label class="radio-inline"><input type="radio" value="1" name="account_status" @if(old('account_status')==1){{ 'checked="checked"' }} @endif> Activate Account</label>--}}
            {{--</div>--}}
            {{--<div class="col-5">--}}
                {{--<label class="radio-inline"><input type="radio" value="0" name="account_status" @if(old('account_status')==0){{ 'checked="checked"' }} @endif> Deactivate Account</label>--}}
            {{--</div>--}}
        {{--</div>--}}

        <div class="col-12">
            <button class="btn btn-outline-dark btn-block" type="submit">Add</button>
        </div>
    </form>
</div>
