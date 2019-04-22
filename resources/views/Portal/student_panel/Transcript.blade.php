<br>

<div class="container">
    <h3 class="headerName">Student Transcript</h3><br>
    @php $check =0; @endphp

@if(count($currentSemester)!=0)
    @if( \Carbon\Carbon::now() <= $currentSemester[0]->final_monitoring_grades_date	 &&  \Carbon\Carbon::now() >= $currentSemester[0]->end_date	)
        <div class="clearfix"></div>
        <div class="Not_Found"> You are blocked Temporarily from access Transcript due to Faculty Rule. </div>
            @php $check =1; @endphp
    @endif
@endif
    @if($check === 0)
    <div class="transcriptBorder">

        <div class="row">
            <div class="col-lg-6 student_info">

                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th class="student_title">Student Name</th>
                        <td class="student_entire_info">{{$student->name_en}}</td>
                    </tr>
                    <tr>
                        <th class="student_title">ID</th>
                        <td class="student_entire_info">{{$student->userable->id}}</td>
                    </tr>
                    <tr>
                        <th class="student_title">Specialization</th>
                        <td class="student_entire_info">{{$student->userable->department->name}}</td>
                    </tr>
                    </tbody>
                </table>

            </div>

            <div class="col-lg-5 student_table_state_style">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th class="student_title">GPA</th>
                        <th class="student_title">Grade</th>
                        <th class="student_title">Hours</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        @if($calculated != null)
                        <td class="student_entire_info">{{$calculated[0]}}</td>
                        <td class="student_entire_info">
                            @if($calculated[0] >= 3.4) {{'Excellent'}}
                            @elseif($calculated[0] >= 2.8 && $calculated[0] < 3.4) {{'Very Good'}}
                            @elseif($calculated[0] >= 2.4 && $calculated[0] < 2.8) {{'Good'}}
                            @elseif($calculated[0] >= 2 && $calculated[0] < 2.4) {{'Satisfactory'}}
                            @elseif($calculated[0] >= 1.4 && $calculated[0] < 2) {{'Weak'}}
                            @elseif($calculated[0] < 1.4) {{' Very Weak'}}
                            @endif
                        </td>
                        <td class="student_entire_info">{{$calculated[1]}}</td>
                        @else
                            <td class="student_entire_info">Not-Defined yet</td>
                            <td class="student_entire_info">Not-Defined yet</td>
                            <td class="student_entire_info">0</td>
                        @endif
                    </tr>
                    </tbody>
                </table>

                <table class="table table-bordered status_style">
                    <tbody>
                    <tr>
                        <th class="student_title">Status</th>
                        <td class="student_entire_info">
                            @if($student->userable->graduated_status === 0)
                                {{'Graduated'}}
                            @else
                                {{'Level '.$student->userable->graduated_status}}
                            @endif
                        </td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>

        <hr class="hrStyle">
        <div class="student_subject_info">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th class="student_title">Course Name</th>
                    <th class="student_title">Hours</th>
                    <th class="student_title">Total</th>
                    <th class="student_title">Grade</th>
                </tr>
                </thead>
                <tbody>

                @foreach($availableCourses as $availableCourse)
                    @php  $check = 0;  $allCourseGrades = []; @endphp

                        @foreach($grades as $grade)
                            @if($availableCourse->id === $grade->subject_id)
                                    @php $check = 1;   $allCourseGrades [] = $grade; @endphp
                             @endif
                        @endforeach

                        @foreach($allCourseGrades as $allCourseGrade)
                            <tr>
                                <td @if($allCourseGrade->total_grade >= 50) class="success" @else class="fail" @endif >
                                    {{$availableCourse->name}}
                                <td @if($allCourseGrade->total_grade >= 50) class="success" @else class="fail" @endif >
                                    {{$availableCourse->credit_hours}}
                                </td>
                                <td @if($allCourseGrade->total_grade >= 50) class="success" @else class="fail" @endif >
                                    {{$allCourseGrade->total_grade}}
                                </td>
                                <td @if($allCourseGrade->total_grade >= 50) class="success" @else class="fail" @endif >
                                     {{-- Logic to Define A, A+ B , B+ --}}
                                    @if($allCourseGrade->total_grade < 50)
                                        {{'F'}}
                                    @elseif($allCourseGrade->total_grade >= 50 && $allCourseGrade->total_grade < 60)
                                        {{'D'}}
                                    @elseif($allCourseGrade->total_grade >= 60 && $allCourseGrade->total_grade < 65)
                                        {{'D+'}}
                                    @elseif($allCourseGrade->total_grade >= 65 && $allCourseGrade->total_grade < 70)
                                        {{'C'}}
                                    @elseif($allCourseGrade->total_grade >= 70 && $allCourseGrade->total_grade < 75)
                                        {{'C+'}}
                                    @elseif($allCourseGrade->total_grade >= 75 && $allCourseGrade->total_grade < 80)
                                        {{'B'}}
                                    @elseif($allCourseGrade->total_grade >= 80 && $allCourseGrade->total_grade < 85)
                                        {{'B+'}}
                                    @elseif($allCourseGrade->total_grade >= 85 && $allCourseGrade->total_grade < 90)
                                        {{'A'}}
                                    @elseif($allCourseGrade->total_grade >= 90)
                                        {{'A+'}}
                                    @endif
                                </td>
                             </tr>
                        @endforeach

                        @if($check === 0)
                            <tr>
                                <td class="student_entire_info">{{$availableCourse->name}}</td>
                                <td class="student_entire_info">{{$availableCourse->credit_hours}}</td>
                                <td class="student_entire_info"></td>
                                <td class="student_entire_info"></td>
                            </tr>
                        @endif
                @endforeach

                </tbody>
            </table>
        </div>

    </div>
    @endif
</div>

