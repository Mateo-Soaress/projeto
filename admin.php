<?php
session_start();

require_once 'src/conexao-bd.php';
require_once 'src/Repositorios/ProdutoRepositorio.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
$usuarioLogado = $_SESSION['usuario'];

$repo = new ProdutoRepositorio($pdo);

$produtos = $repo->listar();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/admin.css">
    <title>MateoRonan - Admin</title>
</head>
<body>
    <main>
        <section class="container-topo">
            <div class="topo-direita">
                <p>Bem-vindo, <strong><?php echo htmlspecialchars($usuarioLogado); ?></strong></p>
            </div>
            <div class="conteudo">
                <h2>Painel Administrativo</h2>
            </div>
        </section>
        <section class="container-admin-banner">
            <img src="img/loja-banner.png" alt="Logo da Loja" class="logo-admin">
            <h1>Administração de Produtos</h1>
            <img src="img/ornamento-informatica.png" alt="Ornamento" class="ornamento">
        </section>
        <section class="container-table">            
            <a href="cadastrar-produto.php" class="link-cadastrar">Cadastrar Produto</a>
            <a href="cadastrar-categoria.php" class="link-cadastrar">Cadastrar Categoria</a>
            <a href="logout.php" class="link-sair">Sair</a>
            <table border="1">
                <tr>
                    <th>Código</th><th>Nome</th><th>Descrição</th><th>Preço</th><th>Categoria</th>
                </tr>
                <?php foreach ($produtos as $produto): ?>
                <tr>
                    <td><?= $produto['id'] ?></td>
                    <td><?= $produto['nome'] ?></td>
                    <td><?= $produto['descricao'] ?></td>
                    <td><?= $produto['preco'] ?></td>
                    <td><?= $produto['categoria'] ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </section>
    </main>
</body>
</html>