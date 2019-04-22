function active() {
    var conceptName = $('#courseName').find(":selected").val();

    if(conceptName === ''){
        $('#errorMessage').show();
    }
    else{
        $('#errorMessage').hide();
        $('#selectCourseForm').get(0).setAttribute('action', '/Panel/Student/Classroom/'+conceptName).submit();
    }
}