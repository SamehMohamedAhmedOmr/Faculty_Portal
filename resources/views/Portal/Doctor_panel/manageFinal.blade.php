<div class="headerName">{{'Manage Final Grades'}}</div>


<form class="form-horizontal" method="GET" action="/panel/Doctor/ManageFinalGrades">
    @csrf
    <div class="row">
        <div class="input-group col-sm-9 form-inline courseConatiner">
            <div class="input-group-prepend">
                <span class="input-group-text" id="CourseID">Course</span>
            </div>
                <select name="courseID" class="form-control" aria-describedby="" style="height: 52px;">
                    @foreach($All_oprn_Courses as $course)
                        <option class="" name="courseID" id="CourseID" value="{{$course->subject->subject->id}}">{{$course->subject->subject->name}}</option>
                    @endforeach
                </select>
            </div>
        <div class="col-3">
            <button class="btn btn-outline-secondary">Manage Final Grades <i class="fas fa-cog"></i> </button>
        </div>
    </div>
</form>

{{-------------------grade Distibution--------------------}}
@if(isset($student) && count($student)>0)
    @php
        $classwork = $grade_dist->section +$grade_dist->quiz_grade+$grade_dist->participation+$grade_dist->attendance+$grade_dist->project+$grade_dist->assignment+$grade_dist->midterm;
        $final=100-$classwork;
    @endphp

    <br>
        <div class="alert alert-dark row">
            <div class="col-1 col-offset-1"></div>
            <div class="col-5"  style="margin-top:3px;text-align: center;font-weight: bolder;" ><div style="background-color: #f7f7f7;font-size:13px;padding: 5px 0px 3px 5px;">{{ 'Class work -> '.$classwork}}</div></div>
            <div class="col-5"  style="margin-top:3px;text-align: center;font-weight: bolder;" ><div style="background-color: #f7f7f7;font-size:13px;padding: 5px 0px 3px 5px;">{{ 'Final Grade -> '.$final}}</div></div>
        </div>
    <br>
    {{-------------------------Manage Student Grades --------------------------------------------}}
    <div style="margin-bottom: -30px; margin-top: 50px;margin-left: -90px;">
        <b style="font-size: 20px; font-family:'Droid Serif' ">{{ $student->total() }} Total Students</b> &nbsp;
        <b style="font-size: 11px; color: #f00;"> In this page {{ $student->count() }} student</b>
    </div>
    <button type="submit" onclick="document.getElementById('form').submit();" class="btn btn-outline-dark" style="float: right;">Update all grades <i class="fas fa-upload"></i> </button>

    <div class="clearfix"></div>
    <table class="table table-bordered" style="position: relative;">
        <thead class="thead-light">
        <tr>
            <th style="vertical-align: middle;" scope="col">ID</th>
            <th style="vertical-align: middle;" scope="col">StudentName</th>
            <th style="vertical-align: middle;" scope="col">Email</th>
            <th style="vertical-align: middle;" scope="col">Level</th>
            <th style="vertical-align: middle;" scope="col">class Work</th>
            <th scope="col" style="vertical-align: middle;background-color: #c8ffab;color: #1f1e1e;font-weight: bolder;">Final</th>
            <th scope="col" style="vertical-align:middle;">Total</th>
        </tr>
        </thead>
            <tbody style="font-size: 15px !important;">
                <form action="/panel/Doctor/ManageFinalGrades" method="post" id="form">
                    @csrf
                    @foreach($student as $stu)
                            @php
                                $grades = $stu->grades->where('semester_id',$semester_id)->where('subject_id',$courseID)->first();
                                if(count($grades)>0)
                                {
                                    $stu_final = $grades->final;
                                    $stu_classwork = $grades->section +$grades->quiz+$grades->midterm+$grades->participation+$grades->attendance+$grades->project+$grades->assignment;
                                }
                                else
                                {
                                    $stu_classwork=0;
                                }
                            @endphp
                            <tr class="setRow">
                                <td>{{ $stu->id }}</td>
                                <td>{{ $stu->user[0]->name_en }}</td>
                                <td>{{ $stu->user[0]->email }}</td>
                                <td>{{ $stu->graduated_status}}</td>
                                <td>{{$stu_classwork.'/'.$classwork}}</td>
                                <td><input style="text-align:center;" type="number" name="final[]" min="0" max="{{$final}}" @if(isset($stu_final))value="{{$stu_final}}" @else value="0" @endif></td>
                                <td style="font-weight: bolder;">@if(isset($grades->total_grade)){{$grades->total_grade}} @else 0 @endif</td>
                                <input type="hidden" name="stu_id[]" value="{{$stu->id}}">
                            </tr>
                    @endforeach
                    <input type="hidden" name="sem_id" value="{{$semester_id}}">
                    <input type="hidden" name="courseID" value="{{$courseID}}">
                </form>
            </tbody>
    </table>
    <div class="pagination text-center"> {{ $student->appends($_GET)->links() }} </div>
    {{-----------------end student grades ---------------}}
@endif