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
require_once __DIR__ . '/../src/Modelos/Produto.php';
require_once __DIR__ . '/../src/Repositorios/ProdutoRepositorio.php';
require_once __DIR__ . '/../src/Modelos/Categoria.php';
require_once __DIR__ . '/../src/Repositorios/CategoriaRepositorio.php';

$produtoRepositorio = new ProdutoRepositorio($pdo);
$categoriaRepositorio = new CategoriaRepositorio($pdo);

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$modoEdicao = false;
$produto = null;

if ($id) {

    if (method_exists($produtoRepositorio, 'buscarPorId')) {
        $produto = $produtoRepositorio->buscarPorId($id);
    }

    if ($produto) {
        $modoEdicao = true;
    }
    else {
        header('Location: listar.php');
        exit;
    }

}

$nome = $modoEdicao ? $produto->getNome() : '';
$descricao = $modoEdicao ? $produto->getDescricao() : '';
$preco = $modoEdicao ? $produto->getPreco() : '';
$categoriaId = $modoEdicao ? $produto->getCategoriaId() : null;

if ($modoEdicao) {
    if (method_exists($produto, 'getImagem')) {
        $valorImagem = $produto->getImagem();
    } else {
        $valorImagem = '';
    }
} else {
    $valorImagem = '';
}

$tituloPagina = $modoEdicao ? 'Editar produto' : 'Cadastrar produto';
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
                    <?php if ($_GET['erro'] === "campos"): ?>
                        <p class="mensagem-erro">Preencha todos os campos.</p>
                    <?php endif; ?>
                <?php endif; ?>

                <form action="salvar.php" method="post" enctype="multipart/form-data">
                    <?php if ($modoEdicao): ?>
                        <input type="hidden" name="id" value="<?= (int)$produto->getId() ?>">
                    <?php endif; ?>

                    <input type="text" name="nome" id="nome" placeholder="Nome do produto" required value="<?= htmlspecialchars($nome) ?>">

                    <input type="text" name="descricao" id="descricao" placeholder="Descrição do produto" value="<?= htmlspecialchars($descricao) ?>">

                    <input type="number" step="0.01" name="preco" id="preco" placeholder="Preço do produto" required value="<?= htmlspecialchars($preco) ?>">
                    
                    <select name="categoria" id="categoria" required>
                        <?php foreach($categoriaRepositorio->listar() as $categoria): ?>
                            <option value="<?= htmlspecialchars($categoria->getNome(), ENT_QUOTES, 'UTF-8'); ?>" <?= isset($categoriaId) && $categoriaId == $categoria->getId() ? 'selected' : '' ?>>
                                <?= htmlspecialchars($categoria->getNome()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <input type="file" name="imagem" id="imagem" accept="image/*">
                    <?php if (!empty($valorImagem)): ?>
                        <div class="preview-imagem">
                            <p>Imagem atual: <?= htmlspecialchars($valorImagem) ?></p>
                            <img src="<?= htmlspecialchars('../uploads/' . $valorImagem) ?>" alt="Imagem do produto" style="max-width:200px;display:block;margin-top:8px;">
                            <input type="hidden" name="imagem_existente" value="<?= htmlspecialchars($valorImagem) ?>">
                        </div>
                    <?php endif; ?>

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