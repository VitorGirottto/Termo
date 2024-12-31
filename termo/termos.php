<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

require ('../database/db.php');

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Falha na conexão: " . $e->getMessage());
}

$sql = "SELECT t.id, t.nome, c.nome AS categoria, t.descricao 
        FROM termo t
        LEFT JOIN categoria c ON t.categoria = c.id"; 
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termos - Sistema Termo</title>
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
            background-color: #004080; 
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
            top: 20px;
            right: 20px;
            background-color: #004080;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
        }
        .back-button:hover {
            background-color: #00264d;
        }
        .search-container {
            margin-bottom: 20px;
            text-align: right;
        }
        .search-container input[type=text] {
            padding: 8px;
            margin-top: 8px;
            margin-bottom: 8px;
            box-sizing: border-box;
            border: 2px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            transition: width 0.4s ease-in-out;
        }
        .search-container input[type=text]:focus {
            width: 100%;
        }
        .search-container select {
            padding: 8px;
            margin-top: 8px;
            margin-bottom: 8px;
            box-sizing: border-box;
            border: 2px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            background-color: #004080;
            color: white;
            transition: background-color 0.3s ease;
        }
        .search-container select option {
            background-color: white;
            color: black;
        }
        .search-container select:focus, .search-container select:hover {
            background-color: #00264d;
        }
        .letter-filters {
            margin-bottom: 10px;
            text-align: center;
        }
        .letter-filters a {
            display: inline-block;
            padding: 8px 12px;
            margin-right: 5px;
            background-color: #004080; 
            color: white;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
        }
        .letter-filters a.active, .letter-filters a:hover {
            background-color: #00264d;
        }
    </style>
</head>
<body>
    <a href="../principal/inicial.php" class="back-button">&#8592;</a>
    <div class="container">
        <h1>Termos</h1>
        <div id="message" class="message"></div>
        <a href="criar_termo.php" class="btn">Novo</a>
        <button class="btn" onclick="excluirSelecionados()">Excluir Selecionados</button>
        <div class="search-container">
            <select id="searchBy">
                <option value="nome">Nome</option>
                <option value="categoria">Categoria</option>
            </select>
            <input type="text" id="searchInput" onkeyup="filtrarTermos()" placeholder="Pesquisar...">
        </div>
        <div class="letter-filters">
            <a href="javascript:void(0);" class="letter-filter" onclick="filtrarPorLetra('Todos')">Todos</a>
            <?php
            for ($i = 65; $i <= 90; $i++) {
                $letra = chr($i);
                echo '<a href="javascript:void(0);" class="letter-filter" onclick="filtrarPorLetra(\'' . $letra . '\')">' . $letra . '</a>';
            }
            ?>
        </div>
        <table id="termosTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                    <th><input type="checkbox" id="checkTodos"> Selecionar Todos</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->rowCount() > 0) {
                    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["nome"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["categoria"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["descricao"]) . "</td>";
                        echo '<td><a href="editar_termo.php?id=' . $row["id"] . '" class="btn">Editar</a></td>';
                        echo '<td><input type="checkbox" class="checkbox" value="' . htmlspecialchars($row["id"]) . '"></td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Nenhum termo encontrado.</td></tr>";
                }
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
            if (confirm('Tem certeza que deseja excluir os termos selecionados?')) {
                var checkboxes = document.getElementsByClassName('checkbox');
                var ids = [];
                for (var i = 0; i < checkboxes.length; i++) {
                    if (checkboxes[i].checked) {
                        ids.push(checkboxes[i].value);
                    }
                }
                if (ids.length > 0) {
                    window.location.href = 'excluir_termos.php?ids=' + ids.join(',');
                } else {
                    alert('Por favor, selecione pelo menos um termo.');
                }
            }
        }

        document.getElementById('checkTodos').addEventListener('change', function() {
            var checkboxes = document.getElementsByClassName('checkbox');
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = this.checked;
            }
        });

        function filtrarTermos() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("termosTable");
            tr = table.getElementsByTagName("tr");

            var searchBy = document.getElementById("searchBy").value; 

            var encontrouResultado = false;

            for (i = 1; i < tr.length; i++) {
                if (searchBy === "nome") {
                    td = tr[i].getElementsByTagName("td")[1]; 
                } else {
                    td = tr[i].getElementsByTagName("td")[2]; 
                }
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";  
                        encontrouResultado = true; 
                    } else {
                        tr[i].style.display = "none"; 
                    }
                }
            }

            if (!encontrouResultado) {
                var termoPesquisado = encodeURIComponent(input.value.trim());
                window.location.href = 'https://pt.wikipedia.org/wiki/Especial:Pesquisar/' + termoPesquisado;
            }
        }

        function filtrarPorLetra(letra) {
            var table, tr, td, i, txtValue;
            table = document.getElementById("termosTable");
            tr = table.getElementsByTagName("tr");

            var letterFilters = document.getElementsByClassName("letter-filter");
            for (i = 0; i < letterFilters.length; i++) {
                letterFilters[i].classList.remove("active");
            }

            if (letra !== 'Todos') {
                event.target.classList.add("active");
            } else {
                document.querySelector('.letter-filters a').classList.add('active');
            }

            for (i = 1; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (letra === 'Todos' || txtValue.toUpperCase().startsWith(letra)) {
                        tr[i].style.display = "";  
                    } else {
                        tr[i].style.display = "none"; 
                    }
                }
            }
        }

        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('message')) {
            var message = urlParams.get('message');
            showMessage(message);
        }
    </script>
</body>
</html>
