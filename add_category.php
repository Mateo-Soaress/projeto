<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $numero = $_POST['numero'];
    $sql = "INSERT INTO categorias (nome, numero) VALUES ('$nome', '$numero')";
    $conn->query($sql);
    header("Location: home.php");
}
?>

<link rel="stylesheet" href="style.css">
<div class="container">
    <h2>Cadastro de Categoria</h2>
    <form method="POST">
        <input type="text" name="nome" placeholder="Nome da categoria" required>
        <input type="number" name="numero" placeholder="Número da categoria" required>
        <button type="submit">Cadastrar</button>
    </form>
</div>