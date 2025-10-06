<?php
session_start();

require_once 'src/conexao-bd.php';
require_once 'src/Repositorios/ProdutoRepositorio.php';
require_once 'src/Repositorios/CategoriaRepositorio.php';
require_once 'src/Modelos/Produto.php';
require_once 'src/Modelos/Categoria.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cadastrar-produto.php');
    exit;
}

$repoCategoria = new CategoriaRepositorio($pdo);

$nome = trim($_POST['nome'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$preco = $_POST['preco'] ?? '';
$categoria = trim($_POST['categoria'] ?? '');

if ($nome === '' || $descricao === '' || $preco === '' || $categoria === '') {
    header('Location: cadastrar-produto.php?erro=campos');
    exit;
}

$categoriaId = $repoCategoria->buscarPorNome($categoria)->getId() ?? 0;


$repoProduto = new ProdutoRepositorio($pdo);

$repoProduto->salvar(new Produto(0, $nome, $descricao, (float)$preco, $categoriaId));
header('Location: admin.php');
exit;

?>