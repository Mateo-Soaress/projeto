<?php
session_start();

require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ .  '/../src/Repositorios/CategoriaRepositorio.php';
require_once __DIR__ . '/../src/Modelos/Categoria.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$repo = new CategoriaRepositorio($pdo);

$repo->remover($repo->buscarPorId($_POST['categoriaId']));

header('Location: listar.php');
exit;
?>