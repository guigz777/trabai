document.addEventListener('DOMContentLoaded', () => {
    // Carrega o cabeçalho
    fetch('../components/Header.html')
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro ao carregar o cabeçalho.');
            }
            return response.text();
        })
        .then(data => {
            document.getElementById('header-placeholder').innerHTML = data;
        })
        .catch(error => console.error(error));

    // Carrega o rodapé
    fetch('../components/Footer.html')
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro ao carregar o rodapé.');
            }
            return response.text();
        })
        .then(data => {
            document.getElementById('footer-placeholder').innerHTML = data;
        })
        .catch(error => console.error(error));

    const logoutDiv = document.getElementById('logout-placeholder');
    const loginDiv = document.getElementById('login-placeholder'); // Div do botão de login

    // Função para renderizar o botão de login
    function renderLoginButton() {
        // Limpa os botões inicialmente
        logoutDiv.innerHTML = '';
        loginDiv.innerHTML = '';

        // Exibe apenas o botão de login
        loginDiv.innerHTML = `
            <button type="button" class="btn" id="login-btn">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </button>
        `;

        // Adiciona evento ao botão de login
        const loginBtn = document.getElementById('login-btn');
        loginBtn.addEventListener('click', () => {
            const username = prompt('Digite o usuário:');
            const password = prompt('Digite a senha:');
            login(username, password);
        });
    }

    // Função de login
    function login(username, password) {
        if (username === 'admin' && password === 'gui08090') {
            localStorage.setItem('user', 'admin');
            localStorage.setItem('password', 'gui08090');
            window.location.href = 'ProdutosCRUD.php'; // Redireciona para o estoque
        } else {
            alert('Usuário ou senha incorretos!');
        }
    }

    // Renderiza o botão de login ao carregar a página
    renderLoginButton();
});