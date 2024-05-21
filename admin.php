<?php
$host = 'localhost';
$db = 'user_registration';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tfcName = $_POST['name-contest'];
    $links = [];

    for ($i = 1; $i <= 6; $i++) {
        $links[] = isset($_POST["contest-link-$i"]) ? $_POST["contest-link-$i"] : null;
    }

    try {
        $sql = "INSERT INTO tfc_contests (tfc_name, contest_link_1, contest_link_2, contest_link_3, contest_link_4, contest_link_5, contest_link_6) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$tfcName, ...$links]);

        header('Location: Dashboard.html');
        exit();
    } catch (\PDOException $e) {
        die('Insertion failed: ' . $e->getMessage());
    }
}
?>
