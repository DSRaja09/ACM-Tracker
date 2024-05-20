<?php
// Database configuration
$servername = "localhost";
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "user_registration";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate form data
    if (empty($email) || empty($password)) {
        die("Please fill all fields.");
    }

    // Prepare and bind
    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);

    // Execute the statement
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        // Bind result variables
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            echo "Sign-in successful!";
        } else {
            echo "Invalid email or password.";
        }
    } else {
        echo "Invalid email or password.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
