<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Carrinho - Loja de Roupas Jets</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/stylecarrinho.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"> <!-- ADICIONE ESTA LINHA -->
</head>
<body>
    <header id="header-placeholder"></header>
    <main>
        <h1>Seu Carrinho</h1>
        <div id="cart-items"></div>
        <div id="cart-total"></div>
        <div id="cart-actions" style="text-align:right; margin-top:20px;"></div>
        <div id="cart-loading" style="display:none;text-align:center;padding:20px;">
            <span class="loader"></span>
        </div>
    </main>
    <footer id="footer-placeholder"></footer>
    <script>
        // Carrega header/footer
        fetch('../components/Header.html').then(res => res.text()).then(data => {
            document.getElementById('header-placeholder').innerHTML = data;
        });
        fetch('../components/Footer.html').then(res => res.text()).then(data => {
            document.getElementById('footer-placeholder').innerHTML = data;
        });

        // Feedback visual (loading)
        function showLoading(show = true) {
            document.getElementById('cart-loading').style.display = show ? 'block' : 'none';
        }

        // Exibe itens do carrinho
        function renderCart() {
            showLoading(true);
            setTimeout(() => {
                const cart = JSON.parse(localStorage.getItem('cart') || '[]');
                const container = document.getElementById('cart-items');
                const totalDiv = document.getElementById('cart-total');
                const actionsDiv = document.getElementById('cart-actions');
                if (cart.length === 0) {
                    container.innerHTML = '<p style="padding:32px 0;text-align:center;color:#888;">Seu carrinho está vazio.</p>';
                    totalDiv.textContent = '';
                    actionsDiv.innerHTML = '';
                    showLoading(false);
                    return;
                }
                let total = 0;
                container.innerHTML = cart.map((item, idx) => {
                    const subtotal = item.preco * item.qtd;
                    total += subtotal;
                    return `
                        <div class="cart-card">
                            <img src="${item.imagem}" alt="${item.nome}">
                            <div style="flex:1;">
                                <strong>${item.nome}</strong>
                                <span class="cart-price">R$ ${item.preco.toFixed(2).replace('.', ',')}</span>
                                <div style="font-size:0.95em;color:#666;margin-top:4px;">
                                    Subtotal: <b>R$ ${(subtotal).toFixed(2).replace('.', ',')}</b>
                                </div>
                            </div>
                            <button class="cart-qtd-btn" onclick="updateQtd(${idx}, -1)" ${item.qtd === 1 ? 'disabled style="opacity:0.5;cursor:not-allowed;"' : ''}>-</button>
                            <span style="min-width:32px;text-align:center;">${item.qtd}</span>
                            <button class="cart-qtd-btn" onclick="updateQtd(${idx}, 1)">+</button>
                            <button class="cart-remove-btn" onclick="removeItem(${idx})">Remover</button>
                        </div>
                    `;
                }).join('');
                totalDiv.innerHTML = `<span>Total:</span> R$ ${total.toFixed(2).replace('.', ',')}`;
                actionsDiv.innerHTML = `
                    <button onclick="finalizarCompra()" class="btn btn-animado">Finalizar Compra</button>
                    <button onclick="limparCarrinho()" class="btn btn-animado" style="background:#e74c3c;color:#fff;margin-left:10px;">Esvaziar Carrinho</button>
                `;
                showLoading(false);
            }, 300); // Simula loading
        }

        // Atualiza quantidade
        function updateQtd(idx, delta) {
            let cart = JSON.parse(localStorage.getItem('cart') || '[]');
            cart[idx].qtd += delta;
            if (cart[idx].qtd < 1) cart[idx].qtd = 1;
            localStorage.setItem('cart', JSON.stringify(cart));
            renderCart();
            updateCartBadge();
        }

        // Remove item com confirmação
        function removeItem(idx) {
            if (!confirm('Tem certeza que deseja remover este item do carrinho?')) return;
            let cart = JSON.parse(localStorage.getItem('cart') || '[]');
            cart.splice(idx, 1);
            localStorage.setItem('cart', JSON.stringify(cart));
            renderCart();
            updateCartBadge();
        }

        // Limpar carrinho
        function limparCarrinho() {
            if (!confirm('Deseja realmente esvaziar o carrinho?')) return;
            showLoading(true);
            setTimeout(() => {
                localStorage.removeItem('cart');
                renderCart();
                updateCartBadge();
                showLoading(false);
            }, 300); // Mostra loading ao limpar
        }

        // Finalizar compra
        function finalizarCompra() {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            if (cart.length === 0) {
                alert('Seu carrinho está vazio!');
                return;
            }
            alert('Compra finalizada! (Funcionalidade demonstrativa)');
            localStorage.removeItem('cart');
            renderCart();
            updateCartBadge();
        }

        // Atualiza badge do carrinho no header
        function updateCartBadge() {
            const badge = document.getElementById('cart-badge');
            if (!badge) return;
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const totalQtd = cart.reduce((acc, item) => acc + item.qtd, 0);
            if (totalQtd > 0) {
                badge.textContent = totalQtd;
                badge.style.display = 'inline-block';
                badge.style.background = '#111';
                badge.style.color = '#fff';
            } else {
                badge.textContent = '';
                badge.style.display = 'none';
            }
        }

        // Loader CSS
        (function addLoaderStyle() {
            const style = document.createElement('style');
            style.innerHTML = `
            .loader {
                display: inline-block;
                width: 36px;
                height: 36px;
                border: 4px solid #e5e5e5;
                border-top: 4px solid #111;
                border-radius: 50%;
                animation: spin 0.8s linear infinite;
                margin: 0 auto;
            }
            @keyframes spin {
                0% { transform: rotate(0deg);}
                100% { transform: rotate(360deg);}
            }`;
            document.head.appendChild(style);
        })();

        renderCart();
        updateCartBadge();
    </script>
</body>
</html>