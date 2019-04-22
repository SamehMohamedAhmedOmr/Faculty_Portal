/*Show TimeTable & openCourses*/
$('#ManageSemester').click(function()
{
    /*get selected id in select -> option tag*/
   var getSelectedID= $('#selectSemester').find(":selected").val();

   /*Call show function in TimeTableController */

    $.ajax({
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        },
        type : 'get',
        url: "/Panel/TimeTable/"+getSelectedID,
        success:function(data){
            if(data !='no') {
                console.log('data');
                $('.ManageSemester').html('').append(data);
                $(".set").parent().css({"background-color": "#ddd"});
            }
        },
        error: function (data){
            console.log(data);
            $('.ManageSemester').html('').append(data);
        }
    });
});

/* show TImeTable Create Form */
function showCreateForm(c_id , c_name , s_id , AvilabelLectures , AvilabelSections)
{
    /*Call show function in TimeTableController */
    $.ajax({
        type : 'get',
        url: "/Panel/TimeTable/course/"+c_id+"/"+c_name+"/"+s_id+"/"+AvilabelLectures+"/"+AvilabelSections,
        success:function(data){
            $('.TimeTableCreate').html('').append(data);
            $('#overlay').show();
            $('.TimeTableCreate').show();
            $('.TimeTableCreate').css({top:'17%',left:'21%',margin:'-'+($('#myDiv').height() / 2)+'px 0 0 -'+($('#myDiv').width() / 2)+'px'});
        },
        error: function (data){
            console.log(data);
        }
    });
}

/*close TIme table create form*/
function closeTImeTableform ()
{
    $('.TimeTableCreate').hide();
    $('#overlay').hide();
}

/*show create form parts (doctor part ) or (instructor part)*/
function showFormParts (check)
{
    if(check=='Doc')
    {
        $('.doc_field').show();
        $('.days').show();
        $('.hours').show();
        $('.TimeTableSaveButton').show();
        $('.AT_field').hide();
    }
    else if (check=='T_A')
    {
        $('.AT_field').show();
        $('.days').show();
        $('.hours').show();
        $('.TimeTableSaveButton').show();
        $('.doc_field').hide();
    }
}
