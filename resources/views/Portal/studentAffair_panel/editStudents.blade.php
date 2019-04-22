<br>
  <h3 class="text-center header">Edit Student [{{ $basics[0]->name_en }}] </h3>
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
    <form action="{{'/Panel/Student/update/'.$stu->id }}" class="form-group" style="padding:36px;background-color: #f7f7f7;" method="POST">
        @csrf
        <div class="form-group row">
            <label for="national_id" class="col-2 col-form-label">National ID:</label>
            <input type="text" class="form-control col-10" value="{{ $basics[0]->national_id }}" name="national_id" readonly>
        </div>

        <div class="form-group row">
            <label for="name_ar" class="col-2 col-form-label">Arabic Name:</label>
            <input type="text" class="form-control col-10" value="{{ $basics[0]->name_ar }}" name="name_ar" readonly>
        </div>

        <div class="form-group row">
            <label for="name_en" class="col-2 col-form-label">English Name:</label>
            <input type="text" class="form-control col-10" value="{{ $basics[0]->name_en }}" name="name_en" readonly>
        </div>

        <div class="form-group row">
            <label for="email" class="col-2 col-form-label">Email:</label>
            <input type="text" class="form-control col-10" value="{{ $basics[0]->email }}" name="email">
        </div>

        <div class="form-group row">
            <label for="gender" class="col-2 col-form-label">Gender:</label>
            <div class="col-5">
                <label class="radio-inline"><input type="radio" value="0" name="gender" checked>  Male</label>
            </div>
            <div class="col-5">
                <label class="radio-inline"><input type="radio" value="1" name="gender">  Female</label>
            </div>
        </div>

        <div class="form-group row">
            <label for="DOB" class="col-2 col-form-label">Date of birth:</label>
            <input type="date" class="form-control col-10" name="DOB" value="{{ $basics[0]->DOB }}" readonly>
        </div>

        <div class="form-group row">
            <label for="phone" class="col-2 col-form-label">Phone:</label>
            <input type="text" class="form-control col-10" name="phone" value="{{ $basics[0]->phone }}">
        </div>

        <div class="form-group row">
            <label for="address" class="col-2 col-form-label">Address:</label>
            <input type="text" class="form-control col-10" name="address" value="{{ $basics[0]->address }}">
        </div>

        <div class="form-group row">
            <label for="department_id" class="col-2 col-form-label">Department:</label>
            <select class="form-control col-10" name="department_id" value="{{ $stu->Department->name }}">
                @foreach($departments as $dep)
                    <option value="{{$dep->id}}" @if($stu->Department->id==$dep->id){{'selected'}}@endif> {{$dep->name}} </option>
                @endforeach
            </select>
        </div>

        <div class="form-group row">
            <label for="graduated_status" class="col-2 col-form-label">Graduated Status:</label>
            <select class="form-control col-10" name="graduated_status" value="{{ $stu->graduated_status }}">
                    <option value="0" @if($stu->graduated_status=='0'){{'selected'}}@endif> graduated </option>
                    <option value="1"@if($stu->graduated_status=='1'){{'selected'}}@endif> Level 1 </option>
                    <option value="2"@if($stu->graduated_status=='2'){{'selected'}}@endif> Level 2 </option>
                    <option value="3"@if($stu->graduated_status=='3'){{'selected'}}@endif> Level 3 </option>
                    <option value="4"@if($stu->graduated_status=='4'){{'selected'}}@endif> Level 4 </option>
            </select>
        </div>

        <div class="form-group row">
            <label for="gender" class="col-2 col-form-label">Status:</label>
            <div class="col-5">
                <label class="radio-inline"><input type="radio" value="1" name="account_status" @if($stu->account_status==1){{ 'checked="checked"' }} @endif>Activate Account</label>
            </div>
            <div class="col-5">
                <label class="radio-inline"><input type="radio" value="0" name="account_status" @if($stu->account_status==0){{ 'checked="checked"' }} @endif>Deactivate Account</label>
            </div>
        </div>
        <button class="btn btn-block btn-outline-dark" type="submit">Edit Student &nbsp;<i class="fas fa-edit"></i> </button>
    </form>
</div>
