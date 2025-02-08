// <?php
// create_admin.php
//$servername = "localhost";
//$username = "root"; 
//$password = ""; 
//$dbname = "hotels";

// Create connection
//$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
//if ($conn->connect_error) {
//    die("Connection failed: " . $conn->connect_error);
//}

// Prepare and bind
/*$stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $admin_username, $admin_password);

// Set the username and password
$admin_username = "admin1";
$admin_password = password_hash("admin123", PASSWORD_DEFAULT); // The hashed password

// Execute the query
if ($stmt->execute()) {
    echo "Admin user created successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
*/