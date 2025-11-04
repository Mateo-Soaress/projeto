<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

$produtoId = $_POST['produtoId'] ?? 0;
$usuarioLogado = $_SESSION['usuario'] ?? '';
$erro = $_GET['erro'] ?? '';

require_once 'src/conexao-bd.php';
require_once 'src/Repositorios/ProdutoRepositorio.php';
require_once 'src/Repositorios/CategoriaRepositorio.php';
require_once 'src/Modelos/Produto.php';
require_once 'src/Modelos/Categoria.php';

$repoCategoria = new CategoriaRepositorio($pdo);
$repoProduto = new ProdutoRepositorio($pdo);

$produto = $repoProduto->buscarPorId($produtoId);
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
    <link rel="icon" href="img/loja-logo.png">
    <title>MateoRonan - Editar Produto</title>
</head>
<body>
    <main>
        <section class="container-form">
            <div class="form-wrapper">
                <?php if ($erro === 'campos'): ?>
                    <p class="mensagem-erro">Preencha todos os campos obrigatórios.</p>
                <?php endif; ?>

                <form action="atualizarProduto.php" method="POST">
                    <input type="number" name="produtoId" id="produtoId" value="<?= htmlspecialchars($produto->getId(), ENT_QUOTES, 'UTF-8'); ?>" class="id-box">
                    <input type="text" name="nome" id="nome" placeholder="Nome do produto" value="<?= htmlspecialchars($produto->getNome(), ENT_QUOTES, 'UTF-8'); ?>" required>
                    <input type="text" name="descricao" id="descricao" placeholder="Descrição do produto" value="<?= htmlspecialchars($produto->getDescricao(), ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="number" step="0.01" name="preco" id="preco" placeholder="Preço do produto" value="<?= htmlspecialchars($produto->getPreco(), ENT_QUOTES, 'UTF-8'); ?>" required>
                    <select name="categoria" id="categoria" required>
                        <option value="">Escolha uma categoria</option>
                        <?php foreach($repoCategoria->listar() as $categoria): ?>
                            <option value="<?= htmlspecialchars($categoria->getNome(), ENT_QUOTES, 'UTF-8'); ?>"
                            <?php if ($categoria->getId() === $produto->getCategoriaId()): ?> selected <?php endif; ?>>
                                <?= htmlspecialchars($categoria->getNome()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="botao-editar">Editar</button>
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
    })
</script>
</body>
</html>