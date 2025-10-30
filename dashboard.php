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
        <section class="container-header">
            <img class="logo-loja" src="img/loja-logo-sem-fundo.png" alt="Logo da Loja MateoRonan">            

            <h1>Dashboard</h1>

            <div class="perfil-box">
                <a href="editarPerfil"><img src="img/perfil.png" alt="Imagem do Perfil"></a>
                <p>Perfil</p>
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
           <img src="img/loja-banner.png" alt="Banner da Loja" class="logo-banner">
        </section>
    </main>
</body>
</html>