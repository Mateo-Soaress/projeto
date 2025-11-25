<?php
    session_start();
    require_once __DIR__ . '/../src/conexao-bd.php';
    require_once __DIR__ . '/../src/Repositorios/ProdutoRepositorio.php';
    require_once __DIR__ . '/../src/Modelos/Produto.php';
    require_once __DIR__ . '/../src/Repositorios/CategoriaRepositorio.php';
    require_once __DIR__ . '/../src/Modelos/Categoria.php';
    
    $produtoRepositorio = new ProdutoRepositorio($pdo);
    $categoriaRepositorio = new CategoriaRepositorio($pdo);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
        $nome = trim($_POST['nome']) ?? '';
        $descricao = trim($_POST['descricao']) ?? '';
        $preco = $_POST['preco'] ?? '';
        $categoria = $_POST['categoria'] ?? '';
        $categoriaId = $categoriaRepositorio->buscarPorNome($categoria)->getId() ?? null;

        if ($nome === '' || $descricao === '' || $preco === '') {
            header('Location: form.php' . ($id ? '?id=' . $id . '&erro=campos' : '?erro=campos'));
            exit;
        }

        $produto = new Produto($id, $nome, $descricao, $preco, $categoriaId);

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
            $produto->setImagem('logo-granato.png');
        }

        if ($produto->getId()) {            
            $produtoRepositorio->atualizar($produto);
        } else {            
            $produtoRepositorio->salvar($produto);
        }

        header("Location: listar.php");
        exit();

    }

?>