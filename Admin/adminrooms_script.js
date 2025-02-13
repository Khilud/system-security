let currentPage = 0;
let showType = 'all';

const insertData = (newBody, data) => {
    // Disable previous button on the first page
    if (currentPage === 0) {
        $('#previousButton').attr('disabled', true);
    }

    let result = JSON.parse(data); // Parse data
    let numberOfPages = Math.ceil(result.length / 4); // Calculate total pages

    // Add rows to the table
    for (let i = currentPage * 4; i < Math.min(result.length, (currentPage + 1) * 4); i++) {
        let log = result[i];
        let newRow = newBody.insertRow();
        for (let key of ['id', 'hotel_id', 'room_number', 'category', 'price']) {
            let newCol = newRow.insertCell();
            newCol.textContent = log[key];
        }
    }

    // Disable/enable next button based on the current page
    if (currentPage >= numberOfPages - 1) {
        $('#nextButton').attr('disabled', true);
    } else {
        $('#nextButton').attr('disabled', false);
    }
};


const showAllRooms = () => 
{
    let body = $('.table tbody').eq(0);
    let newBody = document.createElement('tbody');
    $.ajax({
        type: 'GET',
        url: "http://localhost/Hotel_app/DBUtils.php",
        data: {action: 'getAllRooms'},
        success: (data) => {
            insertData(newBody, data);
        }
    })
    body.replaceWith(newBody);
}


const showRoomByCategory = (category) => 
{
    
    console.log("Category:", category);
    let body = $('.table tbody').eq(0);
    let newBody = document.createElement('tbody');
    $.ajax({
        type: 'GET',
        url: "http://localhost/Hotel_app/DBUtils.php",
        data: {action: 'getRoomByCategory', category: category},
        success: (data) => {
            console.log("category filter Data:", data);
            currentPage = 0; // Reset page
            insertData(newBody, data);
        },
        error: (xhr, status, error) => {
            console.error("AJAX Error: ", status, error);
            alert("Failed to fetch hotels by name. Please try again.");
        }
    });
    body.replaceWith(newBody);
};

const showRoomByPrice = (price) => {
    console.log("Price:", price);
    let body = $('.table tbody').eq(0);
    let newBody = document.createElement('tbody');
    $.ajax({
        type: 'GET',
        url: "http://localhost/Hotel_app/DBUtils.php",
        data: { action: 'getRoomByPrice', price },
        success: (data) => {
            console.log("Price Filter Data:", data);
            currentPage = 0; // Reset page
            insertData(newBody, data);
        },
        error: (xhr, status, error) => {
            console.error("AJAX Error: ", status, error);
        }
    });
    body.replaceWith(newBody);
};

$(document).ready(() => {
    // Show all rooms on page load
    showAllRooms();

// Filter by category
$('#filterByCategoryButton').click(() => {
    const category = $('#categoryInputFilter').val().trim();
    console.log("Filter by Category clicked:", category); 
    if (category.length === 0) {
        alert('Please enter a category!');
        return;
    }
    showRoomByCategory(category);
});

// Filter by price
$('#filterByPriceButton').click(() => {
    const price = $('#priceInputFilter').val().trim();
    if (isNaN(price) || price <= 0) {
        alert('Please enter a valid price!');
        return;
    }
    showRoomByPrice(price);
});

// Pagination: Previous
$('#previousButton').click(() => {
    if (currentPage > 0) {
        currentPage--;
        showAllRooms(); // Re-fetch the correct data
    }
});

// Pagination: Next
$('#nextButton').click(() => {
    currentPage++;
    showAllRooms(); // Re-fetch the correct data
});
});
