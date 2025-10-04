<?php
session_start();

require_once 'src/conexao-bd.php';
require_once 'src/Repositorios/ProdutoRepositorio.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$repo = new ProdutoRepositorio($pdo);

$produtos = $repo->listar();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>MateoRonan - Admin</title>
</head>
<body>
    <main>
        <section class="container">
            <h2>Administração de Produtos</h2>
            <a href="add_product.php">Cadastrar Produto</a>
            <a href="add_category.php">Cadastrar Categoria</a>
            <a href="logout.php">Sair</a>
            <table border="1">
                <tr>
                    <th>Código</th><th>Nome</th><th>Descrição</th><th>Preço</th><th>Categoria</th>
                </tr>
                <?php foreach ($produtos as $produto): ?>
                <tr>
                    <td><?= $produto['id'] ?></td>
                    <td><?= $produto['nome'] ?></td>
                    <td><?= $produto['descricao'] ?></td>
                    <td><?= $produto['preco'] ?></td>
                    <td><?= $produto['categoria'] ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </section>
    </main>
</body>
</html>