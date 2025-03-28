<?php
session_start();
if (!isset($_SESSION['alunos'])) {
    $_SESSION['alunos'] = [];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id_aluno']) && isset($_SESSION['alunos'][$_POST['id_aluno']])) {
        $id_aluno = $_POST['id_aluno'];
    } else {
        $id_aluno = rand(1000, 9999);
    }
    
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];
    $RG = $_POST["RG"];
    $CPF = $_POST["CPF"];
    $data_nascimento = $_POST["data_nascimento"];
    $estado_aluno = isset($_POST["estado_aluno"]) ? $_POST["estado_aluno"] : "Inativo";

    if (!is_dir("uploads")) {
        mkdir("uploads", 0777, true);
    }

    if (isset($_FILES["FOTO"]) && $_FILES["FOTO"]["error"] == 0) {
        $extensao = pathinfo($_FILES["FOTO"]["name"], PATHINFO_EXTENSION);
        $foto_nome = "uploads/" . uniqid() . "." . $extensao;
        move_uploaded_file($_FILES["FOTO"]["tmp_name"], $foto_nome);
    } else {
        $foto_nome = isset($_SESSION['alunos'][$id_aluno]['foto']) ? $_SESSION['alunos'][$id_aluno]['foto'] : "default.png";
    }

    $_SESSION['alunos'][$id_aluno] = [
        'nome' => $nome,
        'email' => $email,
        'telefone' => $telefone,
        'RG' => $RG,
        'CPF' => $CPF,
        'data_nascimento' => $data_nascimento,
        'estado_aluno' => $estado_aluno,
        'foto' => $foto_nome
    ];
}

if (isset($_GET['delete'])) {
    $id_delete = $_GET['delete'];
    unset($_SESSION['alunos'][$id_delete]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha do Aluno</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; text-align: center; }
        .container { width: 50%; margin: 20px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        .header { background: #007BFF; color: white; padding: 15px; font-size: 22px; font-weight: bold; border-radius: 10px 10px 0 0; }
        .foto img { width: 120px; height: 120px; border-radius: 50%; border: 3px solid #007BFF; margin-top: -60px; background: white; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: left; }
        .status { font-weight: bold; color: green; }
        .footer { background: #007BFF; color: white; padding: 10px; font-size: 14px; border-radius: 0 0 10px 10px; margin-top: 20px; }
        .actions { margin-top: 10px; }
        .actions a, .actions button { text-decoration: none; background: red; color: white; padding: 5px 10px; border-radius: 5px; border: none; cursor: pointer; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">Lista de Alunos</div>
    <?php foreach ($_SESSION['alunos'] as $id => $aluno) { ?>
        <div class="container">
            <div class="foto">
                <img src="<?php echo $aluno['foto']; ?>" alt="Foto do Aluno">
            </div>
            <table>
                <tr><th>Nome</th><td><?php echo $aluno['nome']; ?></td></tr>
                <tr><th>Email</th><td><?php echo $aluno['email']; ?></td></tr>
                <tr><th>Telefone</th><td><?php echo $aluno['telefone']; ?></td></tr>
                <tr><th>RG</th><td><?php echo $aluno['RG']; ?></td></tr>
                <tr><th>CPF</th><td><?php echo $aluno['CPF']; ?></td></tr>
                <tr><th>Data de Nascimento</th><td><?php echo $aluno['data_nascimento']; ?></td></tr>
                <tr><th>Estado do Aluno</th><td class="status"><?php echo $aluno['estado_aluno']; ?></td></tr>
            </table>
            <div class="actions">
                <a href="?delete=<?php echo $id; ?>" onclick="return confirm('Tem certeza que deseja excluir este aluno?');">Excluir</a>
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_aluno" value="<?php echo $id; ?>">
                    <input type="text" name="nome" value="<?php echo $aluno['nome']; ?>" required>
                    <input type="email" name="email" value="<?php echo $aluno['email']; ?>" required>
                    <input type="text" name="telefone" value="<?php echo $aluno['telefone']; ?>" required>
                    <input type="text" name="RG" value="<?php echo $aluno['RG']; ?>" required>
                    <input type="text" name="CPF" value="<?php echo $aluno['CPF']; ?>" required>
                    <input type="date" name="data_nascimento" value="<?php echo $aluno['data_nascimento']; ?>" required>
                    <select name="estado_aluno">
                        <option value="Ativo" <?php if($aluno['estado_aluno'] == 'Ativo') echo 'selected'; ?>>Ativo</option>
                        <option value="Inativo" <?php if($aluno['estado_aluno'] == 'Inativo') echo 'selected'; ?>>Inativo</option>
                    </select>
                    <br><br>
                    <input type="file" name="FOTO">
                    <button type="submit">Atualizar</button>
                </form>
            </div>
        </div>
    <?php } ?>
    <div class="footer">© 2025 Lucas Amorim - Sistema Acadêmico</div>
</div>

</body>
</html>