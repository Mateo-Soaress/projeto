<?php
session_start();
require_once 'src/conexao-bd.php';
require_once 'src/Repositorios/UsuarioRepositorio.php';
require_once 'src/Modelos/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

if ($email === '' || $senha === '') {
    header('Location: login.php/erro==campos');
    exit;
}

$repo = new UsuarioRepositorio($pdo);

if ($repo->autenticar($email, $senha)) {
    session_regenerate_id(true);
    $_SESSION['usuario'] = $email;
    header('Location: dashboard.php');
    exit;
}

header('Location: login.php?erro=credenciais');
exit;
