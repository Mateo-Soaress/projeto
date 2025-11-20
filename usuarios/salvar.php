<?php
    session_start();
    require_once __DIR__ . '/../src/conexao-bd.php';
    require_once __DIR__ . '/../src/Repositorios/UsuarioRepositorio.php';
    require_once __DIR__ . '/../src/Modelos/Usuario.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: listar.php');
        exit;
    }

    $id = isset($_POST['id']) && $_POST['id'] !== '' ? (int)$_POST['id'] : null;
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $cpf = $_POST['cpf'] ?? '';
    $perfil = $_POST['perfil'] ?? 'User';
    $senha = $_POST['senha'] ?? '';

    if ($nome === '' || $email === '' || $cpf === '' || $senha === '') {
        header('Location: form.php' . ($id ? '?id=' . $id . '&erro=campos' : '?erro=campos'));
        exit;
    }

    $repo = new UsuarioRepositorio($pdo);    

    if ($id) {
        $existente = $repo->buscarPorId($id);

        if (!$existente) {
            header('Location: listar.php?erro=inexistente');
            exit;
        }

        if ($senha === '') {
            $senhaParaObjeto = $existente->getSenha();
        }
        else {
            $senhaParaObjeto = $senha;
        }

        $usuario = new Usuario($id, $nome, $email, $cpf, $perfil, $senha);
        $repo->atualizar($usuario);
        header('Location: listar.php');
        exit;
    }
    else {
        if ($repo->buscarPorCpf($cpf)) {
            header('Location: form.php' . ($id ? '?id=' . $id . '&erro=cpfexistente' : '?erro=cpfexistente'));
            exit;
        }

        if ($repo->buscarPorEmail($email)) {
            header('Location: form.php' . ($id ? '?id=' . $id . '&erro=emailexistente' : '?erro=emailexistente'));
            exit;
        }

        $usuario = new Usuario(0, $nome, $email, $cpf, $perfil, $senha);
        $repo->salvar($usuario);
        header('Location: listar.php');
        exit;
    }

    $usuario = new Usuario(0, $nome, $email, $cpf, $perfil, $senha);
    $repo->salvar($usuario);
    header('Location: listar.php');
    exit;

?>