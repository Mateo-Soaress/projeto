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
require_once __DIR__ . '/../src/Modelos/Categoria.php';
require_once __DIR__ . '/../src/Repositorios/CategoriaRepositorio.php';

$repo = new CategoriaRepositorio($pdo);

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$modoEdicao = false;
$categoria = null;

if ($id) {

    if (method_exists($repo, 'buscarPorId')) {
        $categoria = $repo->buscarPorId($id);
    }

    if ($categoria) {
        $modoEdicao = true;
    }
    else {
        header('Location: listar.php');
        exit;
    }

}

$nome = $modoEdicao ? $categoria->getNome() : '';

$tituloPagina = $modoEdicao ? 'Editar Categoria' : 'Cadastrar Categoria';
$textoBotao = $modoEdicao ? 'Salvar' : 'Cadastrar';

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
                    <?php if ($_GET['erro'] === "categoriaexistente"): ?>
                        <p class="mensagem-erro">Categoria já existente com este nome.</p>
                    <?php elseif ($_GET['erro'] === "campos"): ?>
                        <p class="mensagem-erro">Preencha todos os campos.</p>
                    <?php endif; ?>
                <?php endif; ?>                

                <form action="salvar.php" method="post">
                    <?php if ($modoEdicao): ?>
                        <input type="hidden" name="id" value="<?= (int)$categoria->getId() ?>">
                    <?php endif; ?>

                    <input type="text" name="nome" id="nome" placeholder="Nome" value="<?= htmlspecialchars($nome) ?>" required>

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