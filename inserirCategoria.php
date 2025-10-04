<?php
    session_start();
    require_once 'src/conexao-bd.php';
    require_once 'src/Repositorios/CategoriaRepositorio.php';
    require_once 'src/Modelos/Categoria.php';

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header('Loction: cadastrar-categoria.php');
        exit;
    }

    

?>