
function delete_admin(id){
    $.ajax(
        {
            headers:{
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            },
            url: "/Panel/Admin/"+id,
            type: 'POST',
            dataType: "JSON",
            data: {
                "id": id,
                "_method": 'delete',
            },
            success: function ()
            {
                $('#admin_view').load(" #admin_view");
            },
            error: function ()
            {
                console.log('fail');
            }
        });
};

function delete_doc(id){
    $.ajax(
        {
            headers:{
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            },
            url: "/Panel/Doctor/"+id,
            type: 'delete',
            dataType: "JSON",
            data: {
                "id": id,
                "_method": 'post',
            },
            success: function ()
            {
                $('#doc_view').load(" #doc_view");
            },
            error: function ()
            {
                console.log('fail');
            }
        });
};

function delete_ta(id){
    $.ajax(
        {
            headers:{
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            },
            url: "/Panel/TA/"+id,
            type: 'delete',
            dataType: "JSON",
            data: {
                "id": id,
                "_method": 'delete',
            },
            success: function ()
            {
                $('#ta_view').load(" #ta_view");
            },
            error: function ()
            {
                console.log('fail');
            }
        });
};

function delete_sa(id){
    $.ajax(
        {
            headers:{
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            },
            url: "/Panel/SA/"+id,
            type: 'delete',
            dataType: "JSON",
            data: {
                "id": id,
                "_method": 'delete',
            },
            success: function ()
            {
                $('#sa_view').load(" #sa_view");
            },
            error: function ()
            {
                console.log('fail');
            }
        });
};

function delete_student(id){
    $.ajax(
        {
            headers:{
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            },
            url: "/Panel/Student/"+id,
            type: 'post',
            dataType: "JSON",
            data: {
                "id": id
            },
            success: function ()
            {
                console.log('deleted');
                $('#student_view').load(" #student_view");
            },
            error: function ()
            {
                console.log('fail');
            }

        });
};

function doc_delete_exp(id){
    $.ajax(
        {
            headers:{
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            },
            url: "/Panel/experience/"+id,
            type: 'post',
            dataType: "JSON",
            data: {
                "id": id
            },
            success: function ()
            {
                console.log('yes');
                $('#doc_exp_view').load(" #doc_exp_view");
            },
            error: function (data)
            {
                console.log(data);
            }
        });
}
function ta_delete_exp(id){
    $.ajax(
        {
            headers:{
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            },
            url: "/Panel/experience/"+id,
            type: 'delete',
            dataType: "JSON",
            data: {
                "id": id,
                "_method": 'delete',
            },
            success: function ()
            {
                $('#ta_exp_view').load(" #ta_exp_view");
            },
            error: function ()
            {
                console.log('fail');
            }
        });
}