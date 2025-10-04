<?php
session_start();
$erro = $_GET['erro'] ?? '';
$registrado = $_GET['registrado'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/login.css">
    <title>MateoRonan - Sign-in</title>
</head>
<body>
    <main>
        <section class="container-form">
            <h2>Registre-se</h2>
            <div class="form-wrapper">
                <?php if ($erro === "emailexistente"): ?>
                    <p class="mensagem-erro">Usuário já existente com este e-mail.</p>
                <?php elseif ($erro === "cpfexistente"): ?>
                    <p class="mensagem-erro">Usuário já existente com este CPF.</p>
                <?php elseif ($erro === "campos"): ?>
                    <p class="mensagem-erro">Preencha todos os campos.</p>
                <?php elseif ($registrado === "true"): ?>
                    <p class="mensagem-sucesso">Usuário cadastrado com sucesso! 
                        </br><a href="login.php" class="link-login">Ir para a página de Login</a>
                    </p>
                <?php endif; ?>

                <form action="registrar.php" method="post">
                    <input type="text" name="nome" id="nome" placeholder="Nome" required>
                    <input type="email" name="email" id="email" placeholder="E-mail" required>
                    <input type="text" name="cpf" id="cpf" placeholder="CPF" required>
                    <input type="password" name="senha" id="senha" placeholder="Senha" required>

                    <input type="submit" class="botao-cadastrar" value="Registrar">
                </form>
            </div>
        </section>
    </main>
    
<script>
    window.addEventListener('DOMContentLoaded', function(){
        var msg_erro = document.querySelector('.mensagem-erro');
        if (msg_erro) {
            setTimeout(function(){
                msg_erro.classList.add('oculto');
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