<?php
$servername = 'localhost';
$dbname = 'termo';
$db_username = 'root';
$db_password = '';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexão PDO: " . $e->getMessage();
    exit();
}

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão MySQLi: " . $conn->connect_error);
}


?>
