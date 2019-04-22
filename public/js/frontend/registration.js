var getDepID="";
var getLevel="";
$('#ManageRegistration').click(function()
{
    getDepID= $('#mng_reg_dep').find(":selected").val();
    getLevel= $('#mng_reg_level').find(":selected").val();
    $.ajax({
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        },
        type : 'get',
        url: "/Panel/registrations/"+getDepID+"*"+getLevel,
        success:function(data){
                console.log('data');
                $('.ManageRegistration').html('').append(data);
                $(".set").parent().css({"background-color": "#ddd"});
        },
        error: function (data){
            console.log(data);
            $('.ManageRegistration').html('').append(data);
        }
    });
});

function reg_student_search(value) {
    console.log(value);
    $.ajax({
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        },
        type : 'get',
        url: "/Panel/registrations/"+value+"*"+getDepID+"*"+getLevel,
        data:{'reg_search':value+"*"+getDepID+"*"+getLevel},
        success:function(data){
            $('.ManageRegistrationTable').html('').append(data);
        },
        error: function ()
        {
            console.log('fail');
        }
    });
}

function reg_student_selectAll(){
    $('.cb-element').prop('checked',true);
    ctrlShowBtn();
}
function reg_student_unselectAll(){
    $('.cb-element').prop('checked',false);
    ctrlShowBtn();
}
function reg_student_regestered(){
    $('.cb-element').prop('checked',false);
    $('.reg_stu').prop('checked',true);
    ctrlShowBtn();
}
function reg_student_nonRegestered(){
    $('.cb-element').prop('checked',false);
    $('.non_reg_stu').prop('checked',true);
    ctrlShowBtn();
}

function ctrlShowBtn() {

    if($('.cb-element:checked').length>0)
        $('#regMailBtn').prop('disabled',false);
    else
        $('#regMailBtn').prop('disabled',true);
}


function sendRegMail() {
    var ids = [];
    $('.cb-element').each(function (){
        if($(this).is(":checked"))
            ids.push($(this).val());
    });
    if(ids.length==0)
    {
        ids = 0;
    }
    var regMailSubj = $('#regMailSubj').val();
    var regMailMsg = $('#regMailMsg').val();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'post',
        url: "/Panel/registrations",
        data:{'ids':ids,'regMailSubj':regMailSubj,'regMailMsg':regMailMsg},
        success: function (data) {
            if(data == "success")
            {
                document.getElementById('regMailSuccess').style.display = 'block';
                document.getElementById('regMailErrors').style.display = 'none';
            }
            else
            {
                document.getElementById('regMailSuccess').style.display = 'none';
                document.getElementById('regMailErrors').style.display = 'block';
                $('#regMailErrors').html('');
                data.forEach(myFunction)
            }
        },
        error: function (data) {
            console.log('Fail');
        }
    });
}
function myFunction (data)
{
    $('#regMailErrors').append(data+". ");
}
var stuID_RegMng = 0;
function showStuReg (id) {
    stuID_RegMng = id;
    document.getElementById('stuMngRegBtn').href=("/Panel/newRegistration/"+id);
    $('#stuRegModal').modal('show');
    $.ajax({
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        },
        type : 'get',
        url: "/Panel/registrations/student/"+id,
        data:{'id':id},
        success:function(data){
            // console.log(data);
            $('#stuRegModalBody').html('').append(data);
        },
        error: function (data){
            console.log(data);
        }
    });

}
function changeSemester() {
    var semesterID= $('.mngRegSemester').find(":selected").val();
    console.log(semesterID);
    $.ajax({
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        },
        type : 'get',
        url: "/Panel/registrations/student/"+semesterID,
        data:{'semester':semesterID,'id':stuID_RegMng},
        success:function(data){
            // console.log(data);
            $('#stuRegCourseTbl').html('').append(data);
        },
        error: function (data){
            console.log(data);
        }
    });
}