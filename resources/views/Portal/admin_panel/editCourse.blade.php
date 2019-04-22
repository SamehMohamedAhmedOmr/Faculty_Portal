
<div class="headerName">{{$selected->name}} Course Details</div>
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
    <form method="post" action="{{action('manageCourse@update',['id' => $selected->id])}}" class="form-group" style="padding: 15px;">
        {{csrf_field()}}
        <input type="hidden" name="_method" value="PUT" >

        <div class="form-group row">
            <label for="Name" class="col-2 col-form-label">Name:</label>
            <input disabled id="courseName" type="text" class="form-control col-10"  placeholder="Enter Course Name" name="name" maxlength="100" minlength="5" required value="{{$selected->name}}" >
        </div>

        <div class="form-group row">
            <label for="Description" class="col-2 col-form-label">Description:</label>
            <input disabled id="courseDescription" type="text" class="form-control col-10"  placeholder="Enter Course Description" name="Description" max="800" min="10" required value="{{$selected->description}}">
        </div>

        <div class="form-group row">
            <label for="Credit" class="col-2 col-form-label">credit Hours:</label>
            <input disabled id="Credit" type="number" class="form-control col-10"  placeholder="Enter Course Credit Hour" name="Credit" max="3" min="2" required value="{{$selected->credit_hours}}">
        </div>

        <div id="course_grade" class="form-group row">
            <div id="labelType"  class="col-form-label col-2" for="grade">Final Grade :</div>
            <div id="labelType" class="form-control col-10" style="background-color: lightgray;"> {{$selected->final_grade}}</div>
        </div>

        <div id="course_level" class="form-group row">
            <div id="labelType"  class="col-form-label col-2" for="level">level: </div>
            <div class="form-control col-10" style="background-color: lightgray;">level {{$selected->level_req}}</div>
        </div>


        <div id="gradeDiv" class="form-group row" style="display: none;">
            <label for="Final_Grade" class="col-2 col-form-label">Final Grade:</label>
            <select id="Final_Grade" name="finalGrade" required class=" form-control col-10">
                <option disabled="disabled" selected="" value="No">Please Select final Grade </option>
                <option value="50" @if($selected->final_grade == 50) {{'selected'}} @endif>50 Grade</option>
                <option value="60" @if($selected->final_grade == 60) {{'selected'}} @endif>60 Grade</option>
            </select>
        </div>


        <div id="levelDiv" class="form-group row" style="display: none;">
            <label for="level" class="col-2 col-form-label">level required:</label>
            <select id="level" name="level" class=" form-control col-10" required>
                <option disabled="disabled" selected="" value="No">Please Select level </option>
                <option value="1" @if($selected->level_req == 1) {{'selected'}} @endif>Level 1</option>
                <option value="2" @if($selected->level_req == 2) {{'selected'}} @endif>Level 2</option>
                <option value="3" @if($selected->level_req == 3) {{'selected'}} @endif>Level 3</option>
                <option value="4" @if($selected->level_req == 4) {{'selected'}} @endif>Level 4</option>
            </select>
        </div>

        <div id="course_department" class="form-group row">
            <label id="labelType" class="col-form-label col-2">Department :</label>
                <span id="labelType" class="form-control col-10" style="background-color: lightgray;">
                 @foreach($departments as $department)
                     @if($selected->department_id == $department->id)
                         {{$department->name}}
                     @endif
                 @endforeach
                </span>
        </div>

        <div id="departmentDiv" class="form-group row" style="display: none;">
            <label for="Department" class="col-2 col-form-label">Department:</label>
            <select id="Department" name="Department" class="form-control col-10" required>
                <option disabled="disabled" selected="" value="No">Please Select Department </option>
                @foreach($departments as $department)
                    <option value="{{$department->id}}" @if($selected->department_id == $department->id) {{'selected'}} @endif>{{$department->name}}</option>
                @endforeach
            </select>
        </div>

        <div id="course_prerequisite" class="form-group row">
            <label id="labelType" class="col-form-label col-2">Prerequisite :</label>
                <span class="form-control col-10" style="background-color: lightgray;">
                    @if(isset($selected->prerequisite))
                         @foreach($courses as $course)
                             @if($selected->prerequisite == $course->id)
                                 {{$course->name}}
                            @endif
                         @endforeach
                    @else{{'None'}}
                    @endif
                </span>
        </div>

        <div id="prerequisiteDiv" class="form-group row" style="display: none;">
            <label for="Prerequisite" class="col-2 col-form-label">Prerequisite:</label>
            <select id="Prerequisite" name="Prerequisite" required class="form-control col-10">
                <option disabled="disabled" selected="" value="No">Please Select Prerequisite if course have </option>
                @foreach($courses as $course)
                    <option value="{{$course->id}}" @if($selected->prerequisite == $course->id) {{'selected'}} @endif>{{$course->name}}</option>
                @endforeach
            </select>
        </div>
        <button onclick="return false;" id="editCourse" class="btn btn-block btn-outline-info">Edit Course <i class="fas fa-graduation-cap"></i> </button>
        <button id="submitEdit" class="btn btn-block btn-outline-success" style="display: none;">Update Course <i class="fas fa-graduation-cap"></i></button>
    </form>
</div>