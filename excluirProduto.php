<?php
session_start();

require_once 'src/conexao-bd.php';
require_once 'src/Repositorios/ProdutoRepositorio.php';
require_once 'src/Modelos/Produto.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$repo = new ProdutoRepositorio($pdo);

$id = $_POST['produtoId'] ?? '';
$repo->remover($repo->buscarPorId($id));

header('Location: admin.php');
exit;
?>