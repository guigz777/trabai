<?php
session_start();
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';
    // Usuário e senha definidos conforme solicitado
    if ($usuario === 'admin' && $senha === 'gui08090') {
        $_SESSION['usuario'] = $usuario;
        header('Location: ProdutosCRUD.php');
        exit;
    } else {
        $erro = 'Usuário ou senha inválidos!';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Loja de Roupas Jets</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/stylelogin.css">
    <style>
        
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-logo">
            <img src="../images/LOGO2.png" alt="Logo da Loja de Roupas Jet">
        </div>
        <h1>login</h1>
        <form method="post" autocomplete="off">
            <?php if ($erro): ?>
                <div class="login-error"><?php echo $erro; ?></div>
            <?php endif; ?>
            <input type="text" name="usuario" placeholder="Usuário" required autofocus>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
    </div>
    <script>
        // Se o login foi bem-sucedido, salve o usuário e a senha no localStorage
        <?php if (isset($_SESSION['usuario']) && $_SESSION['usuario'] === 'admin'): ?>
            localStorage.setItem('user', 'admin');
            localStorage.setItem('password', 'gui08090');
        <?php endif; ?>
    </script>
</body>
</html>