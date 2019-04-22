$(document).ready(function() {

    $("#editPlace").click(function() {
        $("#placeName").prop('disabled', false);
        $("#placeSeats").prop('disabled', false);
        $('#labelType').hide();
        $('#newType').show();
        $('#editPlace').hide();
        $('#submitEdit').show();
        $('.EditPlaceLabels').hide();
    });

    $("#editCourse").click(function() {
        $("#courseName").prop('disabled', false);
        $("#courseDescription").prop('disabled', false);
        $("#Credit").prop('disabled', false);

        $('#course_grade').hide();
        $('#gradeDiv').show();

        $('#course_level').hide();
        $('#levelDiv').show();

        $('#course_department').hide();
        $('#departmentDiv').show();

        $('#course_prerequisite').hide();
        $('#prerequisiteDiv').show();

        $('#editCourse').hide();
        $('#submitEdit').show();
    });

    $("#editSemester").click(function() {
        $("#semesterName").prop('disabled', false);
        $("#Start_Date").prop('disabled', false);
        $("#End_Date").prop('disabled', false);
        $("#Midterm").prop('disabled', false);

        $('#semester_type').hide();
        $('#typeDiv').show();

        $('#semester_Ststus').hide();
        $('#statusDiv').show();

        $('#editSemester').hide();
        $('#submitEdit').show();
    });

    /* New => Task 1 */
    $("#close_model").click(function() {

        var modal = document.getElementById('openCourseModal');
        // When the user clicks on <span> (x), close the modal
        modal.style.display = "none";
    });


});

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    var modal = document.getElementById('openCourseModal');
    if (event.target === modal) {
        modal.style.display = "none";
    }
};

function displayModel (courseId,courseName) {
    var modal = document.getElementById('openCourseModal');
    // When the user clicks the button, open the modal
    modal.style.display = "block";
    $("h2").text(courseName);
    $("#selectedCourse").val(courseId);
}

