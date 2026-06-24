# Guia de Instalação - Caronas no Campus

## Pré-requisitos

Antes de começar, certifique-se de ter os seguintes componentes instalados:

- **PHP 7.4 ou superior**
- **MySQL 5.7 ou superior**
- **Servidor Web** (Apache, Nginx, etc.)
- **Git** para controle de versão

## Passo 1: Clonar o Repositório

```bash
git clone https://github.com/dudufc/caronas-no-campus.git
cd caronas-no-campus
```

## Passo 2: Criar o Banco de Dados

Abra o terminal e acesse o MySQL:

```bash
mysql -u root -p
```

Depois execute o script SQL:

```bash
source database/schema.sql;
```

Ou, se preferir fazer tudo em uma linha:

```bash
mysql -u root -p < database/schema.sql
```

## Passo 3: Configurar as Credenciais do Banco de Dados

Edite o arquivo `config/database.php`:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'seu_usuario');
define('DB_PASSWORD', 'sua_senha');
define('DB_NAME', 'caronas_campus');
```

Substitua `seu_usuario` e `sua_senha` pelas suas credenciais do MySQL.

## Passo 4: Configurar Permissões de Diretórios (Linux/Mac)

```bash
chmod -R 755 app/
chmod -R 755 public/
chmod -R 755 config/
```

## Passo 5: Iniciar o Servidor PHP

### Opção 1: Usar o servidor built-in do PHP

```bash
php -S localhost:8000 -t public/
```

### Opção 2: Usar Apache

Configure um Virtual Host apontando para o diretório `public/`.

### Opção 3: Usar Nginx

Configure um bloco de servidor apontando para o diretório `public/`.

## Passo 6: Acessar a Aplicação

Abra seu navegador e acesse:

```
http://localhost:8000
```

## Dados de Teste

O script SQL já inclui dados de exemplo:

**Usuários:**
- Email: `joao@example.com` | Senha: `password`
- Email: `maria@example.com` | Senha: `password`
- Email: `pedro@example.com` | Senha: `password`

**Caronas e Reservas:**
- 3 caronas de exemplo já criadas
- 2 reservas de exemplo já realizadas

## Solução de Problemas

### Erro de Conexão com Banco de Dados

- Verifique se o MySQL está rodando
- Confirme as credenciais em `config/database.php`
- Verifique se o banco de dados `caronas_campus` foi criado

### Erro 404 ao acessar páginas

- Certifique-se de que o servidor está rodando na porta correta
- Verifique se o arquivo `public/index.php` existe

### Erro de Permissão

- No Linux/Mac, execute: `chmod -R 755 .`
- No Windows, verifique as permissões da pasta

## Estrutura de Diretórios

```
caronas-no-campus/
├── app/                          # Código da aplicação
│   ├── controllers/              # Controladores (lógica)
│   ├── models/                   # Modelos (banco de dados)
│   └── views/                    # Views (interface)
├── config/                       # Configurações
├── database/                     # Scripts SQL
├── public/                       # Arquivos públicos
│   ├── index.php                # Ponto de entrada
│   ├── css/                     # Estilos
│   ├── js/                      # Scripts JavaScript
│   └── images/                  # Imagens
├── README.md                     # Documentação principal
├── INSTALACAO.md                 # Este arquivo
└── .gitignore                    # Arquivos ignorados pelo Git
```

## Próximos Passos

1. Registre um novo usuário
2. Faça login com sua conta
3. Explore as funcionalidades:
   - Ofereça uma carona
   - Busque caronas disponíveis
   - Faça uma reserva
   - Gerencie suas reservas

## Suporte

Para dúvidas ou problemas, consulte a documentação no `README.md` ou entre em contato com o desenvolvedor.

---

**Última atualização**: 24 de junho de 2026
