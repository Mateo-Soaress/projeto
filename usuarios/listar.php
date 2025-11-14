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

$usuarioRepositorio = new UsuarioRepositorio($pdo);

$itens_por_pagina = filter_input(INPUT_GET, 'itens_por_pagina', FILTER_VALIDATE_INT) ?: 5;
$pagina_atual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$offset = ($pagina_atual - 1) * $itens_por_pagina;

$total_usuarios = $usuarioRepositorio->contarTotal();
$total_paginas = ceil($total_usuarios / $itens_por_pagina);

$ordem = filter_input(INPUT_GET, 'ordem') ?: null;
$direcao = filter_input(INPUT_GET, 'direcao') ?: 'ASC';

$usuarios = $usuarioRepositorio->buscarPaginado($itens_por_pagina, $offset, $ordem, $direcao);

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
            <h1>Lista de Usuários</h1>
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
                        <a href="<?= gerarUrlOrdenacao('id', $pagina_atual, $ordem, $direcao, $itens_por_pagina) ?>" style="color: inherit; text-decoration: none;">
                            Código <?= mostrarIconeOrdenacao('id', $ordem, $direcao) ?>
                        </a>
                    </th>
                    <th>
                        <a href="<?= gerarUrlOrdenacao('nome', $pagina_atual, $ordem, $direcao, $itens_por_pagina) ?>" style="color: inherit; text-decoration: none;">
                            Nome <?= mostrarIconeOrdenacao('nome', $ordem, $direcao) ?>
                        </a>
                    </th>
                    <th>
                        <a href="<?= gerarUrlOrdenacao('email', $pagina_atual, $ordem, $direcao, $itens_por_pagina) ?>" style="color: inherit; text-decoration: none;">
                            Email <?= mostrarIconeOrdenacao('email', $ordem, $direcao) ?>
                        </a>
                    </th>
                    <th>
                        <a href="<?= gerarUrlOrdenacao('cpf', $pagina_atual, $ordem, $direcao, $itens_por_pagina) ?>" style="color: inherit; text-decoration: none;">
                            Cpf <?= mostrarIconeOrdenacao('cpf', $ordem, $direcao) ?>
                        </a>
                    </th>
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

            <div class="paginacao">
                <?php if ($total_paginas > 1): ?>
                    <?php if ($pagina_atual > 1): ?>
                        <a href="?pagina=<?=$pagina_atual - 1?>&ordem=<?=htmlspecialchars($ordem)?>&direcao=<?=htmlspecialchars($direcao)?>$itens_por_pagina=<?=htmlspecialchars($itens_por_pagina)?>">Anterior</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <?php if ($i === $pagina_atual): ?>
                            <strong><?= $i ?></strong>
                        <?php else: ?>
                            <a href="?pagina=<?=$i?>&ordem=<?=htmlspecialchars($ordem)?>&direcao=<?=htmlspecialchars($direcao)?>$itens_por_pagina=<?=htmlspecialchars($itens_por_pagina)?>"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($pagina_atual < $total_paginas): ?>
                        <a href="?pagina=<?=$pagina_atual + 1?>&ordem=<?=htmlspecialchars($ordem)?>&direcao=<?=htmlspecialchars($direcao)?>$itens_por_pagina=<?=htmlspecialchars($itens_por_pagina)?>">Próximo</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <a href="form.php" class="link-cadastrar">Cadastrar Usuário</a>            
        </section>
    </main>
</body>
</html>