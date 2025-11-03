<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
}

$usuarioLogado = $_SESSION['usuario'];

if (!$usuarioLogado) {
    header('Location: login.php');
}

function pode(string $perm) {
    return in_array($perm, $_SESSION['permissoes'] ?? [], true);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/dashboard.css">
     <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech&display=swap" rel="stylesheet">
    <title>MateoRonan - Dashboard</title>
</head>
<body>
    <main>
        <section class="container-topo">
            <div class="topo-direita">
                <a href="logout.php" class="link-sair">Sair</a>
                <p>Bem-vindo, <strong><?php echo htmlspecialchars($usuarioLogado); ?></strong></p>
            </div>
            <div class="conteudo">
                <h2>Painel Administrativo</h2>
            </div>
        </section>

        <section class="menu">
            <div class="item-menu">
                <a href="dashboard.php">Dashboard</a>
            </div>
            <div class="item-menu">
                <a href="produtos/listar.php">Produtos</a>
            </div>
            <div class="item-menu">
                <a href="usuarios/listar.php">Usuários</a>
            </div>
            <div class="item-menu">
                <a href="categorias/listar.php">Categorias</a>
            </div>            
        </section>

        <section class="container-banner">
            <img src="img/loja-banner.png" alt="Banner da Loja da Loja" class="logo-banner">
            <h1>Dashboard</h1>
            <img src="img/ornamento-informatica.png" alt="Ornamento" class="ornamento">
        </section>
    </main>
</body>
</html>