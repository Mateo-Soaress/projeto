<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$usuarioLogado = $_SESSION['usuario'];
$erro = $_GET['erro'] ?? '';
$cadastro = $_GET['cadastro'];
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
    <main>
        <section action="inserirCategoria" class="container-form">
            <h2>Cadastro de Categoria</h2>
            <div class="form-wrapper">
                <?php if ($erro === 'campos'): ?>
                    <p class="mensagem-erro">Preencha o nome da categoria.</p>
                <?php elseif ($erro === 'nomeexistente'): ?>
                    <p class="mensagem-erro">Categoria já cadastrada.</p>
                <?php elseif ($cadastro === 'true'): ?>
                    <p class="mensagem-sucesso">Categoria cadastrada com êxito.</p>
                <?php endif; ?>

                <form action="inserirCategoria.php"  method="POST">
                    <input type="text" name="nome" id="nome" placeholder="Nome da categoria" required>           
                    <button type="submit">Cadastrar</button>
                </form>

                <a href="admin.php" class="botao-voltar">Voltar</a>
            </div>        
        </section>
    </main>

<script>
    window.addEventListener('DOMContentLoaded', function(){
        var msg = document.querySelector('.mensagem-erro');
        if (msg) {
            setTimeout(function(){
                msg.classList.add('oculto');
            }, 5000);
        }

        var msg_sucesso = document.querySelector('.mensagem-sucesso');
        if (msg_sucesso) {
            setTimeout(function(){
                msg_sucesso.classList.add('oculto');
            }, 10000);
        }
    })
</script>
</body>
</html>