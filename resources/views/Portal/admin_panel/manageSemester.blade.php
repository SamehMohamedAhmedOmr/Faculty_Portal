<br>
<form method="get" action="{{action('manageSemester@create')}}">
    <button  class="btn btn-outline-success active"
             style="font-size: 14px;  margin: 5px auto; float: right !important;" type="submit">Add Semester &nbsp; <i class="fa fa-calendar"></i></button>
</form>
<div class="clearfix"></div>
@if(session()->has('no_semester'))
    <div class="alert alert-danger">
        {{session()->pull('no_semester', '')}}
        <i class="fa fa-times" style="float: right "></i>
    </div>
@endif

@if(session()->has('semester_cannot_open'))
    <div class="alert alert-danger">
        {{session()->pull('semester_cannot_open', '')}}
        <i class="fa fa-times" style="float: right "></i>
    </div>
@endif

@if (session()->has('Add_Semester_Success'))
    <div class="alert alert-success">
        {{session()->pull('Add_Semester_Success', '')}}
        <i class="fas fa-check-square"></i>
    </div>
@elseif (session()->has('update_Semester_success'))
    <div class="alert alert-success">
         {{session()->pull('update_Semester_success', '')}}
        <i class="fas fa-check-square"></i>
    </div>
@endif

@if(!$semesters->count())
    <div class="clearfix"></div>
    <div class="Not_Found"> No Semester exists yet</div>
@else
    <table class="table table-hover col-md-12" style="margin-top: 3px;">
        <thead class="thead-light">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Type</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Complete</th>
            <th>Edit</th>
        </tr>
        </thead>

        <tbody>
        @foreach($semesters as $key => $semester)
            <tr
                @if($semester->complete == 0)style="background-color: #ffaaaac7;"
                @else style="background-color: #aaffadc7;"
                @endif
            >
            <td>{{++$key}}</td>
                <td><a href={{route('openCourses.show',$semester->id)}}>{{$semester->name}}</a></td>
            <td>
                @if($semester->isSummer==0)
                    {{'Regular'}}
                @elseif($semester->isSummer==1)
                    {{'Summer'}}
                @endif
            </td>
            <td>{{$semester->start_date}}</td>
            <td>{{$semester->end_date}}</td>
            <td>
                @if($semester->complete == 0)
                {{'Not Complete'}}
                @elseif($semester->complete == 1)
                 {{'Complete'}}
                @endif
            </td>
            <td>
                <a href="{{route('manageSemester.edit',$semester->id)}}"><i class="fas fa-edit" aria-hidden="true"></i></a>
            </td>
        </tr>

        </tbody>
        @endforeach
        {{$semesters->links()}}

    </table>

@endif

