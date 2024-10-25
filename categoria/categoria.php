<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$dbname = 'termo';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
    exit();
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['nome'])) {
        $nome = $_POST['nome'];

        $stmt_check = $pdo->prepare("SELECT COUNT(*) AS total FROM categoria WHERE nome = :nome");
        $stmt_check->bindParam(':nome', $nome);
        $stmt_check->execute();
        $row = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if ($row['total'] > 0) {
            $message = 'Já existe uma categoria com esse nome.';
        } else {
            $stmt = $pdo->prepare("INSERT INTO categoria (nome) VALUES (:nome)");
            $stmt->bindParam(':nome', $nome);

            if ($stmt->execute()) {
                $message = 'Categoria adicionada com sucesso.';
            } else {
                $message = 'Erro ao adicionar categoria.';
            }
        }
    } else {
        $message = 'Por favor, insira o nome da categoria.';
    }
}

$categorias = [];
try {
    $stmt = $pdo->query("SELECT * FROM categoria");
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro na consulta: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias - Sistema de Gerenciamento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-image: url('../imagem/fundo2.jpg');
            background-color: #f0f0f0;
            padding: 20px;
        }
        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .message {
            background-color: #004080;
            color: white;
            padding: 10px;
            border-radius: 5px;
            display: <?php echo empty($message) ? 'none' : 'block'; ?>;
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
        h1 {
            color: #004080;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
            padding: 10px;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn {
            background-color: #004080;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-right: 10px;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background-color: #00264d;
        }
        .back-button {
            position: absolute;
            top: 40px;
            right: 20px;
            background-color: #004080;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            z-index: 1000;
        }
        .back-button:hover {
            background-color: #00264d;
        }
        .form-container {
            margin-top: 20px;
            padding: 20px;
            background-color: #f2f2f2;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .form-container label {
            display: block;
            margin-bottom: 10px;
        }
        .form-container input[type=text] {
            width: 100%;
            padding: 8px;
            margin-top: 6px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        .form-container .btn {
            width: auto;
        }
        .form-container {
            margin-top: 20px;
            margin-bottom: 20px;
            padding: 20px;
            background-color: #f2f2f2;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            position: relative;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Adicionar Nova Categoria</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>
            <button type="submit" class="btn">Adicionar</button>
        </form>
    </div>
    <a href="../principal/inicial.php" class="back-button">&#8592;</a>
    <div class="container">
        <h1>Categorias</h1>
        <div class="message"><?php echo $message; ?></div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td><?php echo $categoria['id']; ?></td>
                        <td><?php echo $categoria['nome']; ?></td>
                        <td>
                            <a href="excluir_categoria.php?id=<?php echo $categoria['id']; ?>" class="btn" onclick="return confirm('Tem certeza que deseja excluir esta categoria?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
