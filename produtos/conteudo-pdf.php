<?php
require "../src/conexao-bd.php";
require "../src/Modelos/Produto.php";
require "../src/Repositorios/ProdutoRepositorio.php";
require "../src/Modelos/Categoria.php";
require "../src/Repositorios/CategoriaRepositorio.php";

date_default_timezone_set('America/Sao_Paulo');
$rodapeDataHora = date('d/m/Y H:i');

$produtoRepositorio = new ProdutoRepositorio($pdo);
$produtos = $produtoRepositorio->listar();

$categoriaRepositorio = new CategoriaRepositorio($pdo);

$imagePath = '../img/loja-logo.png';
$imageData = base64_encode(file_get_contents($imagePath));
$imageSrc = 'data:image/jpeg;base64,' . $imageData;
?>

<head>
    <meta charset="UTF-8">

<style>
body {
    text-align: center;
    font-size: small;
}

table {
    width: 70%;
    margin: 15px auto;
    color: black;
    border-radius: 10px;
}

table th, table td {
    padding: 7px;
    border: 1px solid black;
    margin-top: 5px;
}

.pdf-img {
    width: 100px;
    margin: auto;
}

.pdf-footer {
    border: 1px solid gray;
}
</style>

</head>

<img src="<?= $imageSrc ?>" alt="Logo MateoRonan" class="pdf-img">

<h3>Listagem de Produtos</h3>

<table>
    <thead>
        <tr>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Preço</th>
            <th>Categoria</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($produtos as $produto): ?>
            <tr>
                <td><?= $produto->getNome() ?></td>
                <td><?= $produto->getDescricao() ?></td>
                <td>R$<?= $produto->getPreco() ?></td>
                <td><?= $categoriaRepositorio->buscarPorId($produto->getCategoriaId())->getNome() ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="pdf-footer">
    Gerado em: <?= htmlspecialchars($rodapeDataHora) ?>
</div>