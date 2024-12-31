<!DOCTYPE html>
<html lang="pt-br">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Usuário - Sistema Termo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('../imagem/fundo2.jpg');            
            background-color: #f0f0f0;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
        }
        h1 {
            color: #0056b3; 
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #0056b3; 
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #004494; /
        }
        .back-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #0056b3; 
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .back-button:hover {
            background-color: #004494;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Criar um novo Usuário</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="text" name="nome_completo" placeholder="Nome completo" required>
            <br>
            <input type="email" name="email" placeholder="Email" required>
            <br>
            <input type="password" name="senha" placeholder="Senha" required>
            <br>
            <input type="submit" value="Criar Conta">
        </form>
        <a href="usuario.php" class="back-button">&#8592;</a>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nomeCompleto = $_POST["nome_completo"];
            $email = $_POST["email"];
            $senha = $_POST["senha"];

            if (empty($nomeCompleto) || empty($email) || empty($senha)) {
                echo "<p style='color: red;'>Por favor, preencha todos os campos.</p>";
            } else {
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "termo";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
                }

                $sql_check = "SELECT id FROM login WHERE login = ?";
                $stmt_check = $conn->prepare($sql_check);
                $stmt_check->bind_param("s", $email);
                $stmt_check->execute();
                $stmt_check->store_result();

                if ($stmt_check->num_rows > 0) {
                    echo "<p style='color: red;'>Erro ao criar usuário: Este e-mail já está em uso.</p>";
                } else {
                    $sql_insert = "INSERT INTO login (nome, login, senha) VALUES (?, ?, ?)";
                    $stmt_insert = $conn->prepare($sql_insert);
                    $stmt_insert->bind_param("sss", $nomeCompleto, $email, $senha);

                    if ($stmt_insert->execute()) {
                        echo "<p style='color: green;'>Usuário criado com sucesso.</p>";
                    } else {
                        echo "<p style='color: red;'>Erro ao criar usuário: " . $stmt_insert->error . "</p>";
                    }

                    $stmt_insert->close();
                }

                $stmt_check->close();
                $conn->close();
            }
        }
        ?>
    </div>
</body>
</html>
