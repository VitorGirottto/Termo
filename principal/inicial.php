<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Termo</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: url('../imagem/fundo2.jpg');
            background-size: cover;
            background-repeat: no-repeat;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            text-align: center;
        }
        h1 {
            color: #007bff;
            font-size: 2em;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 150px;
            height: auto;
            margin-bottom: 20px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .menu {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .menu a, .btn-create-user {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
            margin-bottom: 10px;
            width: calc(100% - 40px);
            text-align: left;
        }
        .menu a:hover, .btn-create-user:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        .btn-create-user {
            background-color: #007bff;
        }
        .btn-create-user:hover {
            background-color: #0056b3;
        }
        .chat-container {
            position: fixed;
            top: 20px;
            right: 1040px;
            width: 300px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 50px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            max-height: 80vh;
            padding-bottom: 550px;
        }
        .chat-message {
            background-color: #f0f0f0;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .user-message {
            background-color: #007bff;
            color: #fff;
            align-self: flex-end;
        }
        .bot-message {
            background-color: #28a745;
            color: #fff;
            align-self: flex-start;
        }
        .input-container {
            position: fixed;
            bottom: 20px;
            left: 20px;
            display: flex;
            align-items: center;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: calc(100% - 40px);
            max-width: 300px;
            padding: 10px;
        }
        .input-container input[type="text"] {
            flex: 1;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-right: 10px;
        }
        .input-container button {
            padding: 8px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }
        .input-container button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="../imagem/logo1.png" alt="Termo Logo" class="logo">
        <h1>Sistema Termo</h1>
        <div class="menu">
            <a href="../principal/login.php">Login</a>
            <a href="../termo/termos.php">Termos</a>
            <a href="../usuario/usuario.php" class="btn-create-user">Usuários</a>
            <a href="../categoria/categoria.php">Categoria</a>
        </div>
    </div>
</body>
</html>
