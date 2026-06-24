<?php
/**
 * Controlador de Usuários
 * Responsável por gerenciar ações relacionadas a usuários
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/User.php';

class UserController {
    private $userModel;
    
    public function __construct() {
        global $conn;
        $this->userModel = new User($conn);
    }
    
    /**
     * Registrar novo usuário
     */
    public function registrar($nome, $email, $telefone, $senha, $confirmaSenha) {
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
    public function login($email, $senha) {
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
     * Fazer logout
     */
    public function logout() {
        session_destroy();
        return ['sucesso' => true, 'mensagem' => 'Logout realizado com sucesso'];
    }
    
    /**
     * Obter dados do usuário autenticado
     */
    public function obterUsuarioAutenticado() {
        if (!isset($_SESSION['usuario_id'])) {
            return null;
        }
        
        return $this->userModel->buscarPorId($_SESSION['usuario_id']);
    }
    
    /**
     * Verificar se usuário está autenticado
     */
    public function estaAutenticado() {
        return isset($_SESSION['usuario_id']);
    }
}
?>
