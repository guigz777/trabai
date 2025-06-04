# Loja de Roupas Nike

Projeto de um site de vendas inspirado na Nike, desenvolvido para fins educacionais. O sistema permite visualizar produtos, adicionar ao carrinho, enviar mensagens de contato e realizar o gerenciamento de produtos (CRUD) via painel administrativo.

## Funcionalidades

- **Página inicial** com destaques e promoções.
- **Listagem de produtos** com integração ao banco de dados MySQL.
- **Carrinho de compras** com armazenamento local (localStorage).
- **Formulário de contato** que salva mensagens em arquivo `.txt`.
- **Login administrativo** para acesso ao CRUD de produtos.
- **CRUD de produtos** (adicionar, editar, excluir) no painel do administrador.
- **Páginas institucionais**: Sobre e Política de Privacidade.
- **Layout responsivo** e moderno, inspirado no visual da Nike.
  
## Tecnologias Utilizadas

- **PHP** (backend e integração com MySQL)
- **MySQL** (armazenamento dos produtos)
- **HTML5, CSS3, JavaScript**
- **Bootstrap Icons**
- **LocalStorage** (carrinho)
- **Live Server** (desenvolvimento local)

## Como rodar o projeto

1. **Clone o repositório** e coloque na sua pasta do XAMPP/WAMP (ex: `htdocs`).
2. **Configure o banco de dados MySQL**:
   - Crie um banco chamado `lojaderoupas`.
   - Crie a tabela `produtos` com os campos: `id`, `nome`, `preco`, `imagem`, `descricao`.
3. **Acesse pelo navegador**:  
   `http://localhost/loja-roupas-nike/src/pages/index.html`
4. **Login administrativo**:
   - Usuário: `admin`
   - Senha: `gui08090`

## Observações

- As mensagens do formulário de contato são salvas em [`src/pages/contatos.txt`](src/pages/contatos.txt).
- O painel CRUD só é acessível após login.
- O carrinho funciona apenas no navegador (não há integração de pedidos no backend).

## Autor

Guilherme Saraiva

---

Projeto para fins didáticos. Não é afiliado à Nike.
