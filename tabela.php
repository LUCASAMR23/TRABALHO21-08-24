<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "aluno_cadastro");

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$sql = "SELECT * FROM aluno";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Tabela de Alunos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            padding: 30px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            background: white;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #444;
            color: white;
        }
        img {
            max-height: 60px;
        }
    </style>
</head>
<body>
    <h2>Lista de Alunos Cadastrados</h2>
    <table>
        <tr>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Telefone</th>
            <th>RG</th>
            <th>CPF</th>
            <th>Data de Nascimento</th>
            <th>Foto</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row["nome"]) ?></td>
                <td><?= htmlspecialchars($row["email"]) ?></td>
                <td><?= htmlspecialchars($row["telefone"]) ?></td>
                <td><?= htmlspecialchars($row["RG"]) ?></td>
                <td><?= htmlspecialchars($row["CPF"]) ?></td>
                <td><?= htmlspecialchars($row["data_nascimento"]) ?></td>
                <td>
                    <?php if (!empty($row["FOTO"])): ?>
                        <img src="uploads/<?= htmlspecialchars($row["FOTO"]) ?>" alt="Foto">
                    <?php else: ?>
                        Sem foto
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
