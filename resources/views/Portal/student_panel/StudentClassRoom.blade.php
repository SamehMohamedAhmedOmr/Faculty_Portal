@if($currentSemester[0]->open_register_date <= \Carbon\Carbon::now())
<h6 class="headerName"> ClassRoom </h6>

<div id="errorMessage" style="display: none;">
    <div class="clearfix"></div>
    <div class="Not_Found"> Please Select Course. </div>
</div>
    @if(count($registerCourses)>0)
    <form  id ="selectCourseForm" class="form-horizontal selectClass" method="get" action="{{'/Panel/Student/Classroom/'}}">
        {{csrf_field()}}
        <div class="row">
            <div class="input-group col-sm-5 form-inline" >
                <div class="input-group-prepend">
                    <span class="input-group-text" id="Semester">Course</span>
                </div>

                <select title="" name="courseName" id="courseName" class="form-control" aria-describedby="Semester" required>
                    <option selected disabled value="" >Select Course</option>
                       @foreach($registerCourses as $registerCourse)
                           @if($check === 1)
                               @if($registerCourse->subject_id === $currentCourse->id)
                               <option selected value="{{$registerCourse->subject_id}}">{{$registerCourse->subject->subject->name}}</option>
                               @else
                                <option value="{{$registerCourse->subject_id}}">{{$registerCourse->subject->subject->name}}</option>
                               @endif
                           @else
                              <option value="{{$registerCourse->subject_id}}">{{$registerCourse->subject->subject->name}}</option>
                           @endif
                       @endforeach
                </select>
            </div>

            <div class="col-2" id="ManageCourseButton">
                <button class="btn btn-outline-secondary" onclick="active(); return false;">
                    Show <i class="fas fa-sign-in-alt"></i>
                </button>
            </div>
        </div>
    </form>

        @if($check === 1)

            <h6 class="headerName"> Your Grade for {{$currentCourse->name}} </h6>

            <table class="table table-bordered">
                <thead class="thead-light">
                <tr>
                    <th class="headName" scope="col">Midterm</th>
                    <th class="headName" scope="col">Quiz</th>
                    <th class="headName" scope="col">Section</th>
                    <th class="headName" scope="col">Participation</th>
                    <th class="headName" scope="col">Attendance</th>
                    <th class="headName" scope="col">Project</th>
                    <th class="headName" scope="col">Assignment</th>
                </tr>
                <tr>
                    @if($grade_dist != null)
                        <th class="headContent" scope="col">@if($grade_dist->midterm == 0){{'-'}}@else{{$grade_dist->midterm}} @endif</th>
                        <th class="headContent" scope="col">@if($grade_dist->quiz_grade == 0){{'-'}}@else{{$grade_dist->quiz_grade}} @endif</th>
                        <th class="headContent" scope="col">@if($grade_dist->section == 0){{'-'}}@else{{$grade_dist->section}} @endif</th>
                        <th class="headContent" scope="col">@if($grade_dist->participation == 0){{'-'}}@else{{$grade_dist->participation}} @endif</th>
                        <th class="headContent" scope="col">@if($grade_dist->attendance == 0){{'-'}}@else{{$grade_dist->attendance}} @endif</th>
                        <th class="headContent" scope="col">@if($grade_dist->project == 0){{'-'}}@else{{$grade_dist->project}} @endif</th>
                        <th class="headContent" scope="col">@if($grade_dist->assignment == 0){{'-'}}@else{{$grade_dist->assignment}}@endif</th>
                    @else
                        <th class="headContent" scope="col">-</th>
                        <th class="headContent" scope="col">-</th>
                        <th class="headContent" scope="col">-</th>
                        <th class="headContent" scope="col">-</th>
                        <th class="headContent" scope="col">-</th>
                        <th class="headContent" scope="col">-</th>
                        <th class="headContent" scope="col">-</th>
                    @endif
                </tr>
                </thead>
                <tbody style="font-size: 15px !important;">
                <tr class="setRow">
                    @if(count($grades)>0)
                        <td style="vertical-align: middle;" scope="col">{{$grades->midterm}}</td>
                        <td style="vertical-align: middle;" scope="col">{{$grades->quiz}}</td>
                        <td style="vertical-align: middle;" scope="col">{{$grades->section}}</td>
                        <td style="vertical-align: middle;" scope="col">{{$grades->participation}}</td>
                        <td style="vertical-align: middle;" scope="col">{{$grades->attendance}}</td>
                        <td style="vertical-align: middle;" scope="col">{{$grades->project}}</td>
                        <td style="vertical-align: middle;" scope="col">{{$grades->assignment}}</td>
                    @else
                        <td style="vertical-align: middle;" scope="col">-</td>
                        <td style="vertical-align: middle;" scope="col">-</td>
                        <td style="vertical-align: middle;" scope="col">-</td>
                        <td style="vertical-align: middle;" scope="col">-</td>
                        <td style="vertical-align: middle;" scope="col">-</td>
                        <td style="vertical-align: middle;" scope="col">-</td>
                        <td style="vertical-align: middle;" scope="col">-</td>
                    @endif
                </tr>
                </tbody>
            </table>

            <div class="col-6 final_Announcement" >
                <div class="entire_final">Final Grade = {{$currentCourse->final_grade}}
                </div>
            </div>

        <br><br>

            <div class="alert alert-secondary row">
                <div class="col-12 headerName" style="text-align: center;background-color: #ffffff6b;padding-bottom: 0;padding-top: 18px;">{{ 'All Material' }}
                    &nbsp;<i class="fas fa-file"></i>
                    &nbsp;<i class="fas fa-file-word"></i>
                    &nbsp; <i class="fas fa-file-pdf"></i>
                    <br><br>
                    <div  style="margin-bottom: 10px;">
                        @if(count($AllMaterial)>0)
                            @foreach($AllMaterial as $material)
                                @php
                                    $fileName = explode('__',$material->file);
                                @endphp
                                <div class="d-inline-block" style="margin-bottom: 5px;margin-right: 5px;">
                                    <a href="{{Storage::url('DoctorUploads/'.$material->file)}}">
                                        <button class="btn btn-outline-danger">{{$fileName[1]}} &nbsp; <i class="fas fa-file"></i></button>
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <span style="color: red">No Material Yet</span>
                        @endif
                    </div>
                </div>
            </div>

        @endif
    @else
        <div id="errorMessage">
            <div class="clearfix"></div>
            <div class="Not_Found"> You don't register any Course yet. </div>
        </div>
    @endif
@else
    <div id="errorMessage">
        <div class="clearfix"></div>
        <div class="Not_Found"> You are Blocked Temporarily from access this Page. </div>
    </div>

@endif

@section('scripts')
    <script src=" {{ asset('/js/frontend/studentClass.js') }}"></script>
@endsection