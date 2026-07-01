<?php
/**
 * Modelo de Carona
 * Responsável por operações relacionadas a caronas no banco de dados
 */

class Carona {
    private $conn;
    private $table = 'caronas';
    
    public $id;
    public $usuario_id;
    public $origem;
    public $destino;
    public $data_saida;
    public $hora_saida;
    public $vagas_disponiveis;
    public $descricao;
    public $data_criacao;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Buscar carona por ID
     */
    public function buscarPorId($id) {
        $query = "SELECT c.*, u.nome as motorista, u.telefone FROM " . $this->table . " c
                  JOIN usuarios u ON c.usuario_id = u.id
                  WHERE c.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    /**
     * Listar todas as caronas disponíveis
     */
    public function listar() {
        // Listar caronas futuras (incluindo hoje) que tenham vagas ou pertençam ao usuário logado
        // Para simplificar e garantir que todos vejam, vamos listar todas as caronas futuras
        $hoje = date('Y-m-d');
        $query = "SELECT c.*, u.nome as motorista, u.telefone FROM " . $this->table . " c
                  JOIN usuarios u ON c.usuario_id = u.id
                  WHERE c.data_saida >= ?
                  ORDER BY c.data_saida ASC, c.hora_saida ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $hoje);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Buscar caronas por origem e destino
     */
    public function buscar($origem, $destino) {
        $query = "SELECT c.*, u.nome as motorista, u.telefone FROM " . $this->table . " c
                  JOIN usuarios u ON c.usuario_id = u.id
                  WHERE (c.origem LIKE ? OR c.destino LIKE ?)
                  AND c.vagas_disponiveis > 0
                  ORDER BY c.data_saida DESC";
        
        $param = "%" . $origem . "%";
        $param2 = "%" . $destino . "%";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $param, $param2);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Criar nova carona
     */
    public function criar() {
        $query = "INSERT INTO " . $this->table . " 
                  (usuario_id, origem, destino, data_saida, hora_saida, vagas_disponiveis, descricao, data_criacao) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issssss", 
            $this->usuario_id, 
            $this->origem, 
            $this->destino, 
            $this->data_saida,
            $this->hora_saida,
            $this->vagas_disponiveis,
            $this->descricao
        );
        
        return $stmt->execute();
    }
    
    /**
     * Atualizar carona
     */
    public function atualizar() {
        $query = "UPDATE " . $this->table . " 
                  SET origem = ?, destino = ?, data_saida = ?, hora_saida = ?, 
                      vagas_disponiveis = ?, descricao = ? 
                  WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssssi", 
            $this->origem, 
            $this->destino, 
            $this->data_saida,
            $this->hora_saida,
            $this->vagas_disponiveis,
            $this->descricao,
            $this->id
        );
        
        return $stmt->execute();
    }
    
    /**
     * Deletar carona
     */
    public function deletar($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    /**
     * Reduzir vagas disponíveis
     */
    public function reduzirVagas($id, $quantidade = 1) {
        $query = "UPDATE " . $this->table . " 
                  SET vagas_disponiveis = vagas_disponiveis - ? 
                  WHERE id = ? AND vagas_disponiveis > 0";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $quantidade, $id);
        return $stmt->execute();
    }
    
    /**
     * Listar caronas de um usuário (motorista)
     */
    public function listarPorUsuario($usuario_id) {
        $query = "SELECT c.* FROM " . $this->table . " c
                  WHERE c.usuario_id = ?
                  ORDER BY c.data_saida DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
