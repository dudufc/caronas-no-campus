<?php
/**
 * Controlador de Usuários
 * Responsável por gerenciar ações relacionadas a usuários
 */

require_once __DIR__ . '/../models/User.php';

class UserController {
    private $userModel;
    
    public function __construct() {
        global $conn;
        
        // Garantir que a conexão exista antes de instanciar os modelos
        if (!isset($conn)) {
            $path = __DIR__ . '/../../config/database.php';
            if (file_exists($path)) {
                require_once $path;
            } else if (file_exists(__DIR__ . '/../../config/database.php.example')) {
                require_once __DIR__ . '/../../config/database.php.example';
            }
        }
        
        $this->userModel = new User($conn);
    }
    
    /**
     * Mostrar página de login
     */
    public function mostrarLogin() {
        if (isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        $titulo = APP_NAME . ' - Login';
        $view = __DIR__ . '/../views/auth/login.php';
        
        require __DIR__ . '/../views/layout.php';
    }
    
    /**
     * Processar login
     */
    public function processarLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';
        
        $resultado = $this->login($email, $senha);
        
        if ($resultado['sucesso']) {
            $_SESSION['mensagem'] = $resultado['mensagem'];
            $_SESSION['tipo_mensagem'] = 'success';
            header('Location: ' . BASE_URL);
        } else {
            $_SESSION['mensagem'] = $resultado['mensagem'];
            $_SESSION['tipo_mensagem'] = 'danger';
            header('Location: ' . BASE_URL . 'login');
        }
        exit;
    }
    
    /**
     * Mostrar página de registro
     */
    public function mostrarRegistro() {
        if (isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        $titulo = APP_NAME . ' - Registrar';
        $view = __DIR__ . '/../views/auth/registro.php';
        
        require __DIR__ . '/../views/layout.php';
    }
    
    /**
     * Processar registro
     */
    public function processarRegistro() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'registro');
            exit;
        }
        
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $telefone = $_POST['telefone'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $confirmaSenha = $_POST['confirma_senha'] ?? '';
        
        $resultado = $this->registrar($nome, $email, $telefone, $senha, $confirmaSenha);
        
        if ($resultado['sucesso']) {
            $_SESSION['mensagem'] = 'Registrado com sucesso! Faça login para continuar.';
            $_SESSION['tipo_mensagem'] = 'success';
            header('Location: ' . BASE_URL . 'login');
        } else {
            $_SESSION['mensagem'] = $resultado['mensagem'];
            $_SESSION['tipo_mensagem'] = 'danger';
            header('Location: ' . BASE_URL . 'registro');
        }
        exit;
    }
    
    /**
     * Logout
     */
    public function logout() {
        session_destroy();
        header('Location: ' . BASE_URL);
        exit;
    }
    
    /**
     * Mostrar perfil do usuário
     */
    public function mostrarPerfil() {
        $usuario = $this->obterUsuarioAutenticado();
        
        if (!$usuario) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        
        $titulo = APP_NAME . ' - Meu Perfil';
        $view = __DIR__ . '/../views/auth/perfil.php';
        
        require __DIR__ . '/../views/layout.php';
    }
    
    /**
     * Registrar novo usuário
     */
    private function registrar($nome, $email, $telefone, $senha, $confirmaSenha) {
        // Validações
        if (empty($nome) || empty($email) || empty($telefone) || empty($senha)) {
            return ['sucesso' => false, 'mensagem' => 'Todos os campos são obrigatórios'];
        }
        
        if ($senha !== $confirmaSenha) {
            return ['sucesso' => false, 'mensagem' => 'As senhas não correspondem'];
        }
        
        if (strlen($senha) < 6) {
            return ['sucesso' => false, 'mensagem' => 'A senha deve ter no mínimo 6 caracteres'];
        }
        
        // Verificar se email já existe
        if ($this->userModel->buscarPorEmail($email)) {
            return ['sucesso' => false, 'mensagem' => 'Email já cadastrado'];
        }
        
        // Criar usuário
        $this->userModel->nome = $nome;
        $this->userModel->email = $email;
        $this->userModel->telefone = $telefone;
        $this->userModel->senha = $senha;
        
        if ($this->userModel->criar()) {
            return ['sucesso' => true, 'mensagem' => 'Usuário registrado com sucesso'];
        } else {
            return ['sucesso' => false, 'mensagem' => 'Erro ao registrar usuário'];
        }
    }
    
    /**
     * Fazer login
     */
    private function login($email, $senha) {
        if (empty($email) || empty($senha)) {
            return ['sucesso' => false, 'mensagem' => 'Email e senha são obrigatórios'];
        }
        
        $usuario = $this->userModel->buscarPorEmail($email);
        
        if (!$usuario) {
            return ['sucesso' => false, 'mensagem' => 'Email ou senha incorretos'];
        }
        
        if (!password_verify($senha, $usuario['senha'])) {
            return ['sucesso' => false, 'mensagem' => 'Email ou senha incorretos'];
        }
        
        // Criar sessão
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_email'] = $usuario['email'];
        
        return ['sucesso' => true, 'mensagem' => 'Login realizado com sucesso'];
    }
    
    /**
     * Obter dados do usuário autenticado
     */
    private function obterUsuarioAutenticado() {
        if (!isset($_SESSION['usuario_id'])) {
            return null;
        }
        
        return $this->userModel->buscarPorId($_SESSION['usuario_id']);
    }
}
?>
