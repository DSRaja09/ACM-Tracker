<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Parameters for database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_registration";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if POST variables are set
if (isset($_POST['name']) && isset($_POST['cf_handles'])) {
    // Retrieve user input from the form
    $name = $_POST['name'];
    $cf_handles = $_POST['cf_handles'];

    // Prepare SQL query to insert participant data into the 'participants' table
    $sql = "INSERT INTO participants (name, cf_handles) VALUES (?, ?)";
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("ss", $name, $cf_handles);

        // Execute the statement
        if ($stmt->execute()) {
            // Data insertion successful
            header("Location: Dashboard.php"); // Redirect to the Dashboard or another page
            exit();
        } else {
            echo "Error executing statement: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
    // Close the connection
    $conn->close();
} else {
    echo "All fields are required.";
}
?>
