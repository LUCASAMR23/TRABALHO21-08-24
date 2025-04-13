<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexão com o banco
$conn = new mysqli("localhost", "root", "", "aluno_cadastro");

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Recebe os dados do formulário
$nome = $_POST["nome"];
$email = $_POST["email"];
$telefone = $_POST["telefone"];
$RG = $_POST["RG"];
$CPF = $_POST["CPF"];
$data_nascimento = $_POST["data_nascimento"];

// Upload da foto
$foto_nome = "";
if (isset($_FILES["FOTO"]) && $_FILES["FOTO"]["error"] === UPLOAD_ERR_OK) {
    $foto_nome = basename($_FILES["FOTO"]["name"]);
    $destino = "uploads/" . $foto_nome;
    move_uploaded_file($_FILES["FOTO"]["tmp_name"], $destino);
}

// Prepara e executa a query
$sql = "INSERT INTO aluno (nome, email, telefone, RG, CPF, data_nascimento, FOTO)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $nome, $email, $telefone, $RG, $CPF, $data_nascimento, $foto_nome);

if ($stmt->execute()) {
    echo "Aluno cadastrado com sucesso!";
} else {
    echo "Erro ao cadastrar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
