<?php
session_start();
if (isset($_POST['returnButton'])) {
    header('Location: home.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="style.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" ></script>
    <title>All Hotels</title>
</head>
<body>
<div class="content">
 <br>
    <div class="container mt-4">
        <h3 class="text-center mb-4">All Hotels</h3>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Image</th>
                    </tr>
                </thead>
                <tbody>
    <?php
    foreach ($hotels as $hotel) {
        // Construct image path based on hotel ID
        $imagePath = "imghotel/" . $hotel['id'] . ".jpg";

        // Check if the image file exists, otherwise use a default image
        if (!file_exists($imagePath)) {
            $imagePath = "imghotel/default.jpg"; // Set a default image if no image is found
        }

        echo "<tr>";
        echo "<td>" . $hotel['id'] . "</td>";
        echo "<td>" . $hotel['name'] . "</td>";
        echo "<td>" . $hotel['address'] . "</td>";
        echo "<td><img src='" . $imagePath . "' alt='Hotel Image' class='img-thumbnail' width='100'></td>";
        echo "</tr>";
    }
    ?>
</tbody>

            </table>
        </div>
        <div class="text-center mt-3">
            <button type="button" id="previousButton" class="btn btn-primary me-2">Previous</button>
            <button id="nextButton" type="button" class="btn btn-primary">Next</button>
            <br><br>
            <button id="allLogsButton" type="button" class="btn btn-secondary">Show All</button>
        </div>
        <div class="row mt-4">
            <div class="col-md-6">
                <label for="nameInputFilter" class="form-label">Filter by Name:</label>
                <input type="text" id="nameInputFilter" class="form-control mb-2">
                <button id="filterByNameButton" type="button" class="btn btn-info">Filter</button>
            </div>
            <div class="col-md-6">
                <label for="addressInputFilter" class="form-label">Filter by Address:</label>
                <input type="text" id="addressInputFilter" class="form-control mb-2">
                <button id="filterByAddressButton" type="button" class="btn btn-info">Filter</button>
            </div>
        </div>
        <form method="post" class="text-center mt-4">
            <input id="returnButton" type="submit" class="btn btn-danger" name="returnButton" value="Return to Main Page">
        </form>
    </div>


    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="hotels_script.js"></script>
</body>
</html>
