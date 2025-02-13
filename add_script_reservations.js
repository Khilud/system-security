function doSomethingWithUserId(user_id) {
    $('#insertLogButton').click(() => {
        let room_id = $('#roomId').val();
        let start_date = $('#startDate').val();
        let end_date = $('#endDate').val();

        // Validate inputs
        if (room_id.trim() && start_date.trim() && end_date.trim()) {
            $.ajax({
                type: 'GET',
                url: "http://localhost/Hotel_app/DBUtils.php",
                data: {
                    action: 'insertReservetion',
                    room_id: room_id,
                    user_id: user_id,
                    start_date: start_date,
                    end_date: end_date,
                },
                success: (data) => {
                    let res;
                    try {
                        res = JSON.parse(data); // Safely parse JSON
                    } catch (e) {
                        console.error("Error parsing JSON:", e, data);
                        alert("Failed to process server response. Please try again.");
                        return;
                    }

                    // Check response and provide feedback
                    if (res === 1) {
                        $('.form-control').val(""); // Clear form fields
                        alert("Reservation added successfully!");
                    } else if (res === "unavailable") {
                        alert("The room is already reserved for the selected dates. Please choose a different room or date.");
                    } else {
                        alert("Reservation could not be added! Please try again.");
                    }
                },
                error: (xhr, status, error) => {
                    console.error("AJAX request failed:", status, error);
                    alert("Failed to add reservation. Please check your connection or try again.");
                },
            });
        } else {
            alert("Please enter valid data in all fields!");
        }
    });
}

$(document).ready(() => {
    $.ajax({
        type: 'GET',
        url: 'http://localhost/Hotel_app/DBUtils.php',
        data: { action: 'selectIdForReservation', username: username },
        success: function (result) {
            console.log("Response from API:", result); // Log raw response

            let response;
            try {
                response = JSON.parse(result); // Safely parse JSON
            } catch (e) {
                console.error("Error parsing JSON:", e, result);
                alert("Failed to process server response. Please try again.");
                return;
            }

            console.log("Parsed response:", response);

            // Check if the response contains an id
            if (response && response.id) {
                let user_id = parseInt(response.id, 10); // Convert id to integer
                console.log("User ID:", user_id);
                doSomethingWithUserId(user_id); // Pass the user ID to the function
            } else {
                console.error("Response does not contain 'id':", response);
                alert("Error: Failed to fetch user ID. Ensure the username exists.");
            }
        },
        error: (xhr, status, error) => {
            console.error("AJAX request failed:", status, error);
            alert("Failed to fetch user data. Please check your connection or try again.");
        },
    });
});
