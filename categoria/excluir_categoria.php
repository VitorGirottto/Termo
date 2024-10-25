<?php
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "termo";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    $sql = "DELETE FROM categoria WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        $message = "Erro ao preparar a declaração SQL: " . $conn->error;
    } else {
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $message = "Categoria excluída com sucesso.";
        } else {
            $message = "Erro ao excluir categoria: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();

    header("Refresh: 1; URL=categoria.php?message=" . urlencode($message));
    exit();
} else {
    $message = "ID da categoria inválido para exclusão.";
    header("Location: categoria.php?message=" . urlencode($message));
    exit();
}
