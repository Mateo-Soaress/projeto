<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
}

require_once __DIR__ . "/../src/conexao-bd.php";
require_once __DIR__ . "/../src/Modelos/Categoria.php";
require_once __DIR__ . "/../src/Repositorios/CategoriaRepositorio.php";

$categoriaLogado = $_SESSION['usuario'];

if (!$categoriaLogado) {
    header('Location: login.php');
}

$categoriaRepositorio = new CategoriaRepositorio($pdo);

$itens_por_pagina = filter_input(INPUT_GET, 'itens_por_pagina', FILTER_VALIDATE_INT) ?: 5;
$pagina_atual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$offset = ($pagina_atual - 1) * $itens_por_pagina;

$total_categorias = $categoriaRepositorio->contarTotal();
$total_paginas = ceil($total_categorias / $itens_por_pagina);

$ordem = filter_input(INPUT_GET, 'ordem') ?: null;
$direcao = filter_input(INPUT_GET, 'direcao') ?: 'ASC';

$categorias = $categoriaRepositorio->buscarPaginado($itens_por_pagina, $offset, $ordem, $direcao);

function gerarUrlOrdenacao($campo, $paginaAtual, $ordemAtual, $direcaoAtual, $itensPorPagina) {
    $novaDirecao = ($ordemAtual === $campo && $direcaoAtual === 'ASC') ? 'DESC' : 'ASC';
    return "?pagina={$paginaAtual}&ordem={$campo}&direcao={$novaDirecao}&itens_por_pagina={$itensPorPagina}";
}

function mostrarIconeOrdenacao($campo, $ordemAtual, $direcaoAtual) {
    if ($ordemAtual !== $campo) {
        return '&#8661';
    }
    return $direcaoAtual === 'ASC' ? '⇑' : '⇓';
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
                <p>Bem-vindo, <strong><?php echo htmlspecialchars($categoriaLogado); ?></strong></p>
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
                <a href="../produtos/listar.php">Produtos</a>
            </div>
            <?php if (pode('usuarios.listar')): ?>
                <div class="item-menu">
                    <a href="../usuarios/listar.php">Usuários</a>
                </div>
            <?php endif; ?>
            <div class="item-menu">
                <a href="../categorias/listar.php">Categorias</a>
            </div>      
        </section>

        <section class="container-banner">
            <img src="../img/loja-banner.png" alt="Banner da Loja da Loja" class="logo-banner">
            <h1>Lista de Categorias</h1>
            <img src="../img/ornamento-informatica.png" alt="Ornamento" class="ornamento">
        </section>

        <form action="" method="GET" class="form-paginacao">
            <label for="itens_por_pagina">Itens por página: </label>
            <select name="itens_por_pagina" id="itens_por_pagina" onchange="this.form.submit()">
                <option value="5" <?= $itens_por_pagina == 5 ? 'selected' : '' ?>>5</option>
                <option value="10" <?= $itens_por_pagina == 10 ? 'selected' : '' ?>>10</option>
                <option value="20" <?= $itens_por_pagina == 20 ? 'selected' : '' ?>>20</option>
            </select>
        </form>

        <section class="container-table">            
            <table border="1">
                <tr class="header-row">
                    <th>
                        <a href="<?= gerarUrlOrdenacao('nome', $pagina_atual, $ordem, $direcao, $itens_por_pagina) ?>" style="color: inherit; text-decoration: none;">
                            Categoria <?= mostrarIconeOrdenacao('nome', $ordem, $direcao) ?>
                        </a>
                    </th>
                    <th colspan=2>Ações</th>
                </tr>
                <?php foreach ($categorias as $categoria): ?>                    
                    <tr>
                        <td><?= $categoria->getNome() ?></td>
                        <td>
                            <form action="form.php?id=<?= $categoria->getId() ?>" method="POST">                            
                                <input type="submit" value="Editar" class="botao-editar">
                            </form>
                        </td>
                        <td>
                            <form action="excluir.php" method="POST" onsubmit='return confirmarExclusao(<?= json_encode($categoria->getNome()) ?>)'>
                                <input type="hidden" name="categoriaId" id="categoriaId" value="<?= $categoria->getId() ?>" class="id-box">                                
                                <input type="submit" value="Excluir" class="botao-excluir">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <div class="paginacao">
                <?php if ($total_paginas > 1): ?>
                    <?php if ($pagina_atual > 1): ?>
                        <a href="?pagina=<?=$pagina_atual - 1?>&ordem=<?=htmlspecialchars($ordem)?>&direcao=<?=htmlspecialchars($direcao)?>&itens_por_pagina=<?=htmlspecialchars($itens_por_pagina)?>">Anterior</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <?php if ($i === $pagina_atual): ?>
                            <strong><?= $i ?></strong>
                        <?php else: ?>
                            <a href="?pagina=<?=$i?>&ordem=<?=htmlspecialchars($ordem)?>&direcao=<?=htmlspecialchars($direcao)?>&itens_por_pagina=<?=htmlspecialchars($itens_por_pagina)?>"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($pagina_atual < $total_paginas): ?>
                        <a href="?pagina=<?=$pagina_atual + 1?>&ordem=<?=htmlspecialchars($ordem)?>&direcao=<?=htmlspecialchars($direcao)?>&itens_por_pagina=<?=htmlspecialchars($itens_por_pagina)?>">Próximo</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <a href="form.php" class="link-cadastrar">Cadastrar Categorias</a>    
        </section>
    </main>

<script>
    function confirmarExclusao(nome) {        
        return window.confirm("Deseja confirmar a exclusão da categoria '" + nome + "'?");
    }
</script>
</body>
</html>