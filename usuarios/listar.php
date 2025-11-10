<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');


}

require_once __DIR__ . "/../src/conexao-bd.php";
require_once __DIR__ . "/../src/Modelos/Usuario.php";
require_once __DIR__ . "/../src/Repositorios/UsuarioRepositorio.php";

$usuarioLogado = $_SESSION['usuario'];

if (!$usuarioLogado) {
    header('Location: login.php');
}

function pode(string $perm) {
    return in_array($perm, $_SESSION['permissoes'] ?? [], true);
}

$usuarioRepositorio = new UsuarioRepositorio($pdo);
$usuarios = $usuarioRepositorio->listar();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="icon" href="../img/loja-logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Saira:ital,wght@0,100..900;1,100..900&family=Sawarabi+Mincho&family=Sirivennela&display=swap" rel="stylesheet">
    <title>MateoRonan - Dashboard</title>
</head>
<body>
    <main>
        <section class="container-topo">
            <div class="topo-direita">
                <a href="../logout.php" class="link-sair">Sair</a>
                <p>Bem-vindo, <strong><?php echo htmlspecialchars($usuarioLogado); ?></strong></p>
            </div>
            <div class="conteudo">
                <h2>Painel Administrativo</h2>
            </div>
        </section>

        <section class="menu">
            <div class="item-menu">
                <a href="../dashboard.php">Dashboard</a>
            </div>
            <div class="item-menu">
                <a href="produtos/listar.php">Produtos</a>
            </div>
            <?php if (pode('usuarios.listar')): ?>
                <div class="item-menu">
                    <a href="usuarios/listar.php">Usuários</a>
                </div>
            <?php endif; ?>
            <div class="item-menu">
                <a href="categorias/listar.php">Categorias</a>
            </div>      
        </section>

        <section class="container-banner">
            <img src="../img/loja-banner.png" alt="Banner da Loja da Loja" class="logo-banner">
            <h1>Dashboard</h1>
            <img src="../img/ornamento-informatica.png" alt="Ornamento" class="ornamento">
        </section>

        <section class="container-table">            
            <table border="1">
                <tr class="header-row">
                    <th>Código</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Cpf</th>
                    <th>Perfil</th>
                    <th colspan=2>Ações</th>
                </tr>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= $usuario->getId() ?></td>
                        <td><?= $usuario->getNome() ?></td>
                        <td><?= $usuario->getEmail() ?></td>
                        <td><?= $usuario->getCpf() ?></td>
                        <td><?= $usuario->getPerfil() ?></td>
                        <td>
                            <form action="form.php?id=<?= $usuario->getId() ?>" method="POST">                            
                                <input type="submit" value="Editar" class="botao-editar">
                            </form>
                        </td>
                        <td>
                            <form action="excluir.php" method="POST">
                                <input type="hidden" name="usuarioId" id="usuarioId" value="<?= $usuario->getId() ?>" class="id-box">
                                <input type="submit" value="Excluir" class="botao-excluir">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <a href="form.php" class="link-cadastrar">Cadastrar Usuário</a>            
        </section>
    </main>
</body>
</html>