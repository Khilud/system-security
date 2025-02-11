
function doSomethingWithUserId(admin_id) 
{
    $('#insertLogButton').click(() => {
        let room_id = $('#roomId').val();
        let start_date = $('#startDate').val();
        let end_date = $('#endDate').val();
        if (room_id.trim().length > 0 && start_date.trim().length && end_date.trim().length) 
        {
            $.ajax({
                type: 'GET',
                url: "http://localhost/Hotel_app/DBUtils.php", 
                data: {
                    action: 'insertAdminReservation',
                    room_id: room_id,
                    admin_id: admin_id,
                    start_date: start_date,
                    end_date: end_date,  
                },
                success: (data) => {
                    let res = JSON.parse(data);
                    if (res === 0) {
                        alert("Reservation could not be added!");
                    } else {
                        $('.form-control').val("");
                        alert("Reservation added sucessfully!");
                    }
                }
            })
        } else {
            alert("Please enter valid data in all fields!");
        }
    })
}


$(document).ready(() => 
{
   $.ajax({
        type: 'GET',
        url: 'http://localhost/Hotel_app/DBUtils.php',
        data: { action: 'selectAdminByUsername', username: 'admin1' },
        success: function(result) 
        {
            let response = JSON.parse(result);
             //If the admin table has e.g. an `id` column:
            let admin_id = response[0].id;
        console.log("Admin ID:", admin_id);
            doSomethingWithUserId(admin_id);
        }
     });

})

 