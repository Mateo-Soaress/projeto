<?php
session_start();
$usuarioLogado = $_SESSION['usuario'] ?? null;
$erro = $_GET['erro'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario'] = $usuario['nome'];
            header("Location: home.php");
            exit;
        } else {
            echo "Senha incorreta!";
        }
    } else {
        echo "Usuário não encontrado!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>MateoRonan - Login</title>
</head>
<body>
    <?php
    if ($usuarioLogado) : ?>
        <section class="container-topo">
            <div class="topo-direita">
                <p>Você já está logado 
                    <strong><?php echo htmlspecialchars($usuarioLogado); ?></strong>
                </p>
                <form action="logout.php" method="POST">
                    <button type="submit" class="botao-sair">Sair</button>
                </form>
            </div>
            <div class="conteudo">
                <a href="admin.php" class="link-admin">Ir para Painel Administrativo</a>
            </div>
        </section>

    <?php else : ?>
        <section class="container-form">
            <h2>Login</h2>
            <div class="form-wrapper">
                <?php if ($erro === 'credenciais') : ?>
                    <p class="mensagem-erro">Email ou senha incorretos.</p>
                <?php elseif ($erro === 'campos') : ?>
                    <p class="mensagem-erro">Preencha email e senha.</p>
                <?php endif; ?>

                <form action = "autenticar.php" method="POST">
                    <input type="email" name="email" placeholder="E-mail" required>
                    <input type="password" name="senha" placeholder="Senha" required>
                    <button type="submit">Entrar</button>
                </form>
                <a href="register.php">Registre-se</a>
            </div>           
        </section>
    <?php endif; ?>
</body>
</html>