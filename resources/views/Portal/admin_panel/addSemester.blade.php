<div class="headerName">Add Semster</div>
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
{{-- information alert --}}
<div class="NoteContainer">
    <h4 style="text-align: center;" class="alert alert-dark">Notes : </h4>
    <ul>
        <li class="alert alert-info" style="padding: 5px;margin-bottom: 10px;">Semester Start Date Should Start with Saturday. <i class="fas fa-info-circle" style="float: right;" ></i></li>
        <li class="alert alert-info" style="padding: 5px;margin-bottom: 10px;">Semester End Date Should End with thursday.<i class="fas fa-info-circle" style="float: right;" ></i></li>
        <li class="alert alert-info" style="padding: 5px;margin-bottom: 10px;">Midterm should start with Sunday.<i class="fas fa-info-circle" style="float: right;" ></i></li>
        <li class="alert alert-info" style="padding: 5px;margin-bottom: 10px;">Registration Date will start automatically after 7 day from Semester Start Date.<i class="fas fa-info-circle" style="float: right;" ></i></li>
    </ul>
</div>
{{--Add FOrm --}}
<form id="addSemesterForm" method="post" action="{{action('manageSemester@store')}}" style="padding: 15px;">
    @csrf

    <div class="form-group row">
        <label for="Name" class="col-2 col-form-label">Name:</label>
        <input id="name" type="text" class="form-control col-10" value="{{old('name')}}"  placeholder="Enter Semester Name" name="name" maxlength="100" minlength="5" required >
    </div>

    <div class="form-group row">
        <label for="Type" class="col-2 col-form-label">Type:</label>
        <div class="col-5">
            <input type="radio" value="0" name="type" required> Regular Semester</label>
        </div>
        <div class="col-5">
            <label><input type="radio" value="1" name="type" required> Summer</label>
        </div>
    </div>

    <div class="form-group row">
        <label for="Start_Date" class="col-3 col-form-label">Semester Start Date:</label>
        <input id="Start_Date" type="date" class="form-control col-9" value="{{old('Start_Date')}}"  placeholder="Enter Semester Start Date" name="Start_Date" required>
    </div>

    <div class="form-group row">
        <label for="End_Date" class="col-3 col-form-label">Semester End Date:</label>
        <input id="End_Date" type="date" class="form-control col-9"  value="{{old('End_Date')}}"  placeholder="Enter Semester End Date" name="End_Date" required>
    </div>


    <div class="form-group row">
        <label for="Midterm" class="col-3 col-form-label">Midterm week:</label>
        <input id="Midterm"  type="date" class="form-control col-9" value="{{old('Midterm')}}"  placeholder="Enter Midterm week" name="Midterm" required>
    </div>

    <button id="addSemester" type="submit" class="btn btn-outline-success btn-block">Add New Semster &nbsp; <i class="fa fa-calendar"></i></button>

</form>