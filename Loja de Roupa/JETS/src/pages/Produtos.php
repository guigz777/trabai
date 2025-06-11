<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - Loja de Roupas Jets</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/styleprodutos.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <header id="header-placeholder"></header>
    <main>
        <section class="products-hero">
            <div class="products-hero-content">
                <h1 class="products-hero-title">Produtos Jets</h1>
                <p class="products-hero-desc">
                    Confira nossa seleção de produtos Jets com tecnologia, conforto e estilo para todos os momentos.
                </p>
            </div>
            <img src="" alt="Produtos Jet em destaque">
        </section>
        <section class="product-list" id="productList">
            <?php
            $mysqli = new mysqli('localhost', 'GUi', 'gui08090', 'lojaderoupas');
            if ($mysqli->connect_errno) {
                echo "<p style='color:red;'>Erro ao conectar ao banco de dados.</p>";
            } else {
                $result = $mysqli->query("SELECT * FROM produtos");
                if ($result && $result->num_rows > 0) {
                    while ($prod = $result->fetch_assoc()) {
                        ?>
                        <article class="product-item">
                            <img src="<?php echo htmlspecialchars($prod['imagem']); ?>" alt="<?php echo htmlspecialchars($prod['nome']); ?>">
                            <h2><?php echo htmlspecialchars($prod['nome']); ?></h2>
                            <p><?php echo htmlspecialchars($prod['descricao']); ?></p>
                            <span>R$ <?php echo number_format($prod['preco'], 2, ',', '.'); ?></span>
                            <button class="add-to-cart-btn" 
                                    data-id="<?php echo $prod['id']; ?>"
                                    data-nome="<?php echo htmlspecialchars($prod['nome']); ?>"
                                    data-preco="<?php echo $prod['preco']; ?>"
                                    data-imagem="<?php echo htmlspecialchars($prod['imagem']); ?>">
                                Adicionar ao Carrinho
                            </button>
                        </article>
                        <?php
                    }
                } else {
                    echo "<p style='color:#888;'>Nenhum produto cadastrado.</p>";
                }
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
          });

        fetch('../components/Footer.html')
          .then(res => res.text())
          .then(data => {
            document.getElementById('footer-placeholder').innerHTML = data;
          });

        // Atualiza badge do carrinho
        function updateCartBadge() {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const badge = document.getElementById('cart-badge');
            if (cart.length > 0) {
                badge.textContent = cart.reduce((acc, item) => acc + item.qtd, 0);
                badge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
            }
        }

        // Adiciona produto ao carrinho
        document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const nome = this.dataset.nome;
                const preco = parseFloat(this.dataset.preco);
                const imagem = this.dataset.imagem;
                let cart = JSON.parse(localStorage.getItem('cart') || '[]');
                const idx = cart.findIndex(item => item.id === id);
                if (idx > -1) {
                    cart[idx].qtd += 1;
                } else {
                    cart.push({id, nome, preco, imagem, qtd: 1});
                }
                localStorage.setItem('cart', JSON.stringify(cart));
                updateCartBadge();
                alert('Produto adicionado ao carrinho!');
            });
        });

        // Atualiza badge ao carregar a página
        updateCartBadge();
    </script>
</body>
</html>
<?php
// Fechar a conexão com o banco de dados
if ($mysqli) {
    $mysqli->close();
}
?>