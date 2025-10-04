<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}
include "config.php";

$result = $conn->query("SELECT produtos.*, categorias.nome AS categoria FROM produtos 
                        LEFT JOIN categorias ON produtos.categoria_id = categorias.id");
?>

<link rel="stylesheet" href="style.css">
<div class="container">
    <h2>Administração de Produtos</h2>
    <a href="add_product.php">Cadastrar Produto</a>
    <a href="add_category.php">Cadastrar Categoria</a>
    <a href="logout.php">Sair</a>
    <table border="1">
        <tr>
            <th>Código</th><th>Nome</th><th>Descrição</th><th>Preço</th><th>Categoria</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['nome'] ?></td>
            <td><?= $row['descricao'] ?></td>
            <td><?= $row['preco'] ?></td>
            <td><?= $row['categoria'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>