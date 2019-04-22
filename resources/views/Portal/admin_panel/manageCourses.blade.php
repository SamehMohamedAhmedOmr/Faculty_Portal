<br>
<form method="get" action="{{action('manageCourse@create')}}">
    <button class="btn btn-outline-success active"
            style="font-size: 14px;  margin: 5px auto; float: right !important;" type="submit">Add Course  &nbsp; <i class="fas fa-graduation-cap"></i> </button>
</form>
<br>
<div class="clearfix"></div>
@if(session()->has('No_Course'))
<div class="alert alert-danger">
    {{session()->pull('No_Course', '')}}
    <i class="fa fa-times" style="float: right "></i>
</div>
@endif

@if (session()->has('Add_Courses_Success'))
 <div class="alert alert-success">
     {{session()->pull('Add_Courses_Success', '')}}
     <i class="fas fa-check-square"></i>
 </div>
@elseif (session()->has('update_Course_success'))
 <div class="alert alert-success">
     {{session()->pull('update_Course_success', '')}}
     <i class="fas fa-check-square"></i>
 </div>
@endif

@if(!$courses->count())
    <div class="clearfix"></div>
    <div class="Not_Found"> No Courses Added yet</div>
@else
{{--Search--}}
<div class="form-group">
    <input type="text" class="form-control input" id="course_search" name="course_search" placeholder="search by name"></input>
</div>
{{--table--}}
<table class="table table-hover col-md-12" style="margin-top: 3px;">
    <thead class="thead-light">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Credit Hours</th>
            <th>Level required</th>
            <th>Department</th>
            <th>Practical</th>
            <th>Edit</th>
        </tr>
    </thead>
    <tbody id="course_tbl">
    @foreach($courses as $key => $course)
        <tr>
            <td>{{++$key}}</td>
            <td>{{$course->name}}</td>
            <td>{{$course->credit_hours}}</td>
            <td>{{$course->level_req}}</td>
            <td>{{$course->Department->name}}</td>
            <td>
                @if($course->practical==0)
                    No
                @elseif($course->practical==1)
                    yes
                @endif
            </td>
            <td>
                <a href="{{route('manageCourses.edit',$course->id)}}"><i class="fas fa-edit" aria-hidden="true"></i></a>
            </td>
        </tr>
    @endforeach
    </tbody>
    {{$courses->links()}}
</table>
@endif




