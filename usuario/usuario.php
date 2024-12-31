<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários - Sistema Termo</title>
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
        }
        .message {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border-radius: 5px;
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
        h1 {
            color: #007bff;
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
            background-color: #007bff;
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
            background-color: #0056b3;
        }
        .back-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #007bff;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <a href="../principal/inicial.php" class="back-button">&#8592;</a>
    <div class="container">
        <h1>Usuários</h1>
        <div id="message" class="message"></div>
        <a href="criar_usuario.php" class="btn">Novo</a>
        <button class="btn" onclick="excluirSelecionados()">Excluir Selecionados</button>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Login</th>
                    <th>Senha</th>
                    <th>Ações</th>
                    <th><input type="checkbox" id="checkTodos"> Selecionar Todos</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require '../database/db.php';

                

                $sql = "SELECT id, nome, login FROM login";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["nome"] . "</td>";
                        echo "<td>" . $row["login"] . "</td>";
                        echo "<td>***</td>"; 
                        echo '<td><a href="editar_usuario.php?id=' . $row["id"] . '" class="btn">Editar</a></td>';
                        echo '<td><input type="checkbox" class="checkbox" value="' . $row["id"] . '"></td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Nenhum usuário encontrado.</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function showMessage(message) {
            var messageDiv = document.getElementById('message');
            messageDiv.innerText = message;
            messageDiv.style.display = 'block';
            setTimeout(function() {
                messageDiv.style.display = 'none';
            }, 10000); 
        }

        function excluirSelecionados() {
            if (confirm('Tem certeza que deseja excluir os usuários selecionados?')) {
                var checkboxes = document.getElementsByClassName('checkbox');
                var ids = [];
                for (var i = 0; i < checkboxes.length; i++) {
                    if (checkboxes[i].checked) {
                        ids.push(checkboxes[i].value);
                    }
                }
                if (ids.length > 0) {
                    window.location.href = 'excluir_usuarios.php?ids=' + ids.join(',');
                } else {
                    alert('Por favor, selecione pelo menos um usuário.');
                }
            }
        }

        document.getElementById('checkTodos').addEventListener('change', function() {
            var checkboxes = document.getElementsByClassName('checkbox');
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = this.checked;
            }
        });

        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('message')) {
            var message = urlParams.get('message');
            showMessage(message);
        }
    </script>
</body>
</html>
