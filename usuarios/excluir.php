<?php
session_start();

require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ .  '/../src/Repositorios/UsuarioRepositorio.php';
require_once __DIR__ . '/../src/Modelos/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$repo = new UsuarioRepositorio($pdo);

$repo->remover($repo->buscarPorId($_POST['usuarioId']));

header('Location: listar.php');
exit;
?>