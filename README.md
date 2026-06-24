# Caronas no Campus

## Descrição

**Caronas no Campus** é uma aplicação web desenvolvida em PHP que facilita o compartilhamento de caronas entre alunos do campus. O objetivo é conectar estudantes que precisam se deslocar, reduzindo custos com combustível e promovendo a sustentabilidade.

## Funcionalidades Principais

- **Cadastro e Autenticação**: Registro de novos usuários e sistema de login seguro
- **Oferta de Caronas**: Usuários podem oferecer caronas especificando origem, destino, data, hora e número de vagas
- **Busca de Caronas**: Busca por origem e destino para encontrar caronas disponíveis
- **Reserva de Caronas**: Passageiros podem reservar vagas em caronas oferecidas
- **Gerenciamento de Reservas**: Visualizar e cancelar reservas realizadas
- **Perfil de Usuário**: Visualizar e atualizar informações pessoais

## Tecnologias Utilizadas

- **Backend**: PHP 7.4+
- **Banco de Dados**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, Bootstrap 5
- **JavaScript**: Vanilla JS para interações
- **Padrão Arquitetural**: MVC (Model-View-Controller)

## Estrutura do Projeto

```
caronas-no-campus/
├── app/
│   ├── controllers/
│   │   ├── UserController.php
│   │   ├── CaronaController.php
│   │   └── ReservaController.php
│   ├── models/
│   │   ├── User.php
│   │   ├── Carona.php
│   │   └── Reserva.php
│   └── views/
│       ├── index.php
│       ├── login.php
│       ├── registro.php
│       └── ...
├── config/
│   ├── config.php
│   └── database.php
├── database/
│   └── schema.sql
├── public/
│   ├── index.php
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── script.js
│   └── images/
├── README.md
└── .gitignore
```

## Requisitos de Sistema

- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Servidor web (Apache, Nginx, etc.)
- Navegador moderno (Chrome, Firefox, Safari, Edge)

## Instalação

### 1. Clonar o Repositório

```bash
git clone https://github.com/dudufc/caronas-no-campus.git
cd caronas-no-campus
```

### 2. Criar o Banco de Dados

```bash
mysql -u root -p < database/schema.sql
```

### 3. Configurar o Banco de Dados

Editar `config/database.php` com suas credenciais:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'seu_usuario');
define('DB_PASSWORD', 'sua_senha');
define('DB_NAME', 'caronas_campus');
```

### 4. Iniciar o Servidor

```bash
php -S localhost:8000 -t public/
```

Acesse a aplicação em `http://localhost:8000`

## Uso

### Registrar Novo Usuário

1. Clique em "Registrar" na página inicial
2. Preencha os dados: nome, email, telefone e senha
3. Clique em "Registrar"

### Fazer Login

1. Clique em "Login"
2. Insira email e senha
3. Clique em "Entrar"

### Oferecer uma Carona

1. Estando logado, clique em "Oferecer Carona"
2. Preencha os dados: origem, destino, data, hora e número de vagas
3. Clique em "Oferecer Carona"

### Buscar Caronas

1. Na página inicial, preencha origem e destino
2. Clique em "Buscar Caronas"
3. Selecione uma carona e clique em "Ver Detalhes"
4. Clique em "Reservar" para fazer a reserva

### Gerenciar Reservas

1. Clique em "Minhas Reservas"
2. Visualize todas as suas reservas
3. Cancele uma reserva se necessário

## Segurança

- Senhas são criptografadas com bcrypt
- Prepared statements para prevenir SQL injection
- Validação de entrada em todos os formulários
- Sessões seguras para autenticação

## Commits Realizados

1. **Estrutura Inicial do Projeto** - Criação da estrutura MVC e arquivos de configuração
2. **Implementação do Banco de Dados** - Script SQL com tabelas de usuários, caronas e reservas
3. **Desenvolvimento das Funcionalidades Principais** - Controllers e models para gerenciamento de caronas e reservas
4. **Finalização e Interface** - Views com Bootstrap, CSS e documentação

## Autor

Eduardo França - Aluno IFRS Ibirubá

## Licença

Este projeto está sob a licença MIT.

## Contato

Para dúvidas ou sugestões, entre em contato através do email: dudufc@example.com

---

**Última atualização**: 24 de junho de 2026
