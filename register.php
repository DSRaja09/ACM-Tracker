<?php

// Parameters for database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "acm_tracker"; // Ensure your database name is correct

// Create connection with the parameters
$conn = new mysqli($servername, $username, $password, $dbname);

// Checking the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if POST variables are set
if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
    // Retrieve user input from the form
    $Username = $_POST['username'];
    $Email = $_POST['email'];
    $Password = $_POST['password'];

    // Prepare SQL query to insert user data into the 'registration' table
    $sql = "INSERT INTO registration (Username, Email, Password) VALUES (?, ?, ?)";
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("sss", $Username, $Email, $Password);

        // Execute the statement
    if ($stmt->execute()) {
        //echo "Registration successful!";
        header("Location: Admin.html");
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>