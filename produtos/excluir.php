<?php
session_start();

require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ .  '/../src/Repositorios/ProdutoRepositorio.php';
require_once __DIR__ . '/../src/Modelos/Produto.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$repo = new ProdutoRepositorio($pdo);

$repo->remover($repo->buscarPorId($_POST['produtoId']));

header('Location: listar.php');
exit;
?>