$el = document.getElementById("adm_search");
if($el)
    $el.addEventListener("keyup", adm_search);
function adm_search() {
    var $value = document.getElementById("adm_search").value;
    var patt = /^[A-Za-z0-9]+$/;
    if(!$value)
        $value = 'non';
    if(!patt.test($value))
        $value = 'invalid_search_key';
    $.ajax({
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        },
        type : 'get',
        url: "/Panel/Admin/"+$value,
        data:{'search':$value},
        success:function(data){
            $('#adm_tbl').html(data);
        },
        error: function ()
        {
            console.log('fail');
        }
    });
}

$el = document.getElementById("doc_search");
if($el)
    $el.addEventListener("keyup", doc_search);
function doc_search() {
    var $value = document.getElementById("doc_search").value;
    var patt = /^[A-Za-z0-9]+$/;
    if(!$value)
        $value = 'non';
    if(!patt.test($value))
        $value = 'invalid_search_key';
    $.ajax({
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        },
        type : 'get',
        url: "/Panel/Doctor/"+$value,
        data:{'search':$value},
        success:function(data){
            $('#doc_tbl').html(data);
        },
        error: function ()
        {
            console.log('fail');
        }
    });
}

//Prob ; Degree
$el = document.getElementById("sa_search");
if($el)
    $el.addEventListener("keyup", sa_search);
function sa_search() {
    var $value = document.getElementById("sa_search").value;
    var patt = /^[A-Za-z0-9]+$/;
    if(!$value)
        $value = 'non';
    if(!patt.test($value))
        $value = 'invalid_search_key';
    $.ajax({
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        },
        type : 'get',
        url: "/Panel/SA/"+$value,
        data:{'search':$value},
        success:function(data){
            $('#sa_tbl').html(data);
        },
        error: function ()
        {
            console.log('fail');
        }
    });
}

$el = document.getElementById("ta_search");
if($el)
    $el.addEventListener("keyup", ta_search);
function ta_search() {
    var $value = document.getElementById("ta_search").value;
    var patt = /^[A-Za-z0-9]+$/;
    if(!$value)
        $value = 'non';
    if(!patt.test($value))
        $value = 'invalid_search_key';
    $.ajax({
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        },
        type : 'get',
        url: "/Panel/TA/"+$value,
        data:{'search':$value},
        success:function(data){
            $('#ta_tbl').html(data);
        },
        error: function ()
        {
            console.log('fail');
        }
    });
}

$el = document.getElementById("course_search");
if($el)
    $el.addEventListener("keyup", course_search);
function course_search() {
    var $value = document.getElementById("course_search").value;
    var patt = /^[A-Za-z0-9]+$/;
    if(!$value)
        $value = 'non';
    if(!patt.test($value))
        $value = 'invalid_search_key';
    $.ajax({
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        },
        type : 'get',
        url: "/Panel/manageCourses/"+$value,
        data:{'search':$value},
        success:function(data){
            $('#course_tbl').html(data);
        },
        error: function ()
        {
            console.log('fail');
        }
    });
}

$el = document.getElementById("place_search");
if($el)
    $el.addEventListener("keyup", place_search);
function place_search() {
    var $value = document.getElementById("place_search").value;
    var patt = /^[A-Za-z0-9]+$/;
    if(!$value)
        $value = 'non';
    if(!patt.test($value))
        $value = 'invalid_search_key';
    $.ajax({
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        },
        type : 'get',
        url: "/Panel/managePlaces/"+$value,
        data:{'search':$value},
        success:function(data){
            $('#place_tbl').html(data);
        },
        error: function ()
        {
            console.log('fail');
        }
    });
}

$el = document.getElementById("student_search");
if($el)
    $el.addEventListener("keyup", student_search);
function student_search() {
    var $value = document.getElementById("student_search").value;
    var patt = /^[A-Za-z0-9]+$/;
    if(!$value)
        $value = 0;
    if(!patt.test($value))
        $value = 'invalid_search_key';
    $.ajax({
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        },
        type : 'get',
        url: "/Panel/Student/"+$value,
        data:{'search':$value},
        success:function(data){
            $('#student_tbl').html(data);
        },
        error: function ()
        {
            console.log('fail');
        }
    });
}