<?php
session_start();
$usuarioLogado = $_SESSION['usuario'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/form.css">
    <title>MateoRonan - Cadastro Categoria</title>
</head>
<body>
    <section class="container-form">
        <h2>Cadastro de Categoria</h2>
        <form method="POST">
            <input type="text" name="nome" placeholder="Nome da categoria" required>
            <input type="number" name="numero" placeholder="Número da categoria" required>
            <button type="submit">Cadastrar</button>
        </form>
    </section>
</body>
</html>