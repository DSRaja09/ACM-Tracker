<?php
// Database connection settings
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "user_registration";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the TFC name
$tfc_name = $_POST['tfc_name'];

// Loop through each table and insert the data into the database
for ($i = 0; $i < count($_POST['table0_links']); $i++) {
    $links = array();
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'table') !== false && !empty($value[$i])) {
            $links[] = $value[$i];
        }
    }

    // Insert data into the database
    $sql = "INSERT INTO tfc_links (tfc_name, link) VALUES ";
    $sql .= "('" . $conn->real_escape_string($tfc_name) . "', '" . implode("'), ('" . $conn->real_escape_string($tfc_name) . "', '", $links) . "')";
    $conn->query($sql);
}

echo "Data inserted successfully!";

// Close connections
$conn->close();
?>
