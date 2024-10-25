<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário - Sistema Termo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-image: url('../imagem/fundo2.jpg');            
            padding: 20px;
            background-color: #f0f0f0;
        }
        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            max-width: 600px;
            margin: 20px auto;
            position: relative; 
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
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn {
            background-color: #0056b3; 
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #004494; 
        }
        .back-button {
            position: absolute;
            top: 20px;
            right: 20px;
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
        <a href="usuario.php" class="back-button">&#8592;</a>
        <h1>Editar Usuário</h1>

        <?php
        function redirectWithMessage($message, $type = 'success') {
            $encodedMessage = urlencode($message);
            header("Location: editar_usuario.php?id={$_GET['id']}&{$type}={$encodedMessage}");
            exit();
        }

        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $_GET['id'];

            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "termo";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Erro na conexão com o banco de dados: " . $conn->connect_error);
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $nome = $_POST['nome'];
                $login = $_POST['login'];
                $senha = $_POST['senha']; 

                if (strpos($login, '@') === false) {
                    redirectWithMessage("O campo login deve conter o caractere '@'", 'error');
                } else {
                    $nome = $conn->real_escape_string($nome);
                    $login = $conn->real_escape_string($login);

                    if (!empty($senha)) {
                        $stmt = $conn->prepare("UPDATE login SET nome=?, login=?, senha=? WHERE id=?");
                        $stmt->bind_param("sssi", $nome, $login, $senha, $id);
                    } else {
                        $stmt = $conn->prepare("UPDATE login SET nome=?, login=? WHERE id=?");
                        $stmt->bind_param("ssi", $nome, $login, $id);
                    }

                    if ($stmt->execute()) {
                        redirectWithMessage("Usuário atualizado com sucesso!", 'success');
                    } else {
                        redirectWithMessage("Erro ao atualizar usuário: " . $stmt->error, 'error');
                    }

                    $stmt->close();
                }
            }

            $sql = "SELECT nome, login FROM login WHERE id = $id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $nome = $row['nome'];
                $login = $row['login'];

                echo '<form action="editar_usuario.php?id=' . $id . '" method="POST">';
                echo '<div class="form-group">';
                echo '<label for="nome">Nome:</label>';
                echo '<input type="text" id="nome" name="nome" value="' . htmlspecialchars($nome) . '" required>';
                echo '</div>';
                echo '<div class="form-group">';
                echo '<label for="login">Login:</label>';
                echo '<input type="text" id="login" name="login" value="' . htmlspecialchars($login) . '" required>';
                echo '</div>';
                echo '<div class="form-group">';
                echo '<label for="senha">Nova Senha:</label>';
                echo '<input type="password" id="senha" name="senha">';
                echo '</div>';
                echo '<button type="submit" class="btn">Salvar Alterações</button>';
                echo '</form>';
            } else {
                redirectWithMessage("Usuário não encontrado.", 'error');
            }

            $conn->close();
        } else {
            redirectWithMessage("ID do usuário não fornecido.", 'error');
        }
        ?>
    </div>

    <div class="message-container" id="message-container"></div>

    <script>
        function showMessage(type, message) {
            const messageContainer = document.getElementById('message-container');

            const div = document.createElement('div');
            div.className = `message ${type}`;
            div.textContent = decodeURIComponent(message); 

            messageContainer.appendChild(div);

            setTimeout(function() {
                div.style.opacity = '1';
            }, 100);

            setTimeout(function() {
                div.style.opacity = '0';
            }, 10000); 
        }

        <?php
        if (isset($_GET['success'])) {
            $message = htmlspecialchars(urldecode($_GET['success']));
            echo 'showMessage("success", "' . $message . '");';
        }

        if (isset($_GET['error'])) {
            $error = htmlspecialchars(urldecode($_GET['error']));
            echo 'showMessage("error", "' . $error . '");';
        }
        ?>
    </script>
</body>
</html>
