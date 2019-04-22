<br>

<div>
    <ul>
        @foreach ($errors->all() as $error)
            <li class="alert alert-danger">{{ $error }}
                <i class="fa fa-times" style="float: right "></i>
            </li>
        @endforeach
    </ul>
</div>

<div class="">
    <h4 style="font-family: monospace, monospace;" class="headerName">Available Courses</h4>
    @php
      $number = 1;
    @endphp

    <form method="post" action="{{action('registerCoursesController@store')}}" >
        @csrf
        {{ csrf_field() }}

        @foreach($openCourses as $openCourse)
            <button style="background-color: #88c2ff85;" class="registerCoursesButton btn btn-block btn-outline-info" data-toggle="collapse" data-target="#CourseDates{{$number}}" onclick="return false;">
                <i style="float: left;color: #030229; margin-top: 3px;" class="fa fa-bars" aria-hidden="true"></i>
                <span style="color: #464646;">{{$openCourse->subject->name}} [Credit-Hour : {{$openCourse->subject->credit_hours}}]</span>
            </button>
            <br>

                <div class= "collapse in" id="CourseDates{{$number}}">
                    <div id="courseDate">
                        <h6 class="headerName">Lectures Available</h6>

                            <table class="table table-bordered col-md-12">
                                <thead class="thead-light">
                                <tr>
                                    <th></th>
                                    <th>Professor</th>
                                    <th>Day</th>
                                    <th>Place</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($lectures as $lecture)
                                    @if($lecture->subject_id == $openCourse->subject->id && $lecture->timetableable_type === 'Doc')
                                        <tr>
                                            <td>
                                                <div id="type" class="form-group">
                                                    <label><input id="timetableLecture{{$number}}" type="radio" value="{{$lecture->id}}" name="timetableLecture{{$number}}">
                                                    </label>
                                                </div>
                                            </td>

                                            <td>Prof. {{$lecture->timetableable->User[0]->name_en}}</td>
                                            <td>{{$lecture->day}}</td>
                                            <td>{{$lecture->place->name}}</td>
                                            <td>
                                                {{$lecture->time}}:00
                                            </td>
                                            <td>
                                                @php $endTime = $lecture->time+2; @endphp
                                                {{$endTime}}:00
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>

                        <h6 class="headerName">Sections Available</h6>

                            <table class="table table-bordered col-md-12">
                                <thead class="thead-light">
                                <tr>
                                    <th></th>
                                    <th>Teacher Assistant</th>
                                    <th>Day</th>
                                    <th>Place</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($sections as $section)
                                    @if($section->subject_id == $openCourse->subject->id && $section->timetableable_type === 'T_A')
                                        <tr>
                                            <td>
                                                <div id="type"  class="form-group">
                                                    <label><input id="timetableSection{{$number}}" type="radio" value="{{$section->id}}" name="timetableSection{{$number}}">
                                                    </label>
                                                </div>
                                            </td>

                                            <td>Eng. {{$section->timetableable->User[0]->name_en}}</td>
                                            <td>{{$section->day}}</td>
                                            <td>{{$section->place->name}}</td>
                                            <td>
                                                {{$section->time}}:00
                                            </td>
                                            <td>
                                                @php $endTime = $section->time+2; @endphp
                                                {{$endTime}}:00
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>

                        <br>
                    </div>

                </div>

            <?php $number++; ?>
        @endforeach

        <button type="submit" class="btn btn-block btn-outline-info">Register Courses <i class="far fa-plus-square"></i></button>
    </form>

    <br>

</div>
