<br>

@if($check == true)

    @if(count($currentSemester) > 0)
        @if($currentSemester[count($currentSemester)-1]->final_monitoring_grades_date < \Carbon\Carbon::now())
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

            @if(session()->has('EvaluateSuccessfully'))
                <div class="alert alert-success">
                    {{session()->pull('EvaluateSuccessfully', '')}}
                    <i class="fa fa-times" style="float: right "></i>
                </div>
            @endif

            <div class="container">

                @if(count($selectedCourses) === 0)
                    <div class="clearfix"></div>
                    <div class="Not_Found">You are Complete all evaluated request .. thank you </div>
                @else
                    <form method="post" action="{{action('evaluate_course@store')}}">
                        @csrf
                        {{ csrf_field() }}

                        <h2 style="color: #2c4167;">Evaluate Course</h2>
                        <div class="form-group row">
                            <label for="selectedCourse" class="col-2 col-form-label" style="color: #2c4167; font-weight: bold;">Course :</label>
                            <select id="selectedCourse" name="selectedCourse" class="form-control col-10" required>
                                @foreach($selectedCourses as $selectedCourse)
                                    <option  value="{{$selectedCourse->subject_id}}">
                                        {{$selectedCourse->subject->subject->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <br>
                        <div >
                            <div class="survey">
                                <div class="surveyContent">
                                    <h5 class="surveyHeader">
                                        1 - The instructor clearly presented the skills, the tools and concepts and techniques to be learned.
                                    </h5>
                                    <label class="radio-inline survey_option" style="">
                                        <input type="radio" name="survey1" value="5" required> Extremely satisfied</label>
                                    <label class="radio-inline survey_option">
                                        <input type="radio" name="survey1" value="4" required> Very Satisfied</label>
                                    <label class="radio-inline survey_option">
                                        <input type="radio" name="survey1" value="3" required> Somewhat satisfied</label>
                                    <label class="radio-inline survey_option">
                                        <input type="radio" name="survey1" value="2" required> Not so satisfied</label>
                                    <label class="radio-inline survey_option">
                                        <input type="radio" name="survey1" value="1" required> Not at all satisfied</label>
                                </div>
                            </div>

                            <div class="survey">
                                <div class="surveyContent">
                                    <h5 class="surveyHeader">
                                        2 - The instructor helped me achieve my goals.</h5>
                                    <label class="radio-inline survey_option" style="margin-right: 20px;">
                                        <input type="radio" name="survey2" value="5" required> Extremely satisfied</label>
                                    <label class="radio-inline survey_option" style="margin-right: 20px;">
                                        <input type="radio" name="survey2" value="4" required> Very Satisfied</label>
                                    <label class="radio-inline survey_option" style="margin-right: 20px;">
                                        <input type="radio" name="survey2" value="3" required> Somewhat satisfied</label>
                                    <label class="radio-inline survey_option" style="margin-right: 20px;">
                                        <input type="radio" name="survey2" value="2" required> Not so satisfied</label>
                                    <label class="radio-inline survey_option" style="margin-right: 20px;">
                                        <input type="radio" name="survey2" value="1" required> Not at all satisfied</label>
                                </div>
                            </div>

                            <div class="survey">
                                <div class="surveyContent">
                                    <h5 class="surveyHeader">
                                        3 - The instructor encouraged student questions and participation.</h5>
                                    <label class="radio-inline survey_option" style="margin-right: 20px;">
                                        <input type="radio" name="survey3" value="5" required> Extremely satisfied</label>
                                    <label class="radio-inline survey_option" style="margin-right: 20px;">
                                        <input type="radio" name="survey3" value="4" required> Very Satisfied</label>
                                    <label class="radio-inline survey_option" style="margin-right: 20px;">
                                        <input type="radio" name="survey3" value="3" required> Somewhat satisfied</label>
                                    <label class="radio-inline survey_option" style="margin-right: 20px;">
                                        <input type="radio" name="survey3" value="2" required> Not so satisfied</label>
                                    <label class="radio-inline survey_option" style="margin-right: 20px;">
                                        <input type="radio" name="survey3" value="1" required> Not at all satisfied</label>
                                </div>
                            </div>

                            <div class="survey">
                                <div class="surveyContent">
                                    <h5 class="surveyHeader">
                                        4 - The course was effectively organized.</h5>
                                    <label class="radio-inline survey_option" style="margin-right: 20px;">
                                        <input type="radio" name="survey4" value="5" required> Extremely satisfied</label>
                                    <label class="radio-inline survey_option" style="margin-right: 20px;">
                                        <input type="radio" name="survey4" value="4" required> Very Satisfied</label>
                                    <label class="radio-inline survey_option" style="margin-right: 20px;">
                                        <input type="radio" name="survey4" value="3" required> Somewhat satisfied</label>
                                    <label class="radio-inline survey_option" style="margin-right: 20px;">
                                        <input type="radio" name="survey4" value="2" required> Not so satisfied</label>
                                    <label class="radio-inline survey_option" style="margin-right: 20px;">
                                        <input type="radio" name="survey4" value="1" required> Not at all satisfied</label>
                                </div>
                            </div>

                            <div class="survey">
                                <div class="surveyContent">
                                    <h5 class="surveyHeader">
                                        5 - The course (or section) gave me a deeper insight into the topic.</h5>
                                    <label class="radio-inline survey_option" style="margin-right: 20px;">
                                        <input type="radio" name="survey5" value="5" required> Extremely satisfied</label>
                                    <label class="radio-inline survey_option" style="margin-right: 20px;">
                                        <input type="radio" name="survey5" value="4" required> Very Satisfied</label>
                                    <label class="radio-inline survey_option" style="margin-right: 20px;">
                                        <input type="radio" name="survey5" value="3" required> Somewhat satisfied</label>
                                    <label class="radio-inline survey_option" style="margin-right: 20px;">
                                        <input type="radio" name="survey5" value="2" required> Not so satisfied</label>
                                    <label class="radio-inline survey_option" style="margin-right: 20px;">
                                        <input type="radio" name="survey5" value="1" required> Not at all satisfied</label>
                                </div>
                            </div>

                            <div class="survey">
                                <div class="surveyContent">
                                    <h5 class="surveyHeader">
                                        6 - How many class (or section) sessions did you attend ? </h5>
                                    <label class="radio-inline survey_option" style="margin-right: 50px;">
                                        <input type="radio" name="survey6" value="5" required> All</label>
                                    <label class="radio-inline survey_option" style="margin-right: 50px;">
                                        <input type="radio" name="survey6" value="4" required> More than 5</label>
                                    <label class="radio-inline survey_option" style="margin-right: 50px;">
                                        <input type="radio" name="survey6" value="3" required> Twice</label>
                                    <label class="radio-inline survey_option" style="margin-right: 50px;">
                                        <input type="radio" name="survey6" value="2" required> Once</label>
                                    <label class="radio-inline survey_option" style="margin-right: 50px;">
                                        <input type="radio" name="survey6" value="1" required> Didn't attend</label>
                                </div>
                            </div>
                        </div>
                        <button type="submit"  class="btn btn-block btn-outline-info" >Submit</button>
                    </form>
                @endif
            </div>
        @else
            <div class="clearfix"></div>
            <div class="Not_Found">You are Blocked form Evaluate Now .. try after semester end </div>
        @endif
    @else
        <div class="clearfix"></div>
        <div class="Not_Found">No semester Yet</div>
    @endif

@else
    <div class="clearfix"></div>
    <div class="Not_Found">No semester Yet</div>
@endif
