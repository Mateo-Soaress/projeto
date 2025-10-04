<?php
session_start();
require_once 'src/conexao-bd.php';
require_once 'src/Repositorios/UsuarioRepositorio.php';
require_once 'src/Modelos/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$cpf = $_POST['cpf'] ?? '';
$senha = $_POST['senha'] ?? '';

if ($nome === '' || $email === '' || $cpf === '' || $senha === '') {
    header('Location: signin.php?erro=campos');
    exit;
}

$repo = new UsuarioRepositorio($pdo);

if (!$repo->buscarPorEmail($email) && !$repo->buscarPorCpf($cpf)) {
    $usuario = new Usuario(0, $nome, $email, $cpf, $senha);
    $repo->salvar($usuario);
    header('Location: login.php');
    exit;
}

if (!$repo->buscarPorCpf($cpf)) {
    header('Location: signin.php?erro=cpfexistente');
    exit;
}

header('Location: signin.php?erro=emailexistente');
exit;

?>