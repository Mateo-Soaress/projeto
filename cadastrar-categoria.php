<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$usuarioLogado = $_SESSION['usuario'];
$erro = $_GET['erro'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/form.css">
    <title>MateoRonan - Cadastro Categoria</title>
</head>
<body>
    <section action="inserirCategoria" class="container-form">
        <h2>Cadastro de Categoria</h2>
        <div class="form-wrapper">
            <?php if ($erro === 'campos'): ?>
                <p class="mensagem-erro">Preencha o nome da categoria.</p>
            <?php elseif ($erro === 'nomeexistente'): ?>
                <p class="mensagem-erro">Categoria já cadastrada</p>
            <?php endif; ?>

            <form action="inserirCategoria.php"  method="POST">
                <input type="text" name="nome" id="nome" placeholder="Nome da categoria" required>           
                <button type="submit">Cadastrar</button>
            </form>

            <a href="admin.php" class="botao-voltar">Voltar</a>
        </div>        
    </section>

<script>
    window.addEventListener('DOMContentLoaded', function(){
        var msg = document.querySelector('.mensagem-erro');
        if (msg) {
            setTimeout(function(){
                msg.classList.add('oculto');
            }, 5000);
        }
    })
</script>
</body>
</html>