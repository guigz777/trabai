<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

// Adicione no início do arquivo, após session_start()
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$mysqli = new mysqli('localhost', 'GUi', 'gui08090', 'lojaderoupas');
$msg = "";

// Adicionar produto
if (isset($_POST['add'])) {
    $nome = $_POST['nome'];
    $preco = str_replace(',', '.', $_POST['preco']);
    $desc = $_POST['descricao'];

    // Verifica se o usuário enviou uma URL válida
    if (!empty($_POST['imagem_url'])) {
        $imagem = $_POST['imagem_url'];
    } else {
        $imagem = null;
        $msg = "Por favor, insira uma URL válida para a imagem.";
    }

    if ($imagem) {
        $stmt = $mysqli->prepare("INSERT INTO produtos (nome, preco, imagem, descricao) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdss", $nome, $preco, $imagem, $desc);
        $stmt->execute();
        $msg = "Produto adicionado com sucesso!";
    }
}

// Atualizar produto
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $preco = str_replace(',', '.', $_POST['preco']);
    $desc = $_POST['descricao'];

    // Verifica se o usuário enviou uma URL válida
    if (!empty($_POST['imagem_url'])) {
        $imagem = $_POST['imagem_url'];
    } else {
        $imagem = $_POST['imagem_atual']; // Caso nenhuma URL seja alterada
    }

    if ($imagem) {
        $stmt = $mysqli->prepare("UPDATE produtos SET nome=?, preco=?, imagem=?, descricao=? WHERE id=?");
        $stmt->bind_param("sdssi", $nome, $preco, $imagem, $desc, $id);
        $stmt->execute();
        $msg = "Produto atualizado com sucesso!";
    }
}

// Excluir produto
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $mysqli->prepare("DELETE FROM produtos WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $msg = "Produto excluído com sucesso!";
}

// Buscar produto para edição
$editProduto = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $mysqli->query("SELECT * FROM produtos WHERE id=$id");
    $editProduto = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos CRUD - Loja de Roupas Nike</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/styleprodutos.css">
    <link rel="stylesheet" href="../assets/css/stylecrud.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <header id="header-placeholder"></header>
    <main>
        <section class="products-hero">
            <div class="products-hero-content">
                <h1 class="products-hero-title">Produtos Nike (CRUD)</h1>
                <p class="products-hero-desc">
                    Gerencie sua lista de produtos Nike: adicione, edite ou remova produtos facilmente.
                </p>
            </div>
            <img src="../assets/images/banner-nike.jpg" alt="Produtos Nike em destaque">
        </section>
        <?php if ($msg): ?>
            <div style="background:#dff0d8;color:#3c763d;padding:10px 20px;border-radius:8px;margin:20px auto;max-width:400px;text-align:center;">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>
        <form class="crud-form" method="post" action="">
            <h2 id="formTitle"><?php echo $editProduto ? 'Editar Produto' : 'Adicionar Produto'; ?></h2>
            <?php if ($editProduto): ?>
                <input type="hidden" name="id" value="<?php echo $editProduto['id']; ?>">
                <input type="hidden" name="imagem_atual" value="<?php echo $editProduto['imagem']; ?>">
            <?php endif; ?>
            <input type="text" name="nome" placeholder="Nome do Produto" required value="<?php echo $editProduto['nome'] ?? ''; ?>">
            <input type="text" name="preco" placeholder="Preço (ex: 199,90)" required value="<?php echo isset($editProduto['preco']) ? number_format($editProduto['preco'], 2, ',', '') : ''; ?>">
            
            <!-- Campo para URL da imagem -->
            <input type="text" name="imagem_url" placeholder="URL da Imagem" required value="<?php echo $editProduto['imagem'] ?? ''; ?>">
            
            <textarea name="descricao" placeholder="Descrição" required><?php echo $editProduto['descricao'] ?? ''; ?></textarea>
            <button type="submit" name="<?php echo $editProduto ? 'edit' : 'add'; ?>">Salvar</button>
            <?php if ($editProduto): ?>
                <a href="ProdutosCRUD.php" style="display:inline-block;margin-top:10px;">Cancelar edição</a>
            <?php endif; ?>
        </form>
        <section class="product-list" id="productList">
            <?php
            $result = $mysqli->query("SELECT * FROM produtos ORDER BY id DESC");
            if ($result && $result->num_rows > 0) {
                while ($prod = $result->fetch_assoc()) {
                    ?>
                    <article class="product-item">
                        <img src="<?php echo htmlspecialchars($prod['imagem']); ?>" alt="<?php echo htmlspecialchars($prod['nome']); ?>">
                        <h2><?php echo htmlspecialchars($prod['nome']); ?></h2>
                        <p><?php echo htmlspecialchars($prod['descricao']); ?></p>
                        <span>R$ <?php echo number_format($prod['preco'], 2, ',', '.'); ?></span>
                        <div class="crud-actions">
                            <a class="edit" href="?edit=<?php echo $prod['id']; ?>"><i class="bi bi-pencil"></i> Editar</a>
                            <a class="delete" href="?delete=<?php echo $prod['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este produto?')"><i class="bi bi-trash"></i> Excluir</a>
                        </div>
                    </article>
                    <?php
                }
            } else {
                echo "<p style='color:#888;'>Nenhum produto cadastrado.</p>";
            }
            ?>
        </section>
    </main>
    <footer id="footer-placeholder"></footer>
    <script src="../scripts/main.js"></script>
    <script>
        fetch('../components/Header.html')
          .then(res => res.text())
          .then(data => {
            document.getElementById('header-placeholder').innerHTML = data;
            // Insere o botão Sair apenas nesta página, após o header ser carregado
            var logout = document.createElement('a');
            logout.href = '?logout=1';
            logout.className = 'logout-btn-header';
            logout.innerHTML = 'Sair <i class="bi bi-box-arrow-right"></i>';
            var placeholder = document.getElementById('logout-placeholder');
            if (placeholder) placeholder.appendChild(logout);
          });

        fetch('../components/Footer.html')
          .then(res => res.text())
          .then(data => {
            document.getElementById('footer-placeholder').innerHTML = data;
          });
    </script>
</body>
</html>