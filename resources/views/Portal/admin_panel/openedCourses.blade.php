<br>
@if(count($currentSemester)>0)
    @if($currentSemester[0]->open_register_date > \Carbon\Carbon::now())
        <?php $check = 0; ?>
        {{--get errors--}}
        @if($errors->any())
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="alert alert-danger">{{ $error }}
                            <i class="fa fa-times" style="float: right "></i>
                        </li>
                    @endforeach
                </ul>
            </div>
        @elseif (session()->has('openCourse_Success'))
            <div class="alert alert-success">
                {{session()->pull('openCourse_Success', '')}}
                <i class="fas fa-check-square"></i>
            </div>
        @elseif (session()->has('Delete_Successfully'))
            <div class="alert alert-success">
                {{session()->pull('Delete_Successfully', '')}}
                <i class="fas fa-check-square"></i>
            </div>
        @elseif (session()->has('no_open_course'))
            <div class="alert alert-success">
                {{session()->pull('no_open_course', '')}}
                <i class="fas fa-check-square"></i>
            </div>
        @endif

        <div class="container">
            <div class="card" style="margin-bottom: 50px;">
                <div class="panel panel-danger" style="padding-top: 0;border: 3px solid #7cde9f;padding-bottom: 40px;">
                    <div class="headerName" >All Courses &nbsp;&nbsp;<i class="fas fa-graduation-cap"></i></div>
                    <div class="panel-body customBody row" style="padding: 20px 25px;"><br>

                        @if($courses->count())
                            @foreach($courses as $course)
                                @if($openedCourses->count())
                                    @foreach($openedCourses as $openedCourse)
                                        @if($course->id === $openedCourse->subject_id)
                                            <?php $check = 1; ?>
                                            @break
                                        @endif
                                    @endforeach
                                    @if($check === 0)
                                        <div class="col-xs-4">
                                            <a id="openCourse"  class="btn customCoursesAdding" style="padding-left: 15px !important;"
                                               data-toggle="model" data-target="openCourseModal" onclick="displayModel('{{$course->id}}','{{$course->name}}')">
                                                <i class="fas fa-plus-circle" style="margin-right: 6px;" ></i> {{$course->name}}
                                            </a>
                                        </div>
                                    @else
                                        <?php $check = 0; ?>
                                    @endif

                                @else
                                    <a id="openCourse"  class="btn customCoursesAdding" style="padding-left: 15px !important;"
                                       data-toggle="model" data-target="openCourseModal" onclick="displayModel('{{$course->id}}','{{$course->name}}');">
                                        <i class="fas fa-plus-circle" style="margin-right: 6px;" ></i> {{$course->name}}
                                    </a>
                                @endif
                            @endforeach
                        @else
                            <p style="text-align:center !important;  font-size: 14px;margin: 10px auto;">There No course Added to System Yet</p>
                        @endif

                        <br><br>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-bottom: 50px;">
                <div class="panel panel-danger" style="padding-top: 0;border: 3px solid #fbb7cf;padding-bottom: 40px;">
                    <div class="headerName" >Courses Added &nbsp;&nbsp;<i class="fas fa-plus-square"></i></div>
                    <div class="panel-body customBody row" style="padding: 20px 25px;"><br>
                        @if($openedCourses->count())
                            @foreach($openedCourses as $openedCourse)
                                @foreach($courses as $course)
                                    @if($course->id == $openedCourse->subject_id)
                                        <form method="POST" action="{{'/Panel/openCourses/'.$course->id}}"> {{csrf_field()}}
                                            <input type="hidden" name="_method" value="DELETE" >
                                            <button  class="btn customCoursesOpened">
                                                <i class="fas fa-minus-circle" style="margin-right: 6px;" ></i> {{$course->name}}
                                            </button>
                                        </form>
                                    @endif
                                @endforeach
                            @endforeach
                        @else
                            <p style="text-align:center !important;  font-size: 14px;margin: 10px auto;">There's No Course opened yet</p>
                        @endif
                        <br><br>
                    </div>
                </div>
            </div>

            <!-- The Modal -->
            <div id="openCourseModal" class="modal">
                <!-- Modal content -->
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 id="courseName"></h2>
                        <span id="close_model" class="close" style="color: white;font-size: 40px;color: #0a0a0a !important;opacity: 1;text-shadow: 2px -2px 3px #343a40;">&times;</span>
                    </div>
                    <div class="modal-body">
                        <div class="form-group form_style">
                            <form id="openCourseForm" method="post" action="{{'/Panel/openCourses'}}" style="padding: 15px;">
                                @csrf
                                <div class="form-group row">
                                    <label for="LectureNumber" class="col-4 col-form-label">Number of Lectures:</label>
                                    <input id="LectureNumber" type="number" class="form-control col-8"  placeholder="Enter Number of Lectures"
                                           name="LectureNumber" max="3" min="1" required>
                                </div>

                                <div class="form-group row">
                                    <label for="sectionNumber" class="col-4 col-form-label">Number of Sections:</label>
                                    <input id="sectionNumber" type="number" class="form-control col-8"  placeholder="EnterNumber of Sections"
                                           name="sectionNumber" max="5" min="1" required>
                                </div>

                                <div class="form-group row">
                                    <label for="Leader" class="col-4 col-form-label">Professor Leader :</label>
                                    <select id="Leader" name="Leader" required class="col-8 form-control" style="display: block !important;">
                                        <option disabled="" selected="" value="No">Please Select Leader </option>
                                        @foreach($Doctors as $doctor)
                                            <option value="{{$doctor->userable_id}}">{{$doctor->name_en}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div hidden class="form-group row">
                                    <label for="selectedCourse">SelectedCourse</label>
                                    <input hidden id="selectedCourse" name="selectedCourse">
                                </div>
                                <br>
                                <div class="form-group row" style="margin-bottom: 0;">
                                    <button id="" class="btn btn-block btn-outline-dark">Save &nbsp;&nbsp; <i class="fas fa-graduation-cap"></i></button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

    @else
        <div class="container">
            <div class="panel-group">
                <div class="panel panel-success">
                    <div class="panel-heading customHeading">
                        Courses </div>
                    <div class="panel-body customBody">
                        <br>
                        @if($openedCourses->count())
                            @foreach($openedCourses as $openedCourse)
                                @foreach($courses as $course)
                                    @if($course->id == $openedCourse->subject_id)
                                        <a class="btn btn-primary customCoursesOpened">
                                            {{$course->name}}
                                        </a>
                                    @endif
                                @endforeach
                            @endforeach
                        @else
                            There's No Course opened yet
                        @endif
                        <br><br>
                    </div>
                </div>
            </div>
        </div>
    @endif

@else
    <div class="container">
        <div class="panel-group">
            <div class="panel panel-success">
                <div class="panel-heading customHeading">
                    No Semester Found ! <br>
                    Please open Semester and come back again!
                </div>
            </div>
        </div>
    </div>
@endif
