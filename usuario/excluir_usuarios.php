<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "termo";

if (isset($_GET['ids']) && !empty($_GET['ids'])) {
    $ids = explode(',', $_GET['ids']);

    foreach ($ids as $id) {
        if (!is_numeric($id)) {
            die("ID inválido recebido.");
        }
    }

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    $sql = "DELETE FROM login WHERE id IN (" . implode(',', $ids) . ")";

    if ($conn->query($sql) === TRUE) {
        $message = "Usuários excluídos com sucesso.";
    } else {
        $message = "Erro ao excluir usuários: " . $conn->error;
    }

    $conn->close();
    
    header("Location: usuario.php?message=" . urlencode($message));
    exit();
} else {
    $message = "Nenhum ID de usuário recebido para exclusão.";
    header("Location: usuario.php?message=" . urlencode($message));
    exit();
}
?>
