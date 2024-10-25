<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Termo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('http://localhost:82/Desenvolver/termo/imagem/tela11.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
        }
        .login-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        h2 {
            color: #1E90FF;
            margin-bottom: 20px;
        }
        input[type="text"], input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #1E90FF; 
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #1C86EE;
        }
        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Termo</h2>
        <form action="" method="POST">
            <img src="http://localhost:82/Desenvolver/termo/imagem/logo1.png" alt="Termo Logo" style="width: 100px; height: 100px; margin-bottom: 10px;">
            <br>
            <input type="text" name="username" placeholder="Login" required>
            <br>
            <input type="password" name="password" placeholder="Senha" required>
            <br>
            <input type="submit" value="Entrar">
        </form>
        <?php
        session_start(); 
        $servername = "localhost";
        $db_username = "root"; 
        $db_password = "";
        $dbname = "termo";

        $conn = new mysqli($servername, $db_username, $db_password, $dbname);

        if ($conn->connect_error) {
            die("Falha na conexão: " . $conn->connect_error);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST["username"];
            $password = $_POST["password"];

            $username = mysqli_real_escape_string($conn, $username);
            $password = mysqli_real_escape_string($conn, $password);

            $sql = "SELECT id, nome, login, senha FROM login WHERE login = '$username' AND senha = '$password'";
            $result = $conn->query($sql);

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $_SESSION['id'] = $row['id'];
                $_SESSION['nome'] = $row['nome'];
                header("Location: inicial.php");
                exit();
            } else {
                echo "<div class='error-message'>Usuário ou senha incorretos. Tente novamente.</div>";
            }
        }
        ?>
    </div>
</body>
</html>
