<?php
/**
 * Modelo de Usuário
 * Responsável por operações relacionadas a usuários no banco de dados
 */

class User {
    private $conn;
    private $table = 'usuarios';
    
    public $id;
    public $nome;
    public $email;
    public $telefone;
    public $senha;
    public $data_criacao;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Buscar usuário por ID
     */
    public function buscarPorId($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    /**
     * Buscar usuário por email
     */
    public function buscarPorEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    /**
     * Criar novo usuário
     */
    public function criar() {
        $query = "INSERT INTO " . $this->table . " 
                  (nome, email, telefone, senha, data_criacao) 
                  VALUES (?, ?, ?, ?, NOW())";
        
        $stmt = $this->conn->prepare($query);
        
        // Hash da senha
        $senha_hash = password_hash($this->senha, PASSWORD_BCRYPT);
        
        $stmt->bind_param("ssss", $this->nome, $this->email, $this->telefone, $senha_hash);
        
        return $stmt->execute();
    }
    
    /**
     * Listar todos os usuários
     */
    public function listar() {
        $query = "SELECT id, nome, email, telefone, data_criacao FROM " . $this->table . " ORDER BY data_criacao DESC";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Atualizar usuário
     */
    public function atualizar() {
        $query = "UPDATE " . $this->table . " 
                  SET nome = ?, email = ?, telefone = ? 
                  WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssi", $this->nome, $this->email, $this->telefone, $this->id);
        
        return $stmt->execute();
    }
    
    /**
     * Deletar usuário
     */
    public function deletar($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
