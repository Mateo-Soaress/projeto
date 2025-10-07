<?php
session_start();

require_once 'src/conexao-bd.php';
require_once 'src/Repositorios/ProdutoRepositorio.php';
require_once 'src/Repositorios/CategoriaRepositorio.php';
require_once 'src/Modelos/Produto.php';
require_once 'src/Modelos/Categoria.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$repoCategoria = new CategoriaRepositorio($pdo);

$id = $_POST['produtoId'] ?? 0;
$nome = trim($_POST['nome'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$preco = $_POST['preco'] ?? '';
$categoria = $_POST['categoria'] ?? '';

if ($id === 0 ||$nome === '' || $descricao === '' || $preco === '' || $categoria === '') {
    header('Location: editar-produto.php?erro=campos');
}

$categoriaId = $repoCategoria->buscarPorNome($categoria)->getId() ?? 0;

$repoProduto = new ProdutoRepositorio($pdo);

$repoProduto->atualizar(new Produto((int)$id, $nome, $descricao, (float)$preco, $categoriaId));
header('Location: admin.php');
exit;
?>