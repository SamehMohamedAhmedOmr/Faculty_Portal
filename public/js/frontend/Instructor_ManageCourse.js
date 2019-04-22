$(window).ready(function () {

    $('#SemesterName').on('change', function () {

        var SemesterID = this.value;

        /*Select All courses related to this semester*/
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: "/Panel/Instructor/ManageCourse/showCourses/" + SemesterID,
            success: function (data) {
                $('.courseConatiner').show();
                $('#ManageCourseButton').show();

                $('.coursesList').html('');
                $('.coursesList').append("<select name='courseID' class='form-control' aria-describedby='Semester' >");
                $('.coursesList select').append(data);
                $('.coursesList').append("</select>");
                $(".coursesList select").css("height", "52px");
            },
            error: function (data) {
                console.log(data);
            }
        });
    });

    // on click open compose Message
    $('.NotfiyStudent').click(function () {
        overlay = jQuery('<div id="overlay"> </div>');
        overlay.appendTo(document.body);
        $('.composeMessage').show();
    });
    // on click hide compose message
    $('.composeMessage .close').click(function () {
        $("#overlay").remove();
        $('.composeMessage').hide({ direction: "right" }, 1500);
    });

});

function setGrades(x)
{
    var Sem_id = $(x).closest('tr').find("input[name=sem_id]").val();
    var stu_id = $(x).closest('tr').find("input[name=stu_id]").val();
    var courseID = $(x).closest('tr').find("input[name=courseID]").val();
    var section = $(x).closest('tr').find("input[name=section]").val();

    /*Select All courses related to this semester*/
    $.ajax({
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        },
        type : 'post',
        url: "/Panel/Instructor/ManageCourse/updateGrades",
        data: {
            "section": section,
            "courseID":courseID ,
            "Sem_id" : Sem_id ,
            "stu_id" :stu_id
        },
        success:function(data){
            if(data =='success')
            {
                $(x).find('.checkGrades').removeClass('fa-pencil-alt').addClass('fa-check-circle').css('color','#fff').css('font-size','17px');
                setTimeout(function() {
                    $(x).find('.checkGrades').addClass('fa-pencil-alt').css('color','#fff');
                }, 2000);
            }
            else
            {
                $(x).find('.checkGrades').removeClass('fa-pencil-alt').addClass('fa-times-circle').css('color','#f00').css('font-size','17px');
                $(x).find('.checkGrades').attr('data-tooltip', data);
                setTimeout(function() {
                    $(x).find('.checkGrades').removeClass('fa-times-circle').addClass('fa-pencil-alt').css('color','#fff');
                }, 5000);

                $.alert({
                    title: 'Failed to Update Grades !!',
                    type: 'orange',
                    icon:'fas fa-exclamation-triangle',
                    content: data,
                });
            }

        },
        error: function (data){
            console.log(data);
        }
    });

}
