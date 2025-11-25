<?php
session_start();

require_once 'src/conexao-bd.php';
require_once 'src/Repositorios/CategoriaRepositorio.php';
require_once 'src/Modelos/Categoria.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

$usuarioLogado = $_SESSION['usuario'];
$erro = $_GET['erro'] ?? '';
$cadastro = $_GET['cadastro'] ?? '';

$repo = new CategoriaRepositorio($pdo);

$valorImagem = '';

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
    <title>MateoRonan - Cadastro Produto</title>
</head>
<body>
    <main>
        <section class="container-form">
            <h2>Cadastro de Produto</h2>
            <div class="form-wrapper">
                <?php if ($erro === 'campos'): ?>
                    <p class="mensagem-erro">Preencha todos os campos obrigatórios.</p>                
                <?php elseif ($cadastro === 'true'): ?>
                    <p class="mensagem-sucesso">Produto cadastrado com êxito.</p>
                <?php endif; ?>

                <form action="inserirProduto.php" method="POST">
                    <input type="text" name="nome" id="nome" placeholder="Nome do produto" required>
                    <input type="text" name="descricao" id="descricao" placeholder="Descrição do produto">
                    <input type="number" step="0.01" name="preco" id="preco" placeholder="Preço do produto" required>
                    <select name="categoria" id="categoria" required>
                        <?php foreach($repo->listar() as $categoria): ?>
                            <option value="<?= htmlspecialchars($categoria->getNome(), ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars($categoria->getNome()); ?></option>
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
                    <button type="submit" class="botao-cadastrar">Cadastrar</button>
                </form>

                <a href="admin.php" class="botao-voltar">Voltar</a>
            </div>
        </section>
    </main>
</body>
</html>