<h6 class="headerName"> Set Section Grades </h6>
<form class="form-horizontal" method="GET" action="/Panel/Instructor/ManageCourse">
    <meta name="csrf-token" content={{csrf_token()}}>
    <div class="row">
        <div class="input-group col-sm-5 form-inline" >
            <div class="input-group-prepend">
                <span class="input-group-text" id="Semester">Semester</span>
            </div>
            <select name="SemesterName" id="SemesterName" class="form-control" aria-describedby="Semester">
                <option selected disabled>select Semester</option>
                @foreach($allSemester as $semester)
                    <option value="{{$semester->id}}">{{$semester->name}}</option>
                @endforeach
            </select>
        </div>

        <div  class="input-group col-sm-5 form-inline courseConatiner" style="display: none;">
            <div class="input-group-prepend">
                <span class="input-group-text" id="Semester">Course</span>
            </div>
            <div class="coursesList">
            </div>
        </div>

        <div class="col-2" id="ManageCourseButton" style="display: none;">
            <button class="btn btn-outline-secondary">Manage <i class="fas fa-cog"></i> </button>
        </div>
    </div>
</form>

{{----------------------------------------   Start students Div  ---------------------------------------------------}}
@php
    $quiz=0;
    $section=0;
    $participate=0;
    $attendance=0;
    $project=0;
    $assigment=0;
@endphp

@if(isset($student) && count($student)>0)
    {{------------------------------classwork-------------------------------------}}
    <br>
    <div class="alert alert-dark row">
        <div class="col-12" style="text-align: center;">{{ 'class work Distributions for this course' }}</div> <br><br>
        <div class="col-2" ><div style="background-color: #f7f7f7;font-size:13px;padding: 5px 0px 3px 5px;">@if($grade_dist->section==0){{'Section -> 0'}} @else {{'Section -> '.$grade_dist->section}}@php $section=1; @endphp @endif</div></div>
        <div class="col-2" > <div style="background-color: #f7f7f7;font-size:13px;padding: 5px 0px 3px 5px;"> @if($grade_dist->quiz_grade==0) {{'Quiz -> 0'}} @else {{'Quiz -> '.$grade_dist->quiz_grade}} @php $quiz=1; @endphp @endif </div> </div>
        <div class="col-2" ><div style="background-color: #f7f7f7;font-size:13px;padding: 5px 0px 3px 5px;">@if($grade_dist->participation==0){{'participate -> 0'}} @else {{'participate -> '.$grade_dist->participation}} @php $participate=1; @endphp @endif</div></div>
        <div class="col-2" ><div style="background-color: #f7f7f7;font-size:13px;padding: 5px 0px 3px 5px;">@if($grade_dist->attendance==0){{'Attendance -> 0'}} @else {{'Attendance -> '.$grade_dist->attendance}}@php $attendance=1; @endphp @endif</div></div>
        <div class="col-2" ><div style="background-color: #f7f7f7;font-size:13px;padding: 5px 0px 3px 5px;">@if($grade_dist->project==0){{'project -> 0'}} @else {{'project -> '.$grade_dist->project}} @php $project=1; @endphp @endif</div></div>
        <div class="col-2" ><div style="background-color: #f7f7f7;font-size:13px;padding: 5px 0px 3px 5px;">@if($grade_dist->assignment==0){{'Assignment -> 0'}} @else {{'Assignment -> '.$grade_dist->assignment}} @php $assigment=1; @endphp @endif</div></div>
        <div class="col-6"  style="margin-left: 25%; margin-top:3px;text-align: center;font-weight: bolder;" ><div style="background-color: #f7f7f7;font-size:13px;padding: 5px 0px 3px 5px;">Midterm exam -> {{$grade_dist->midterm}}</div></div>

    </div>
    <br>
    {{----------------------Uploaded FIles----------------------------------}}
    <div class="alert alert-secondary row">
        {{--@if(Request::input('SemesterName')==$AvilableSemester)--}}
            {{--<div class="col-12" style="text-align: center;">{{ 'Upload New Material to Class Room' }}</div> <br><br>--}}
            {{--<form class="form col-12 row" action="/Panel/Instructor/UploadFile" style="margin-bottom: 5px;"  method="POST" enctype="multipart/form-data">--}}
                {{--@csrf--}}
                {{--<input type="hidden" name="semesterID" value="{{Request::input('SemesterName')}}">--}}
                {{--<input type="hidden" name="subjectID" value="{{Request::input('courseID')}}">--}}
                {{--<input type="file" name="file" class="btn btn-light form-control col-4" style="margin-right: 10px;">--}}
                {{--<input type="text" name="description" placeholder="description" class="form-control col-5" style="margin-right: 55px;">--}}
                {{--<button type="submit" value="upload File" class="btn  btn-outline-dark col-2">Upload File &nbsp; <i class="fas fa-upload"></i></button>--}}
            {{--</form>--}}
        {{--@endif--}}
        <div class="col-12 headerName" style="text-align: center;background-color: #ffffff6b;padding-bottom: 0;padding-top: 18px;">{{ 'All Material' }}
            &nbsp;<i class="fas fa-file"></i>
            &nbsp;<i class="fas fa-file-word"></i>
            &nbsp; <i class="fas fa-file-pdf"></i>
            <br><br>
            <div  style="margin-bottom: 10px;">
                @foreach($AllMaterial as $material)
                    @php
                        $fileName = explode('__',$material->file);
                    @endphp
                    <div class="d-inline-block" style="margin-bottom: 5px;margin-right: 5px;" title="{{$material->description}}">
                        <a href="{{Storage::url('DoctorUploads/'.$material->file)}}">
                            <button class="btn btn-outline-danger">{{$fileName[1]}} &nbsp; <i class="fas fa-file"></i></button>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-------------------------Manage Student Grades --------------------------------------------}}

    <div style="margin-bottom: -30px; margin-top: 50px;">
        <b style="font-size: 20px; font-family:'Droid Serif' ">{{ $student->total() }} Total Students</b> &nbsp;
        <b style="font-size: 11px; color: #f00;"> In this page {{ $student->count() }} student</b>
    </div>

    <div style="float: right;">
        <button class="btn btn-outline-secondary NotfiyStudent">Notify Students <i class="fas fa-exclamation-circle"></i></button>
    </div>
    <div class="clearfix"></div>
    <table class="table table-bordered" >
        <thead class="thead-light">
        <tr>
            <th style="vertical-align: middle;" scope="col">ID</th>
            <th style="vertical-align: middle;" scope="col">StudentName</th>
            <th style="vertical-align: middle;" scope="col">Email</th>
            <th style="vertical-align: middle;" scope="col">Level</th>
            <th style="vertical-align: middle;" scope="col">Midterm</th>
            @php
                if($quiz==1){echo "<th style=\"vertical-align: middle;\" scope=\"col\">Quiz</th>";}
                if($section==1){echo "<th style=\"vertical-align: middle;\" scope=\"col\">Section</th>";}
                if($participate==1){echo "<th style=\"vertical-align: middle;\" scope=\"col\">Paticipate</th>";}
                if($attendance==1){echo "<th style=\"vertical-align: middle;\" scope=\"col\">Attend</th>";}
                if($project==1){echo "<th style=\"vertical-align: middle;\" scope=\"col\">Proj</th>";}
                if($assigment==1){echo "<th style=\"vertical-align: middle;\" scope=\"col\">Assignment</th>";}
            @endphp
            <th scope="col" style="vertical-align: middle;background-color: #c8ffab;color: #1f1e1e;font-weight: bolder;">Total</th>
            @if($section==1) <th scope="col" style="vertical-align: middle;">Edit</th> @endif

        </tr>
        </thead>
        <tbody style="font-size: 15px !important;">
        @php $Notify_ids=[];  @endphp
        @foreach($student as $stu)
            @php
                $Notify_ids[]=$stu->id;
                $grades = $stu->grades->where('semester_id',Request::input('SemesterName'))->where('subject_id',$courseID)->first();
            @endphp
            <tr class="setRow">
                <td>{{ $stu->id }}</td>
                <td>{{ $stu->user[0]->name_en }}</td>
                <td>{{ $stu->user[0]->email }}</td>
                <td>{{ $stu->graduated_status}}</td>
                <td><input style="text-align:center;" name="midterm" type="number"  disabled  @if(isset($grades)) value="{{$grades->midterm}}" @else value="0"  @endif min="0" max="{{$grade_dist->midterm}}"></td>
                @php
                    if($quiz==1)
                    {
                        echo "<td><input disabled style=\"text-align:center;\"  ".((isset($grades->quiz))?' value='.$grades->quiz.' ':' value="0"')." type=\"number\" min=\"0\" max=\"".$grade_dist->quiz_grade."\" ></td>";
                    }
                    if($section==1)
                    {
                        echo "<td ><input ".((isset($grades->semester_id) && $grades->semester_id != $AvilableSemester )?' disabled':'')." style=\"text-align:center;\" name='section' ".((isset($grades->section))?'value='.$grades->section.'':'value="0"')." type=\"number\" min=\"0\" max=\"".$grade_dist->section."\"></td>";
                    }
                    if($participate==1)
                    {
                        echo "<td ><input disabled style=\"text-align:center;\"  ".((isset($grades->participation))?'value='.$grades->participation.'':'value="0"')." type=\"number\" min=\"0\" max=\"".$grade_dist->participation."\"></td>";
                    }
                    if($attendance==1)
                    {
                        echo "<td ><input disabled style=\"text-align:center;\"  ".((isset($grades->attendance))?'value='.$grades->attendance.'':'value="0"')." type=\"number\" min=\"0\" max=\"".$grade_dist->attendance."\"></td>";
                    }
                    if($project==1)
                    {
                        echo "<td ><input disabled style=\"text-align:center;\"  ".((isset($grades->project))?'value='.$grades->project.'':'value="0"')." type=\"number\" min=\"0\" max=\"".$grade_dist->project."\"></td>";
                    }
                    if($assigment==1)
                    {
                        echo "<td ><input disabled style=\"text-align:center;\"  ".((isset($grades->assignment))?'value='.$grades->assignment.'':'value="0"')." type=\"number\" min=\"0\" max=\"".$grade_dist->assignment."\"></td>";
                    }
                @endphp
                <td style="background-color: #c4f2ab59; font-weight: bolder;">@if(isset($grades->total_grade)){{$grades->total_grade}}@else 0 @endif</td>
                @if($section==1)
                    <td><button  class="btn btn-dark" onclick="setGrades(this)" @if( isset($grades->semester_id) && $grades->semester_id != $AvilableSemester ) disabled @endif  href="#"><i class="fas fa-pencil-alt checkGrades"></i></button></td>
                    <input type="hidden" name="stu_id" value="{{$stu->id}}">
                    <input type="hidden" name="sem_id" @if( !isset($grades->semester_id) || (isset($grades->semester_id) && $grades->semester_id == $AvilableSemester)) value="{{$AvilableSemester}}" @endif >
                    <input type="hidden" name="courseID" value="{{$courseID}}">
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="pagination text-center"> {{ $student->appends($_GET)->links() }} </div>
    <div class="col-12 text-center">
        <a href="/exportStudent_Instructor/{{Request::input('courseID') }}/{{ Request::input('SemesterName') }}"><button class="btn btn-outline-secondary">create Excel Sheet &nbsp;<i class="far fa-file-excel"></i></button></a>
    </div>
    {{---------------------------------------MailBox------------------------------------------------------}}
    <div class="composeMessage" style="padding: 15px; left: 27%; display: none; width: auto; height: auto;">
        <div class=""><div class="" style="overflow: auto; width: 605px; height: 400px;">
                <div id="fancy2" style="display: block;">
                    <h4>Notify All Students </h4>
                    <form action="/Panel/Instructor/NotifyStudents" method="POST">
                        @csrf
                        @php
                            foreach($Notify_ids as $id)
                            {  echo '<input type="hidden" name="NotifyI_ids[]" value="'.$id.'">'; }
                        @endphp
                        <input type="hidden" name="subjectID" value="{{Request::input('courseID')}}">
                        <input type="hidden" name="semesterID" value="{{Request::input('SemesterName')}}">

                        <fieldset class="subject" style="width: 100% !important;">
                            <input name="header" placeholder="heading..." type="text" required>
                        </fieldset>

                        <fieldset class="question" style="width: 100% !important;">
                            <textarea name="message" placeholder="Message..." required></textarea>
                        </fieldset>

                        <div class="btn-holder" style="padding-top: 12px;">
                            <button class="btn btn-block btn-outline-dark" style="line-height: normal; width: 100%;" type="submit">Send Message <i class="far fa-share-square"></i> </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <a title="Close" class=""><i class="close fas fa-times-circle"></i></a>
    {{--</div>--}}

@elseif(isset($student) && count($student)==0)
    <div class="optionView col-12">
        <div class="center-block" style=" margin: 10px;">
            <div class="clearfix"></div>
            <span class="timetable_table_title">{{'there is No Student in this course !! '}}</span>
        </div>
    </div>
@endif

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
    <script src=" {{ asset('/js/frontend/Instructor_ManageCourse.js') }}"></script>
@endsection
    </div>