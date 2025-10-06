<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

$usuarioLogado = $_SESSION['usuario'];
$erro = $_GET['erro'] ?? '';

require_once 'src/conexao-bd.php';
require_once 'src/Repositorios/CategoriaRepositorio.php';
require_once 'src/Modelos/Categoria.php';

$repo = new CategoriaRepositorio($pdo);

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
    <title>MateoRonan - Cadastro Produto</title>
</head>
<body>
    <div class="container-form">
        <h2>Cadastro de Produto</h2>
        <div class="form-wrapper">
            <?php if ($erro === 'campos'): ?>
                <p class="mensagem-erro">Preencha todos os campos obrigatórios.</p>
            <?php elseif ($erro === 'categoriainvalida'): ?>
                <p class="mensagem-erro">Categoria inválida. Selecione uma categoria existente.</p>
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
                <button type="submit">Cadastrar</button>
            </form>

            <a href="admin.php" class="botao-voltar">Voltar</a>
        </div>
    </div>
</body>
</html>