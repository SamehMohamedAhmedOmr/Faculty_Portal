{{-- information alert --}}
<br>
<div class="NoteContainer">
    <h4 style="text-align: center;" class="alert alert-dark">Notes : </h4>
    <ul>
        <li class="alert alert-info" style="padding: 5px;margin-bottom: 10px;">Semester Start Date Should Start with Saturday. <i class="fas fa-info-circle" style="float: right;" ></i></li>
        <li class="alert alert-info" style="padding: 5px;margin-bottom: 10px;">Semester End Date Should End with thursday.<i class="fas fa-info-circle" style="float: right;" ></i></li>
        <li class="alert alert-info" style="padding: 5px;margin-bottom: 10px;">Midterm should start with Sunday.<i class="fas fa-info-circle" style="float: right;" ></i></li>
        <li class="alert alert-info" style="padding: 5px;margin-bottom: 10px;">Registration Date will start automatically after 7 day from Semester Start Date.<i class="fas fa-info-circle" style="float: right;" ></i></li>
    </ul>
</div>
<div class="headerName">{{$selected->name}} Semester Details</div>
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
    <form method="post" action="{{action('manageSemester@update',['id' => $selected->id])}}" style="padding: 15px;">
        {{csrf_field()}}

        <input type="hidden" name="_method" value="PUT" >

        <div class="form-group row">
            <label for="Name" class="col-3 col-form-label">Name:</label>
            <input disabled id="semesterName" type="text" class="form-control col-9" placeholder="Enter Semester Name"
                   name="name" maxlength="100" minlength="5" required value="{{$selected->name}}">
        </div>

        <div id="semester_type" class="form-group row">
            <label id="labelType" class="col-3 col-form-label">Semester Type :</label>
            <div class="form-control col-9" style="background-color: lightgray;">
                    @if($selected->type == 0 )
                        {{'Regular semester'}}
                    @elseif($selected->type==1)
                        {{'Summer'}}
                    @endif
            </div>
        </div>

        <div id="typeDiv" class="form-group row" style="display: none;" >
            <label for="Type" class="col-3 col-form-label">Type:</label>

            <div id="type" aria-required="true" class="col-4">
                <label><input type="radio" value="0" name="type" required @if($selected->isSummer == 0) {{'checked="checked"'}} @endif>
                    Regular Semester</label>
            </div>

            <div d="type" aria-required="true" class="col-4">
                <label><input type="radio" value="1" name="type" required @if($selected->isSummer == 1) {{'checked="checked"'}} @endif>
                    Summer</label>
            </div>
        </div>

        <div class="form-group row">
            <label for="Start_Date"  class="col-3 col-form-label">Semester Start Date:</label>
            <input disabled id="Start_Date" type="date" class="form-control col-9"
                   placeholder="Enter Semester Start Date" name="Start_Date" required value="{{$selected->start_date}}">
        </div>

        <div class="form-group row">
            <label for="End_Date" class="col-3 col-form-label">Semester End Date:</label>
            <input disabled id="End_Date" type="date" class="form-control col-9"
                   placeholder="Enter Semester End Date" name="End_Date" required value="{{$selected->end_date}}">
        </div>


        <div class="form-group row">
            <label for="Midterm" class="col-3 col-form-label">Midterm week:</label>
            <input disabled id="Midterm" type="date" class="form-control col-9"
                   placeholder="Enter Midterm week" name="Midterm" required value="{{$selected->midterm_week}}">
        </div>

        <div id="semester_Ststus" class="form-group row">
            <label id="labelType" class="col-3 col-form-label" >Semester Status : </label>

            <div class="form-control col-9" style="background-color: lightgray;">
                    @if($selected->complete == 0 )
                        {{'Not Complete'}}
                    @elseif($selected->complete==1)
                        {{'Complete'}}
                    @endif
            </div>
        </div>

        <div id="statusDiv" class="form-group row" style="display: none;" >

            <label for="Type" class="col-3 col-form-label" >Status :</label>

            <div id="status" aria-required="true" class="col-4">
                <label>complete
                        <input type="radio" value="1" name="status" @if($selected->complete == 1) {{'checked="checked"'}} @endif>
                </label>
            </div>
            <div id="status" aria-required="true" class="col-4">
                <label> Not complete
                        <input type="radio" value="0" name="status" @if($selected->complete == 0) {{'checked="checked"'}} @endif>
                </label>
            </div>
        </div>

        <button onclick="return false;" id="editSemester" class="btn btn-block btn-outline-info">Edit Semester <i class="far fa-calendar"></i></button>
        <button id="submitEdit" class="btn btn-outline-success btn-block" style="display: none;">update Semester <i class="far fa-calendar"></i></button>
    </form>
</div>