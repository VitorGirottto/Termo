<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "termo";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    $nome = $_POST['nome'];

    if (strlen($nome) > 255) {
        $message = "O nome da categoria é muito longo.";
    } else {
        $sql = "INSERT INTO categoria (nome) VALUES (?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            $message = "Erro ao preparar a declaração SQL: " . $conn->error;
        } else {
            $stmt->bind_param("s", $nome);

            if ($stmt->execute()) {
                $message = "Categoria adicionada com sucesso.";
            } else {
                $message = "Erro ao adicionar categoria: " . $stmt->error;
            }

            $stmt->close();
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Categoria</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 400px;
            margin: 0 auto;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input[type=text] {
            width: 100%;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn {
            background-color: #004080;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background-color: #00264d;
        }
        .message {
            margin-top: 10px;
            padding: 10px;
            background-color: #f2dede;
            border: 1px solid #ebccd1;
            color: #a94442;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Adicionar Categoria</h2>
        <?php if (isset($message)) : ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group">
                <label for="nome">Nome da Categoria:</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn">Adicionar Categoria</button>
            </div>
        </form>
    </div>
</body>
</html>
