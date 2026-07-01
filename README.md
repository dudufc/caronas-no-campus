# Caronas no Campus

Aplicação web desenvolvida em PHP que facilita o compartilhamento de caronas entre alunos do campus. O objetivo é conectar estudantes que precisam se deslocar, reduzindo custos com combustível e promovendo a sustentabilidade.

---

## Funcionalidades

- **Cadastro e Autenticação** — Registro de novos usuários e login seguro
- **Oferta de Caronas** — Especifique origem, destino, data, hora e número de vagas
- **Busca de Caronas** — Encontre caronas disponíveis por origem e destino
- **Reserva de Caronas** — Reserve vagas em caronas oferecidas por outros alunos
- **Gerenciamento de Reservas** — Visualize e cancele suas reservas
- **Gestão de Caronas Oferecidas** — Motoristas podem visualizar, aceitar ou recusar pedidos de passageiros
- **Perfil de Usuário** — Visualize e atualize suas informações pessoais

---

## Tecnologias

- **Backend:** PHP 7.4+
- **Banco de Dados:** MySQL 5.7+
- **Frontend:** HTML5, CSS3, Bootstrap 5
- **JavaScript:** Vanilla JS
- **Arquitetura:** MVC (Model-View-Controller)

---

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

---

## Requisitos

- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Servidor web (Apache, Nginx ou PHP built-in server)
- Navegador moderno

---

## Instalação

**1. Clonar o repositório**
```bash
git clone https://github.com/dudufc/caronas-no-campus.git
cd caronas-no-campus
```

**2. Criar o banco de dados**
```bash
mysql -u root -p < database/schema.sql
```

**3. Configurar as credenciais**

Edite `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'seu_usuario');
define('DB_PASSWORD', 'sua_senha');
define('DB_NAME', 'caronas_campus');
```

**4. Iniciar o servidor**

---

## Uso

**Registrar usuário**
1. Clique em "Registrar"
2. Preencha nome, email, telefone e senha
3. Clique em "Registrar"

**Login**
1. Clique em "Login"
2. Insira email e senha

**Oferecer carona**
1. Clique em "Oferecer Carona"
2. Preencha origem, destino, data, hora e vagas
3. Clique em "Oferecer Carona"

**Buscar e reservar**
1. Na página inicial, preencha origem e destino
2. Clique em "Buscar Caronas"
3. Selecione uma carona → "Ver Detalhes" → "Reservar"

**Gerenciar reservas**
1. Clique em "Minhas Reservas"
2. Visualize ou cancele suas solicitações de passageiro

**Gestão de Passageiros (Motorista)**
1. Clique em "Minhas Caronas"
2. Veja a lista de passageiros pendentes para cada carona oferecida
3. Clique em "Aceitar" ou "Recusar" para gerenciar as vagas

---

## Segurança

- Senhas criptografadas com bcrypt
- Prepared statements para prevenção de SQL injection
- Validação de entrada em todos os formulários
- Sessões seguras para autenticação

---

## Autores

| Nome | Instituição |
|------|-------------|
| Eduardo França | IFRS — Ibirubá |
| Samuel Tomassoni | IFRS — Ibirubá |

---

*Projeto desenvolvido para a disciplina de LP IV — IFRS Ibirubá*
