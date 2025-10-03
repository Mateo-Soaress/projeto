<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $categoria_id = $_POST['categoria'];

    $sql = "INSERT INTO produtos (nome, descricao, preco, categoria_id) 
            VALUES ('$nome', '$descricao', '$preco', '$categoria_id')";
    $conn->query($sql);
    header("Location: home.php");
}
$categorias = $conn->query("SELECT * FROM categorias");
?>

<link rel="stylesheet" href="style.css">
<div class="container">
    <h2>Cadastro de Produto</h2>
    <form method="POST">
        <input type="text" name="nome" placeholder="Nome do produto" required>
        <input type="text" name="descricao" placeholder="Descrição do produto">
        <input type="number" step="0.01" name="preco" placeholder="Preço do produto" required>
        <select name="categoria">
            <?php while($cat = $categorias->fetch_assoc()): ?>
                <option value="<?= $cat['id'] ?>"><?= $cat['nome'] ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Cadastrar</button>
    </form>
</div>