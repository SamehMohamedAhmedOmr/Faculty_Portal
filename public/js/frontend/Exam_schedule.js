/*Add Midterm Schedule*/
function AddMidtermExam(subjectName , Subject_id , stud_Num)
{
    $('#overlay').show();
    $('.TimeTableCreate .subjectName').html(subjectName);
    $('.TimeTableCreate .alert').html('* capacity of halls must be larger than number of student register course ( '+ stud_Num+' )  <i style="float: right;" class="fas fa-exclamation-triangle"></i>  ' );
    $('.TimeTableCreate #AddMidtermSchedule').val(Subject_id);
    $('.TimeTableCreate #studentNUm').val(stud_Num);
    $('.TimeTableCreate').show();
}
/*Add Practical Exam*/
function AddPracticalExam (subjectName , Subject_id , stud_Num)
{
    $('#overlay').show();
    $('.TimeTableCreate .subjectName').html(subjectName);
    $('.TimeTableCreate .alert').html('* capacity of labs must be larger than number of student register course ( '+ stud_Num+' )  <i style="float: right;" class="fas fa-exclamation-triangle"></i>  ' );
    $('.TimeTableCreate #AddPracticalSchedule').val(Subject_id);
    $('.TimeTableCreate #studentNUm').val(stud_Num);
    $('.TimeTableCreate').show();
}
function AddFinalExam(subjectName , Subject_id , stud_Num , subject_hours)
{
    $('#overlay').show();
    $('.TimeTableCreate .subjectName').html(subjectName);
    $('.TimeTableCreate .alert').html('* capacity of halls must be larger than number of student register course ( '+ stud_Num+' )  <i style="float: right;" class="fas fa-exclamation-triangle"></i>  ' );
    $('.TimeTableCreate #AddFinalSchedule').val(Subject_id);
    $('.TimeTableCreate #studentNUm').val(stud_Num);
    $('.TimeTableCreate #subject_hours').val(subject_hours);
    $('.TimeTableCreate').show();
}
/*delete Midterm Schedule*/
function deleteMIdtermSchedule(x)
{
    $.confirm({
        title: 'Are you sure !',
        content: 'All exam schedule related to this course will delete permanently , continue !',
        animationBounce: 2.5,
        animationSpeed: 600,
        theme: 'supervan',
        icon: 'fas fa-question-circle',
        buttons: {
            confirm: function () {
                $(x).closest('form').submit();
            },
            cancel: function () {

            }
        }
    });
}
/*add halls*/
function addMidtermHalls()
{
    $('#HallPlaces').append('<label class="col-4 col-form-label onHallLable">Select Hall</label>');
    $('#HallPlaces').append('<select class="form-control col-7 oneHall" id="Hall[]"  style="z-index: 9999 !important;" name="Hall[]">');
    $('#HallPlaces select:last').append($('.oneHall > option').clone());
    $('#HallPlaces select:last').append('</select><br>');
    $('#AddHalls').hide();
}
/*Add Labs*/
function addMidtermLabs ()
{
    $('#labPlaces').append('<label class="col-4 col-form-label onLable">Select Lab</label>');
    $('#labPlaces').append('<select class="form-control col-7 onelab" id="lab[]"  style="z-index: 9999 !important;" name="lab[]">');
    $('#labPlaces select:last').append($('.onelab > option').clone());
    $('#labPlaces select:last').append('</select><br>');
    $('#Addlab').hide();
}

$(window).ready(function()
{
    /*Add Hall (plus) */
    $('[id^=Hall]').change(function()
    {
        var stu_num = $('#studentNUm').val();
        var TotalCapacity=0;
        $('select[name="Hall[]"]').each(function() {
            var Capacity = $(this).find(':selected').next().val();
            TotalCapacity=TotalCapacity+parseInt(Capacity);
        });
        if(TotalCapacity<stu_num)
        { $('#AddHalls').show();}
        else {$('#AddHalls').hide();}
    });

    /*Add Lab (plus)*/
    $('[id^=lab]').change(function()
    {
        var stu_num = $('#studentNUm').val();
        var TotalCapacity=0;
        $('select[name="lab[]"]').each(function() {
            var Capacity = $(this).find(':selected').next().val();
            TotalCapacity=TotalCapacity+parseInt(Capacity);
        });
        if(TotalCapacity<stu_num)
        { $('#Addlab').show();}
        else {$('#Addlab').hide();}
    });
});



/*close TIme table create form*/
function closeTImeTableform ()
{
    $('.TimeTableCreate').hide({ direction: "right" }, 1500);

    $('.removeCOntainer').not(':first').css('display','none');
    $('#overlay').hide();
}
