<?php
    session_start();
    require_once 'src/conexao-bd.php';
    require_once 'src/Repositorios/CategoriaRepositorio.php';
    require_once 'src/Modelos/Categoria.php';

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header('Location: cadastrar-categoria.php');
        exit;
    }

    $nome = trim($_POST['nome'] ?? '');

    if ($nome === '') {
        header('Location: cadastrar-categoria.php?erro=campos');
        exit;
    }

    $repo = new CategoriaRepositorio($pdo);

    if ($repo->buscarPorNome($nome)) {
        header('Location: cadastrar-categoria.php?erro=nomeexistente');
        exit;
    }

    $repo->salvar(new Categoria(0, $nome));
    header('Location: cadastrar-categoria.php?cadastro=true');
    exit;

?>