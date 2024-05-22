<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Parameters for database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_registration"; // Ensure your database name is correct

// Create connection with the parameters
$conn = new mysqli($servername, $username, $password, $dbname);

// Checking the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if POST variables are set
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
    // Retrieve user input from the form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password

    // Prepare SQL query to insert user data into the 'users' table
    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("sss", $name, $email, $password);

        // Execute the statement
        if ($stmt->execute()) {
            // Registration successful, redirect to Admin.html
            header("Location: Admin.html");
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
