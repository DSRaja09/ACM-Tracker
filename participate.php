<?php
// Database configuration
$host = 'localhost';
$db = 'user_registration';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';;

// DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Create a PDO instance
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Handle connection error
    die('Connection failed: ' . $e->getMessage());
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $Name = $_POST['Name'];
    $cf_handles = $_POST['CF_handles'];

    // Insert form data into the database
    try {
        $sql = "INSERT INTO participants (name, cf_handles) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $cf_handles]);

        // Redirect to a success page (or any other page)
        //header('Location: success.html');
        exit();
    } catch (\PDOException $e) {
        // Handle insertion error
        die('Insertion failed: ' . $e->getMessage());
    }
}
?>



?>