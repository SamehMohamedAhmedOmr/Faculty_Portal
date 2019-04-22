<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Student_affair;
use App\Subject;
use App\User;

/*Main Pages*/
Route::get('/home', 'HomeController@Home')->name('home');
Route::get('/events','HomeController@event');
Route::get('/gallery', 'HomeController@gallery');

// login & Authentication [ Middleware  , guard , providers ]
Route::get('/login','Auth\loginController@login');
Route::post('/login','Auth\loginController@authenticate')->name('login');
Route::get('/logout','Auth\loginController@logout');

//userController
Route::get('/profile','userController@profile');
Route::post('/profile','userController@update_profile');

/*Experience*/
Route::resource('/Panel/experience','ExperienceController')->middleware('auth');
/*Manage places*/
Route::resource('/Panel/managePlaces','managePlacesController')->middleware('auth');
/*Manage courses*/
Route::resource('/Panel/manageCourses','manageCourse')->middleware('auth');
/*Manage Semesters*/
Route::resource('/Panel/manageSemester','manageSemester')->middleware('auth');
/*Manage Emails*/
Route::get('/Email/inbox','EmailController@inboxMail')->middleware('auth');
Route::get('/Email/Send','EmailController@sendMail')->middleware('auth');
Route::post('/Email/store','EmailController@storeMail');
/*Manage open courses*/
Route::resource('/Panel/openCourses','openCoursesController')->middleware('auth');
/*Manage TimeTable*/
Route::get('/Panel/TimeTable','TimeTableController@index')->middleware('auth');
Route::get('/Panel/TimeTable/{id}','TimeTableController@show')->middleware('auth');
Route::get('/Panel/TimeTable/course/{c_id}/{c_Name}/{S_id}/{av_lecture}/{ac_section}','TimeTableController@TimeTableCourse')->middleware('auth');
Route::post('/Panel/TimeTable/store','TimeTableController@store')->middleware('auth');
Route::post('/Panel/TimeTable/{id}','TimeTableController@destroy')->middleware('auth');
/*Manage Registration*/
Route::get('/Panel/registrations','StudentController@manageRegistration')->middleware('auth');
Route::get('/Panel/registrations/{id}','StudentController@showRegistration')->middleware('auth');
Route::post('/Panel/registrations','StudentController@registrationMail')->middleware('auth');
Route::get('/Panel/registrations/student/{id}','StudentController@studentRegistration')->middleware('auth');

Route::get('/Panel/newRegistration/{id}','StudentController@register')->middleware('auth');
Route::post('/Panel/newRegistration/{id}','StudentController@registrationSubmit')->middleware('auth');
Route::get('/Panel/registrations/drop/{subjID}/{stuID}','StudentController@dropCourse')->middleware('auth');
/*Timetable*/
Route::get('/Panel/timetable/view','TimeTableController@view');
/*Register Courses [Student]*/
Route::resource('/Panel/registerCourses','registerCoursesController')->middleware('auth');
/*Manage Courses Leading in this semester*/
Route::get('/Panel/Doctor/manageCoursesDistribution','DoctorController@Manage_Course_distribution')->middleware('auth');
Route::post('/Panel/Doctor/manageCoursesDistribution','DoctorController@Save_Course_distribution')->middleware('auth');
/*Manage courses that doctor teach in this semester*/
Route::get('/Panel/Doctor/ManageCourse','DoctorController@ManageCourseIndex')->middleware('auth');;
Route::post('/Panel/Doctor/ManageCourse/showCourses/{id}','DoctorController@ManageCourse_showCourses')->middleware('auth');;
Route::post('/Panel/Doctor/ManageCourse/updateGrades','DoctorController@updateGrades')->middleware('auth');
Route::get('/exportStudent_Doc/{courseID}/{semesterID}','ExcelController@exportStudent_Doc')->middleware('auth');
Route::post('/Panel/Doctor/UploadFile','FileController@DoctorUploadMaterial')->middleware('auth');
Route::post('/Panel/Doctor/RemoveFile','FileController@DoctorRemoveFile')->middleware('auth');
Route::post('/Panel/Doctor/NotifyStudents','DoctorController@NotifyStudents')->middleware('auth');
/*Doctor set final grades*/
Route::get('/panel/Doctor/ManageFinalGrades','DoctorController@Final_grades_view');
Route::post('/panel/Doctor/ManageFinalGrades','DoctorController@save_final_grades');
/*Evaluate Courses [Student]*/
Route::resource('/Panel/Student/EvaluateCourses','evaluate_course')->middleware('auth');
/*Manage Course That Instructor teach*/
Route::get('/Panel/Instructor/ManageCourse','TeacherAssistantController@ManageCourseIndex')->middleware('auth');
Route::post('/Panel/Instructor/ManageCourse/showCourses/{id}','TeacherAssistantController@ManageCourse_showCourses')->middleware('auth');;
Route::post('/Panel/Instructor/ManageCourse/updateGrades','TeacherAssistantController@updateGrades')->middleware('auth');
Route::post('/Panel/Instructor/NotifyStudents','TeacherAssistantController@NotifyStudents')->middleware('auth');
Route::get('/exportStudent_Instructor/{courseID}/{semesterID}','ExcelController@exportStudent_Instructor')->middleware('auth');
/*Manage MidtermSchedule*/
Route::get('/Panel/MidtermSchedule','StudentAffairsController@MidtermSchedule')->middleware('auth');
Route::post('/Panel/MidtermSchedule/delete','StudentAffairsController@removeMidtermSchedule')->middleware('auth');
Route::post('/Panel/MidtermSchedule/Add','StudentAffairsController@AddMidtermSchedule')->middleware('auth');
/*Manage Practical Schedule*/
Route::get('/Panel/Practical','StudentAffairsController@PracticalSchedule')->middleware('auth');
Route::post('/Panel/Practical/Add','StudentAffairsController@AddPracticalSchedule')->middleware('auth');
Route::post('/Panel/Practical/delete','StudentAffairsController@removePracticalSchedule')->middleware('auth');
/*FinalSchedule*/
Route::get('/Panel/FinalSchedule','StudentAffairsController@FinalSchedule')->middleware('auth');
Route::post('/Panel/FinalSchedule/Add','StudentAffairsController@AddFinalSchedule')->middleware('auth');
Route::post('/Panel/FinalSchedule/remove','StudentAffairsController@removeFinalSchedule')->middleware('auth');
/*show Schedule Register*/
Route::get('/Panel/student/showSchedule','StudentController@showSchedule')->middleware('auth');
Route::get('/Panel/Doctor/showSchedule','DoctorController@showSchedule')->middleware('auth');
Route::get('/Panel/Instructor/showSchedule','TeacherAssistantController@showSchedule')->middleware('auth');
/* Show ClassRoom [Student]*/
Route::resource('/Panel/Student/Classroom','studentClassRoom')->middleware('auth');
/* Show Transcript [Student]*/
Route::resource('/Panel/Student/Transcript','TranscriptController')->middleware('auth');
/*Events*/
Route::resource('/events','EventController');
/*Gallary*/
Route::resource('/gallery/albums','AlbumController');
Route::get('/gallery/upload/{id}', 'FileController@uploadForm');
Route::post('/gallery/upload', 'FileController@uploadSubmit');
Route::get('/gallery/delete/{id}', 'FileController@removePhoto');
/*show statistics*/
Route::get('/Panel/Admin/Statistics/1','AdminStatisticsController@studentInEachLevel')->middleware('auth');
Route::get('/Panel/Admin/Statistics/2','AdminStatisticsController@registeredStudent')->middleware('auth');
Route::get('/Panel/Admin/Statistics/3','AdminStatisticsController@semesterResult')->middleware('auth');
Route::get('/Panel/Admin/Statistics/4','AdminStatisticsController@averageGPA')->middleware('auth');
Route::get('/Panel/S_A/updateLevels','StudentController@updateLevels')->middleware('auth');

//_____________________________________________________________________________________________

/* Start Admin Controller*/
Route::resource('/Panel/Admin','AdminController')->middleware('auth');
/* Start S_A Controller*/
Route::resource('/Panel/SA','StudentAffairsController')->middleware('auth');
/* Start T_A Controller */
Route::resource('/Panel/TA','TeacherAssistantController')->middleware('auth');
/*Start Doc Controller*/
Route::get('/Panel/Doctor','DoctorController@index')->middleware('auth');
Route::get('/Panel/Doctor/create','DoctorController@create')->middleware('auth');
Route::get('/Panel/Doctor/{id}','DoctorController@show')->middleware('auth');
Route::get('/Panel/Doctor/{id}/edit','DoctorController@edit')->middleware('auth');

Route::post('/Panel/Doctor','DoctorController@store')->middleware('auth');
Route::post('/Panel/Doctor/{id}','DoctorController@destroy')->middleware('auth');
Route::post('/Panel/Doctor/update/{id}','DoctorController@update')->middleware('auth');

/*start Student Controller*/
Route::get('/Panel/Student','StudentController@index')->middleware('auth');
Route::get('/Panel/Student/create','StudentController@create')->middleware('auth');
Route::get('/Panel/Student/{id}','StudentController@show')->middleware('auth');
Route::get('/Panel/Student/{id}/edit','StudentController@edit')->middleware('auth');

Route::post('/Panel/Student','StudentController@store')->middleware('auth');
Route::post('/Panel/Student/{id}','StudentController@destroy')->middleware('auth');
Route::post('/Panel/Student/update/{id}','StudentController@update')->middleware('auth');



// Doctor Panel with full Authentication and Authorization
Route::get('/Doctor/Panel',function ()
{
    if(Auth::user()->userable_type == 'Doc') { return view('Portal.Doctor_panel.Panel'); }
    else { return view('Portal.public.index');}
})->middleware('auth');

// Instructor Panel with full Authentication and Authorization
Route::get('/Instructor/Panel',function ()
{
    if(Auth::user()->userable_type == 'T_A') { return view('Portal.Instructor_panel.Panel'); }
    else { return view('Portal.public.index');}
})->middleware('auth');
// student Affair panel with full Authentication and Authorization
Route::get('/StudentAffair/Panel',function ()
{
    if(Auth::user()->userable_type == 'S_A') { return view('Portal.studentAffair_panel.Panel'); }
    else { return view('Portal.public.index');}
})->middleware('auth');
// student panel with full Authentication and Authorization
Route::get('/student/panel', function()
{
    if(Auth::user()->userable_type == 'Stu') { return view('Portal.student_panel.Panel'); }
    else { return view('Portal.public.index');}
})->middleware('auth');


