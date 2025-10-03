<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $cpf = $_POST['cpf'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nome, email, cpf, senha) VALUES ('$nome', '$email', '$cpf', '$senha')";
    if ($conn->query($sql)) {
        header("Location: index.php");
    } else {
        echo "Erro: " . $conn->error;
    }
}
?>

<link rel="stylesheet" href="style.css">
<div class="container">
    <h2>Registre-se</h2>
    <form method="POST">
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="email" name="email" placeholder="E-mail" required>
        <input type="text" name="cpf" placeholder="CPF" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Registrar</button>
    </form>
</div>