<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit;
}

$usuarioLogado = $_SESSION['usuario'] ?? null;
if (!$usuarioLogado) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Modelos/Usuario.php';
require_once __DIR__ . '/../src/Repositorios/UsuarioRepositorio.php';

$repo = new UsuarioRepositorio($pdo);

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$modoEdicao = false;
$usuario = null;

if ($id) {

    if (method_exists($repo, 'buscarPorId')) {
        $usuario = $repo->buscarPorId($id);
    }

    if ($usuario) {
        $modoEdicao = true;
    }
    else {
        header('Location: listar.php');
        exit;
    }

}

$nome = $modoEdicao ? $usuario->getNome() : '';
$email = $modoEdicao ? $usuario->getEmail() : '';
$cpf = $modoEdicao ? $usuario->getCpf() : '';
$perfil = $modoEdicao ? $usuario->getPerfil() : 'User';
$senha = $modoEdicao ? $usuario->getSenha() : '';

$tituloPagina = $modoEdicao ? 'Perfil' : 'Sign-in';
$textoBotao = $modoEdicao ? 'Salvar' : 'Registrar';

$registrado = $_GET['registrado'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/form.css">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="icon" href="../img/loja-logo.png">
    <title>MateoRonan - <?= htmlspecialchars($tituloPagina)?></title>
</head>
<body>
    <main>
        <section class="container-banner">
            <img src="../img/loja-banner.png" alt="Banner da Loja" class="logo-banner">
        </section>
        <section class="container-form">
            <h2><?= htmlspecialchars($tituloPagina) ?></h2>
            <div class="form-wrapper">
                <?php if (isset($_GET['erro'])): ?>
                    <?php if ($_GET['erro'] === "emailexistente"): ?>
                        <p class="mensagem-erro">Usuário já existente com este e-mail.</p>
                    <?php elseif ($_GET['erro'] === "cpfexistente"): ?>
                        <p class="mensagem-erro">Usuário já existente com este CPF.</p>
                    <?php elseif ($_GET['erro'] === "campos"): ?>
                        <p class="mensagem-erro">Preencha todos os campos.</p>
                    <?php endif; ?>
                <?php endif; ?>                

                <form action="salvar.php" method="post">
                    <?php if ($modoEdicao): ?>
                        <input type="hidden" name="id" value="<?= (int)$usuario->getId() ?>">
                    <?php endif; ?>

                    <input type="text" name="nome" id="nome" placeholder="Nome" value="<?= htmlspecialchars($nome) ?>" required>
                    <input type="email" name="email" id="email" placeholder="E-mail" value="<?= htmlspecialchars($email) ?>" required>
                    <input type="text" name="cpf" id="cpf" placeholder="CPF" value="<?= htmlspecialchars($cpf) ?>" required>
                    
                    <select id="perfil">
                        <option value="User" <?= $perfil === 'User' ? 'selected' : '' ?>>User</option>
                        <option value="Admin" <?= $perfil === 'Admin' ? 'selected' : '' ?>>Admin</option>
                    </select>

                    <input type="password" name="senha" id="senha" placeholder="Senha" value="<?= htmlspecialchars($senha) ?>" required>

                    <input type="submit" class="botao-cadastrar" value="<?= htmlspecialchars($textoBotao) ?>">
                </form>

                
                <a href="listar.php" class="botao-voltar">Voltar</a>
                
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
    })
</script>
</body>
</html>