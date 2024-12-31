<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Termo - Sistema Termo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
            background-image: url('../imagem/fundo2.jpg');
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h1 {
            color: #0056b3;
        }
        .form-group {
            margin-bottom: 10px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="text"], textarea, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        .btn {
            background-color: #0056b3;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn:hover {
            background-color: #004494;
        }
        .back-button {
            position: absolute;
            top: 40px;
            right: 363px;
            background-color: #0056b3;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
        }
        .back-button:hover {
            background-color: #004494;
        }
        .message-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            max-width: 300px;
            z-index: 1000;
            pointer-events: none;
        }
        .message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 4px;
            color: #fff;
            font-size: 14px;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }
        .success {
            background-color: #4CAF50;
        }
        .error {
            background-color: #f44336;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="termos.php" class="back-button">&#8592;</a>
        <h1>Editar Termo</h1>

        <?php
        function redirectWithMessage($message, $type = 'success') {
            $encodedMessage = urlencode($message);
            $successMessage = ($type === 'success') ? "&success={$encodedMessage}" : '';
            header("Location: editar_termo.php?id={$_GET['id']}{$successMessage}");
            exit();
        }

        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $_GET['id'];

            require ('../database/db.php');

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $nome = $_POST['nome'];
                $categoria = ($_POST['categoria'] === '') ? null : $_POST['categoria'];
                $descricao = $_POST['descricao'];

                $nome = $conn->real_escape_string($nome);
                $descricao = $conn->real_escape_string($descricao);

                if ($_POST['categoria'] === '' && !empty($row['categoria'])) {
                    $categoria = null;

                    $stmtDeleteCategoria = $conn->prepare("UPDATE termo SET categoria=NULL WHERE id=?");
                    $stmtDeleteCategoria->bind_param("i", $id);
                    $stmtDeleteCategoria->execute();
                    $stmtDeleteCategoria->close();
                }

                $stmt = $conn->prepare("UPDATE termo SET nome=?, categoria=?, descricao=? WHERE id=?");

                $stmt->bind_param("sssi", $nome, $categoria, $descricao, $id);

                if ($stmt->execute()) {
                    redirectWithMessage("Termo atualizado com sucesso!", 'success');
                } else {
                    redirectWithMessage("Erro ao atualizar termo: " . $stmt->error, 'error');
                }

                $stmt->close();
            }

            $sql = "SELECT nome, categoria, descricao FROM termo WHERE id = $id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $nome = $row['nome'];
                $categoria = $row['categoria'];
                $descricao = $row['descricao'];

                echo '<form action="editar_termo.php?id=' . $id . '" method="POST">';
                echo '<div class="form-group">';
                echo '<label for="nome">Nome:</label>';
                echo '<input type="text" id="nome" name="nome" value="' . htmlspecialchars($nome) . '" required>';
                echo '</div>';
                echo '<div class="form-group">';
                echo '<label for="categoria">Categoria:</label>';
                echo '<select id="categoria" name="categoria">';
                echo '<option value="">Selecione uma categoria (opcional)</option>';
                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
                }

                $sql_categorias = "SELECT id, nome FROM categoria";
                $result_categorias = $conn->query($sql_categorias);

                if ($result_categorias->num_rows > 0) {
                    while ($row_categoria = $result_categorias->fetch_assoc()) {
                        $selected = ($categoria == $row_categoria['id']) ? 'selected' : '';
                        echo "<option value='" . $row_categoria['id'] . "' $selected>" . $row_categoria['nome'] . "</option>";
                    }
                }

                echo '</select>';
                echo '</div>';
                echo '<div class="form-group">';
                echo '<label for="descricao">Descrição:</label>';
                echo '<textarea id="descricao" name="descricao" rows="4" required>' . htmlspecialchars($descricao) . '</textarea>';
                echo '</div>';
                echo '<button type="submit" class="btn">Salvar Alterações</button>';
                echo '</form>';
            } else {
                redirectWithMessage("Termo não encontrado.", 'error');
            }

            $conn->close();
        } else {
            redirectWithMessage("ID do termo não fornecido.", 'error');
        }
        ?>

    </div>

    <div class="message-container">
        <?php
        if (isset($_GET['success'])) {
            echo '<div class="message success">' . htmlspecialchars(urldecode($_GET['success'])) . '</div>';
        } elseif (isset($_GET['error'])) {
            echo '<div class="message error">' . htmlspecialchars(urldecode($_GET['error'])) . '</div>';
        }
        ?>
    </div>

</body>
</html>
