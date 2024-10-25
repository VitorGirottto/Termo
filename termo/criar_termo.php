<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Termo - Sistema Termo</title>
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
        input[type="text"], textarea, select {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            height: 120px;
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
            background-color: #004494; 
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
        <h1>Criar um novo Termo</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="text" name="nome" placeholder="Nome do Termo" required>
            <br>
            <select name="categoria">
                <option value="">Selecione uma categoria (opcional)</option>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "termo";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
                }

                $sql = "SELECT id, nome FROM categoria";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
                    }
                }

                $conn->close();
                ?>
            </select>
            <br>
            <textarea name="descricao" placeholder="Descrição do Termo" required></textarea>
            <br>
            <input type="submit" value="Criar Termo">
        </form>
        <a href="termos.php" class="back-button">&#8592;</a>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nome = $_POST["nome"];
            $categoria = isset($_POST["categoria"]) ? $_POST["categoria"] : null;
            $descricao = $_POST["descricao"];

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Erro na conexão com o banco de dados: " . $conn->connect_error);
            }

            $sql_insert = "INSERT INTO termo (nome, categoria, descricao) VALUES (?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("sis", $nome, $categoria, $descricao);

            if ($stmt_insert->execute()) {
                echo "<p style='color: green;'>Termo criado com sucesso.</p>";
            } else {
                echo "<p style='color: red;'>Erro ao criar termo: " . $stmt_insert->error . "</p>";
            }

            $stmt_insert->close();
            $conn->close();
        }
        ?>
    </div>
</body>
</html>
