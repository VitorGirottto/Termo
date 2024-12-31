<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require ('../database/db.php');

if (isset($_GET['ids']) && !empty($_GET['ids'])) {
    $ids = explode(',', $_GET['ids']);

    foreach ($ids as $id) {
        if (!is_numeric($id)) {
            die("ID inválido recebido.");
        }
    }

    $sql = "DELETE FROM termo WHERE id IN (" . implode(',', $ids) . ")";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Erro na preparação da query: " . $conn->error);
    }

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $message = "Termos excluídos com sucesso.";
    } else {
        $message = "Termos excluídos com sucesso.";
    }

    $stmt->close();
    $conn->close();

    header("Location: termos.php?message=" . urlencode($message));
    exit();
} else {
    $message = "Nenhum ID de termo recebido para exclusão.";
    header("Location: termos.php?message=" . urlencode($message));
    exit();
}
?>
