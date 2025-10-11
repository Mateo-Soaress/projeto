<?php
session_start();

require_once 'src/conexao-bd.php';
require_once 'src/Repositorios/ProdutoRepositorio.php';
require_once 'src/Repositorios/CategoriaRepositorio.php';
require_once 'src/Modelos/Produto.php';
require_once 'src/Modelos/Categoria.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
$usuarioLogado = $_SESSION['usuario'];

$repo = new ProdutoRepositorio($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&display=swap" rel="stylesheet">
    <link rel="icon" href="img/loja-logo.png">
    <title>MateoRonan - Admin</title>
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
        <section class="container-banner">
            <img src="img/loja-banner.png" alt="Banner da Loja da Loja" class="logo-banner">
            <h1>Administração de Produtos</h1>
            <img src="img/ornamento-informatica.png" alt="Ornamento" class="ornamento">
        </section>
        <section class="container-table">            
            <a href="cadastrar-produto.php" class="link-cadastrar">Cadastrar Produto</a>
            <a href="cadastrar-categoria.php" class="link-cadastrar">Cadastrar Categoria</a>
            <table border="1">
                <tr class="header-row">
                    <th>Código</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Categoria</th>
                    <th colspan=2>Ações</th>
                </tr>
                <?php foreach ($repo->listar() as $produto): ?>
                <tr> <?php $repoCategoria = new CategoriaRepositorio($pdo); ?>
                    <td><?= $produto->getId() ?></td>
                    <td><?= $produto->getNome() ?></td>
                    <td><?= $produto->getDescricao() ?></td>
                    <td>R$<?= $produto->getPreco() ?></td>
                    <td><?= $repoCategoria->buscarPorId($produto->getCategoriaId())->getNome() ?></td>
                    <td>
                        <form action="editar-produto.php" method="POST">
                            <input type="number" name="produtoId" id="produtoId" value="<?= htmlspecialchars($produto->getId(), ENT_QUOTES, 'UTF-8') ?>" class="id-box">
                            <input type="submit" value="Editar" class="botao-editar">
                        </form>
                    </td>
                    <td>
                        <form action="excluirProduto.php" method="POST">
                            <input type="number" name="produtoId" id="produtoId" value="<?= htmlspecialchars($produto->getId(), ENT_QUOTES, 'UTF-8') ?>" class="id-box">
                            <input type="submit" value="Excluir" class="botao-excluir">
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </section>
    </main>
</body>
</html>