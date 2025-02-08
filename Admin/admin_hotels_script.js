let currentPage = 0;
let showType = 'all';

const insertData = (newBody, data) => {
    if (currentPage === 0) {
        $('#previousButton').attr('disabled', true);
    }
    let result = JSON.parse(data);
    let numberOfPages = Math.ceil(result.length / 4);
    for (let log of result) {
        let newRow = newBody.insertRow();
        if (result.indexOf(log) >= 4 * currentPage) {
            for (let index of ['id', 'name', 'address']) {
                let newCol = newRow.insertCell();
                let newText = document.createTextNode(log[index]);
                newCol.appendChild(newText);
            }
            newBody.append(newRow);
        }
        if (result.indexOf(log) >= 4 * currentPage + 3) {
            break;
        }
    }
    if (numberOfPages === 0) {
        $('#nextButton').attr('disabled', true);
    } else {
        if (currentPage === numberOfPages - 1) {
            $('#nextButton').attr('disabled', true);
        } else {
            $('#nextButton').attr('disabled', false);
        }
    }
};

const showAllHotels = () => {
    let body = $('.logTable tbody').eq(0);
    let newBody = document.createElement('tbody');
    $.ajax({
        type: 'GET',
        url: "http://localhost/Hotel_app/DBUtils.php",
        data: { action: 'getAllHotels' },
        success: (data) => {
            insertData(newBody, data);
        },
        error: (xhr, status, error) => {
            console.error("Error fetching hotels:", error);
            alert("Failed to fetch hotels. Please try again.");
        }
    });
    body.replaceWith(newBody);
};

const showHotelByName = (name) => {
    let body = $('.logTable tbody').eq(0);
    let newBody = document.createElement('tbody');
    $.ajax({
        type: 'GET',
        url: "http://localhost/Hotel_app/DBUtils.php",
        data: { action: 'getHotelByName', hotel: name },
        success: (data) => {
            $('.form-control').val("");
            insertData(newBody, data);
        },
        error: (xhr, status, error) => {
            console.error("Error fetching hotels by name:", error);
            alert("Failed to fetch hotels by name. Please try again.");
        }
    });
    body.replaceWith(newBody);
};

const showHotelByAddr = (addr) => {
    let body = $('.logTable tbody').eq(0);
    let newBody = document.createElement('tbody');
    $.ajax({
        type: 'GET',
        url: "http://localhost/Hotel_app/DBUtils.php",
        data: { action: 'getHotelByAddr', address: addr },
        success: (data) => {
            $('.form-control').val("");
            insertData(newBody, data);
        },
        error: (xhr, status, error) => {
            console.error("Error fetching hotels by address:", error);
            alert("Failed to fetch hotels by address. Please try again.");
        }
    });
    body.replaceWith(newBody);
};

const showCorrectHotels = () => {
    switch (showType) {
        case 'all':
            showAllHotels();
            break;

        case 'name':
            let names = $('#nameInputFilter').val().trim();
            if (names.length > 0)
                showHotelByName(names);
            else {
                showType = 'all';
            }
            break;

        case 'addr':
            let addr = $('#addressInputFilter').val().trim();
            if (addr.length > 0)
                showHotelByAddr(addr);
            else {
                showType = 'all';
            }
            break;
    }
};

$(document).ready(() => {
    showAllHotels();

    $('#allLogsButton').click(() => {
        currentPage = 0;
        showType = 'all';
        showCorrectHotels();
    });

    $('#filterByNameButton').click(() => {
        currentPage = 0;
        showType = 'name';
        showCorrectHotels();
    });

    $('#filterByAddressButton').click(() => {
        currentPage = 0;
        showType = 'addr';
        showCorrectHotels();
    });

    $('#previousButton').click(() => {
        if (currentPage > 0) {
            currentPage--;
            if (currentPage === 0) {
                $('#previousButton').attr('disabled', true);
            }
        }
        showCorrectHotels();
    });

    $('#nextButton').click(() => {
        $('#previousButton').attr('disabled', false);
        currentPage++;
        showCorrectHotels();
    });
});