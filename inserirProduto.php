<?php
session_start();

require_once 'src/conexao-bd.php';
require_once 'src/Repositorios/ProdutoRepositorio.php';
require_once 'src/Repositorios/CategoriaRepositorio.php';
require_once 'src/Modelos/Produto.php';
require_once 'src/Modelos/Categoria.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cadastrar-produto.php');
    exit;
}

$repoCategoria = new CategoriaRepositorio($pdo);

$nome = trim($_POST['nome'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$preco = $_POST['preco'] ?? '';
$categoria = $_POST['categoria'] ?? '';

if ($nome === '' || $descricao === '' || $preco === '' || $categoria === '') {
    header('Location: cadastrar-produto.php?erro=campos');
    exit;
}

$categoriaId = $repoCategoria->buscarPorNome($categoria)->getId() ?? 0;

$produto = new Produto(0, $nome, $descricao, (float)$preco, $categoriaId);

$uploadsDir = __DIR__ . '/../uploads/';
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0755, true);
}

if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $tmpPath = $_FILES['imagem']['tmp_name'];
    
    $imgInfo = @getimagesize($tmpPath);
    if ($imgInfo !== false) {            
        $mime = $imgInfo['mime'];
        $ext = '';
        
        switch ($mime) {
            case 'image/jpeg':
                $ext = '.jpg';
                break;
            case 'image/png':
                $ext = '.png';
                break;
            case 'image/gif':
                $ext = '.gif';
                break;
            default:
                $ext = image_type_to_extension($imgInfo[2]) ?: '';
        }
        
        $filename = uniqid('img_', true) . $ext;
        $destination = $uploadsDir . $filename;

        if (move_uploaded_file($tmpPath, $destination)) {
            $produto->setImagem($filename);
        }
    }
} elseif (!empty($_POST['imagem_existente'])) {    
    $produto->setImagem($_POST['imagem_existente']);
} else {    
    $produto->setImagem('loja-logo.png');
}

$repoProduto = new ProdutoRepositorio($pdo);

$repoProduto->salvar(new Produto(0, $nome, $descricao, (float)$preco, $categoriaId));
header('Location: cadastrar-produto.php?cadastro=true');
exit;

?>