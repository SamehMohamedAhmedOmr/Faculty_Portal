<div class="headerName">Add Course</div>
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
    <form id="addCourseForm" method="post" action="{{action('manageCourse@store')}}" style="padding: 15px;">
        @csrf
        <div class="form-group row">
            <label for="Name" class="col-2 col-form-label">Name:</label>
            <input id="name" type="text" class="form-control col-10" placeholder="Enter Course Name" name="name" maxlength="100" minlength="5" required value="{{ old('name') }}" >
        </div>

        <div class="form-group row">
            <label for="Description" class="col-2 col-form-label">Description:</label>
            <input id="Description" type="text" class="form-control col-10"  placeholder="Enter Course Description" name="Description" max="800" min="10" required value="{{ old('Description') }}">
        </div>

        <div class="form-group row">
            <label for="Credit" class="col-2 col-form-label">credit Hours:</label>
            <input id="Credit" type="number" class="form-control col-10"  placeholder="Enter Course Credit Hour" name="Credit" max="3" min="2" required value="{{ old('Credit') }}">
        </div>

        <div class="form-group row" >
            <label for="Final_Grade" class="col-2 col-form-label">Final Grade:</label>
            <select id="Final_Grade" name="Final_Grade" required class="form-control col-10">
                <option disabled="disabled" selected="" value="No">Please Select final Grade </option>
                <option value="50">50 Grade</option>
                <option value="60">60 Grade</option>
            </select>
        </div>

        <div class="form-group row">
            <label for="level" class="col-2 col-form-label">level required:</label>
            <select id="level" name="level" required class="form-control col-10">
                <option disabled="disabled" selected="" value="No">Please Select level </option>
                <option value="1">Level 1</option>
                <option value="2">Level 2</option>
                <option value="3">Level 3</option>
                <option value="4">Level 4</option>
            </select>
        </div>

        <div class="form-group row">
            <label for="Department" class="col-2 col-form-label">Department:</label>
            <select id="Department" name="Department" required class="form-control col-10">
                <option disabled="disabled" selected="" value="No">Please Select Department </option>
                @foreach($departments as $department)
                    <option value="{{$department->id}}">{{$department->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group row">
            <label for="Prerequisite" class="col-2 col-form-label">Prerequisite:</label>
            <select id="Prerequisite" name="Prerequisite" required class="form-control col-10" >
                <option disabled="disabled" selected="" value="No">Please Select Prerequisite if course have </option>
                @foreach($courses as $course)
                    <option value="{{$course->id}}">{{$course->name}}</option>
                @endforeach
            </select>
        </div>
        <button id="addCourse" type="submit" class="btn btn-block btn-outline-info">Add New Course <i class="fas fa-graduation-cap"></i></button>
    </form>
</div>