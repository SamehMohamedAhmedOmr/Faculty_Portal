function showEvent (eventID) {

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'get',
        url: "/events/"+eventID,
        data:{'eventID':eventID},
        success: function (data) {
            $('#eventModalContent').html('').append(data);
            console.log(data);
        },
        error: function (data) {
            console.log('Fail');
        }
    });
}

function deleteEvent(id) {
    $.ajax(
        {
            headers:{
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            },
            url: "/events/"+id,
            type: 'delete',
            dataType: "JSON",
            data: {
                "id": id,
                "_method": 'delete',
            },
            success: function ()
            {
                $('.event-view').load(" .event-view");
            },
            error: function (data)
            {
                console.log('fail');
            }
       });
}