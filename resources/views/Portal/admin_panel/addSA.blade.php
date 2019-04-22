<div class="headerName"> Add New Student Affair </div>

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

{{--add new user form--}}
<div class="form-group form_style">
    <form action="{{'/Panel/SA'}}" class="form-group" method="POST">
        @method('POST')
        @csrf

        <div class="form-group row">
            <label for="national_id" class="col-2 col-form-label">National ID:</label>
            <div class="col-10">
                <input
                        type="text"
                        pattern="[0-9]{14}"
                        required class="form-control input-sm"
                        value="{{ old('national_id') }}"
                        name="national_id"
                        title="national id consist of 14 number only">
            </div>
        </div>

        <div class="form-group row">
            <label for="name_ar" class="col-2 col-form-label">Arabic Name:</label>
            <div class="col-10">
                <input
                        type="text"
                        required
                        class="form-control input-sm"
                        value="{{ old('name_ar') }}"
                        name="name_ar">
            </div>
        </div>

        <div class="form-group row">
            <label for="name_en" class="col-2 col-form-label">English Name:</label>
            <div class="col-10">
                <input
                        type="text"
                        pattern="[A-Za-z\s]{10,100}"
                        title="valid english name between (10-100) character"
                        required class="form-control input-sm"
                        value="{{ old('name_en') }}"
                        name="name_en">
            </div>
        </div>

        <div class="form-group row">
            <label for="email" class="col-2 col-form-label">Email:</label>
            <div class="col-10">
                <input
                        type="text"
                        pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"
                        title="pls enter valid name ex: XXXX@xxx.xx"
                        required
                        class="form-control input-sm"
                        value="{{ old('email') }}"
                        name="email">
            </div>
        </div>

        <div class="form-group row">
            <label for="password" class="col-2 col-form-label">Password:</label>
            <div class="col-10">
                <input
                        type="password"
                        required
                        class="form-control input-sm"
                        value="{{ old('password') }}"
                        name="password">
            </div>
        </div>

        <div class="form-group row">
            <label for="gender" class="col-2 col-form-label">Gender:</label>
            <div class="col-5">
                <label class="radio-inline"><input type="radio" value="0" name="gender" @if(old('gender')==0){{ 'checked="checked"' }} @endif> Male</label>
            </div>
            <div class="col-5">
                <label class="radio-inline"><input type="radio" value="1" name="gender" @if(old('gender')==1){{ 'checked="checked"' }} @endif> Female</label>
            </div>
        </div>

        <div class="form-group row">
            <label for="DOB" class="col-2 col-form-label">Date of birth:</label>
            <div class="col-10">
                <input
                        type="date"
                        class="form-control input-sm"
                        name="DOB"
                        value="{{ old('DOB') }}"
                        required>
            </div>
        </div>

        <div class="form-group row">
            <label for="phone" class="col-2 col-form-label">Phone:</label>
            <div class="col-10">
                <input
                        type="text"
                        pattern="[0-9]{8,13}"
                        title="phone number consist of 8 or 13 number only"
                        class="form-control input-sm"
                        name="phone"
                        value="{{ old('phone') }}"
                        required>
            </div>
        </div>

        <div class="form-group row">
            <label for="address" class="col-2 col-form-label">Address:</label>
            <div class="col-10">
                <input
                        type="text"
                        class="form-control input-sm"
                        name="address"
                        value="{{ old('address') }}"
                        required>
            </div>
        </div>

        <div class="form-group row">
            <label for="hire_date" class="col-2 col-form-label">Hire date:</label>
            <div class="col-10">
                <input
                        type="date"
                        class="form-control input-sm"
                        name="hire_date"
                        value="{{ old('hire_date') }}"
                        required>
            </div>
        </div>

        <div class="form-group row">
            <label for="degree" class="col-2 col-form-label">Degree:</label>
            <div class="col-10">
                <input
                        type="text"
                        class="form-control input-sm"
                        name="degree"
                        value="{{ old('degree') }}"
                        required>
            </div>
        </div>

        <button class="btn btn-block btn-info" type="submit">Add</button>
    </form>
</div>
