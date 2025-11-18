<?php
require "../src/conexao-bd.php";
require "../src/Modelos/Usuario.php";
require "../src/Repositorios/UsuarioRepositorio.php";

date_default_timezone_set('America/Sao_Paulo');
$rodapeDataHora = date('d/m/Y H:i');

$usuarioRepositorio = new UsuarioRepositorio($pdo);
$usuarios = $usuarioRepositorio->listar();

$imagePath = '../img/loja-logo.png';
$imageData = base64_encode(file_get_contents($imagePath));
$imageSrc = 'data:image/jpeg;base64,' . $imageData;
?>

<head>
    <meta charset="UTF-8">

<style>
body {
    text-align: center;
    font-size: small;
}

table {
    margin: 15px auto;
    color: black;
    border-radius: 10px;
}

table th, table td {
    padding: 7px;
    border: 1px solid black;
    margin-top: 5px;
}

.pdf-img {
    width: 100px;
    margin: auto;
}

.pdf-footer {
    border: 1px solid gray;
}
</style>

</head>

<img src="<?= $imageSrc ?>" alt="Logo MateoRonan" class="pdf-img">

<h3>Listagem de Usuários</h3>

<table>
    <thead>
        <tr>
            <th>Usuário</th>
            <th>E-mail</th>
            <th>CPF</th>
            <th>Perfil</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($usuarios as $usuario): ?>
            <tr>
                <td><?= $usuario->getNome() ?></td>
                <td><?= $usuario->getEmail() ?></td>
                <td><?= $usuario->getCpf() ?></td>
                <td><?= $usuario->getPerfil() ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="pdf-footer">
    Gerado em: <?= htmlspecialchars($rodapeDataHora) ?>
</div>