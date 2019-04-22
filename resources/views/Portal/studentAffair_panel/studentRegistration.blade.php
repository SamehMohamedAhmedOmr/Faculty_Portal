@if(!$status)
    <div>
        <h4 class="regFormTitle">Student Registration</h4>
    </div>
@else
    <div>
        <h4 class="regFormTitle">Student Registration</h4>
    </div>
    <div class="headerName headerReg"> Student Name : <span>{{ $stu->User[0]->name_en }}</span> <br> ID : <span>{{ $stu->id }}</span></div>
    {{-- Start Success message --}}
    @if(session()->has('hint'))
        <div class="alert alert-success regFormHint">
            <i class="fas fa-asterisk"></i>
            {{ session()->get('hint') }}
        </div>
    @endif
    {{-- End Sucess message --}}
    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li class="alert alert-danger regFormError">{{ $error }}
                        <i class="fa fa-exclamation" style="float: right "></i>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- End Error message --}}
    <div>
        <h4 class="regFormTitle" style="font-size: 20px">Available courses</h4>
    </div>
    <form method="post" action="/Panel/newRegistration/{{ $stu->id }}" >
        @csrf
        {{ csrf_field() }}
        @foreach($avlbl_courses as $course)

            <button class="btn btn-primary courseRegBtn" data-toggle="collapse" data-target="#table{{$course->Subject->id}}" onclick="return false;" style="display: block">
                <span>{{ $course->Subject->name }} - Credit Hours : {{ $course->Subject->credit_hours }}</span>
            </button>

            <div class= "collapse in" id="table{{ $course->Subject->id }}">
                {{-- Lectures --}}
                <table class="table table-bordered col-md-12 timetable_table stuRegFormTbl">
                    <thead>
                    <tr><th colspan="6" class="tblType">Lectures</th></tr>
                    <tr>
                        <th></th>
                        <th> Doctor Name </th>
                        <th> Day </th>
                        <th> Start time </th>
                        <th> End time </th>
                        <th> Place </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($lectures as $lec)
                        @if($lec->subject_id == $course->subject_id)
                            <tr>
                                <td>
                                    <input type="radio" value="{{ $lec->id }}" name="lec{{$course->subject_id}}">
                                </td>
                                <td><h6>Dr.{{ $lec->timetableable->User[0]->name_en}}</h6></td>
                                <td><h6>{{ $lec->day }}</h6></td>
                                <td><h6>{{ $lec->time }}:00</h6></td>
                                <td><h6>{{ $lec->time+2 }}:00</h6></td>
                                <td><h6>{{ $lec->Place->name }}</h6></td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
                {{-- Sections --}}
                <table class="table table-bordered col-md-12 timetable_table stuRegFormTbl">
                    <thead>
                    <tr><th colspan="6" class="tblType">Sections</th></tr>
                    <tr>
                        <th></th>
                        <th> Doctor Name </th>
                        <th> Day </th>
                        <th> Start time </th>
                        <th> End time </th>
                        <th> Place </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sections as $sec)
                        @if($sec->subject_id == $course->subject_id)
                            <tr>
                                <td>
                                    <input type="radio" value="{{ $sec->id }}" name="sec{{$course->subject_id}}">
                                </td>
                                <td><h6>Eng.{{ $sec->timetableable->User[0]->name_en }}</h6></td>
                                <td><h6>{{ $sec->day }}</h6></td>
                                <td><h6>{{ $sec->time }}:00</h6></td>
                                <td><h6>{{ $sec->time+2 }}:00</h6></td>
                                <td><h6>{{ $sec->Place->name }}</h6></td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
        <button class="btn btn-primary regFormSubmBtn" type="submit">Register</button>
    </form>
@endif

<a href="/Panel/registrations"><button class='btn btn-primary regFormBackBtn'>Back</button></a>



