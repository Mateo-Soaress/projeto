<?php

session_start();

if (!isset($_SESSION['usuario'])) {
    session_abort();
    $usuarioLogado = null;
}
else {
    $usuarioLogado = $_SESSION['usuario'];
}

require "src/conexao-bd.php";
require "src/Modelos/Produto.php";
require "src/Repositorios/ProdutoRepositorio.php";
require "src/Modelos/Usuario.php";
require "src/Repositorios/UsuarioRepositorio.php";

$produtosRepositorio = new ProdutoRepositorio($pdo);

$filtroNome = trim(filter_input(INPUT_GET, 'barra-pesquisa')) ?: null;

if ($filtroNome) {   
    $dadosProdutos = $produtosRepositorio->listarComFiltro($filtroNome); 
} else {    
    $dadosProdutos = $produtosRepositorio->produtos();
}

if ($usuarioLogado) {
    $usuarioRepositorio = new UsuarioRepositorio($pdo);
    $usuario = $usuarioRepositorio->buscarPorEmail($usuarioLogado);
    $id = $usuario ? $usuario->getId() : null;
}
else {
    $id = null;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Saira:ital,wght@0,100..900;1,100..900&family=Sawarabi+Mincho&family=Sirivennela&display=swap" rel="stylesheet">
    <link rel="icon" href="img/loja-logo-sem-fundo.png">
    <title>MateoRonan - Página Inicial</title>
</head>

<body>
    <main>
        <section class="container-header">
            <img class="logo-loja" src="img/loja-logo-sem-fundo.png" alt="Logo da Loja MateoRonan">

            <form class="pesquisa-form" action="" method="GET">
                <input type="text" name="barra-pesquisa" id="barra-pesquisa" value="<?= htmlspecialchars($filtroNome) ?>">

                <button class="botao-pesquisar" type="submit"><img src="img/lupa.png" alt="Botão de Pesquisar"></button>
            </form>

            <div class="perfil-box">
                <a href="usuarios/form.php?id=<?= $id ?>"><img src="img/perfil.png" alt="Imagem do Perfil"></a>
                <p>Perfil</p>
            </div>
        </section>
        <section class="container-produtos">
            <?php foreach ($dadosProdutos as $produto): ?>
                <div class="container-produto">
                    <div class="container-produto">
                        <div class="container-imagem">
                            <img src="<?= $produto->getImagemDiretorio() ?>">
                        </div>
                        <h2 class="titulo-produto"><?= $produto->getNome() ?></h2>
                        <p class="descricao-produto"><?= $produto->getDescricao() ?></p>
                        <p class="preco-produto">R$<?= $produto->getPreco() ?></p>
                        <button class="botao-comprar">Comprar</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
        <section class="container-footer">
            <img class="logo-loja" src="img/loja-logo-sem-fundo.png" alt="Logo da Loja MateoRonan">

            <div class="informacoes-footer">
                <p>Copyright © 2025 MateoRonan. All rights reserved</p>
                <p>Made by Ronan & Mateo</p>
            </div>

            <img src="img/logo-loja.png" alt="">
        </section>
    </main>
<script>
    function confirmarExclusao(nome) {
        return window.confirm("Confirmar a exclusão do usuário '" + nome + "'?");
    }
</script>
</body>

</html>