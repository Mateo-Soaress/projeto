<?php
    session_start();
    require_once __DIR__ . '/../src/conexao-bd.php';
    require_once __DIR__ . '/../src/Repositorios/CategoriaRepositorio.php';
    require_once __DIR__ . '/../src/Modelos/Categoria.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: listar.php');
        exit;
    }

    $id = isset($_POST['id']) && $_POST['id'] !== '' ? (int)$_POST['id'] : null;
    $nome = trim($_POST['nome'] ?? '');

    if ($nome === '') {
        header('Location: form.php' . ($id ? '?id=' . $id . '&erro=campos' : '?erro=campos'));
        exit;
    }

    $repo = new CategoriaRepositorio($pdo);    

    if ($id) {
        $existente = $repo->buscarPorId($id);

        if (!$existente) {
            header('Location: listar.php?erro=inexistente');
            exit;
        }       

        $categoria = new Categoria($id, $nome);
        $repo->atualizar($categoria);
        header('Location: listar.php');
        exit;
    }
    else {
        if ($repo->buscarPorNome($nome)) {
            header('Location: form.php' . ($id ? '?id=' . $id . '&erro=categoriaexistente' : '?erro=categoriaexistente'));
            exit;
        }

            $categoria = new Categoria(0, $nome);
            $repo->salvar($categoria);
            header('Location: listar.php');
            exit;
    }

    $categoria = new Categoria(0, $nome);
    $repo->salvar($categoria);
    header('Location: listar.php');
    exit;

?>