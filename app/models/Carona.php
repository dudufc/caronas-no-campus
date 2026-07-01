<?php

declare(strict_types=1);


class Reserva 
{
    private mysqli $conn;
    private string $table = 'reservas';
    
    public ?int $id = null;
    public ?int $carona_id = null;
    public ?int $usuario_id = null;
    public ?string $status = null;
    public ?string $data_reserva = null;
    
    public function __construct(mysqli $db) 
    {
        $this->conn = $db;
    }
    
 
    public function buscarPorId(int $id): ?array 
    {
        $query = "
            SELECT r.*, c.origem, c.destino, c.data_saida, c.hora_saida, u.nome AS passageiro 
            FROM {$this->table} r
            JOIN caronas c ON r.carona_id = c.id
            JOIN usuarios u ON r.usuario_id = u.id
            WHERE r.id = ?
        ";
        
        $stmt = $this->prepararQuery($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $resultado = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        return $resultado ?: null;
    }
    
  
    public function listarPorUsuario(int $usuario_id): array 
    {
        $query = "
            SELECT r.*, c.origem, c.destino, c.data_saida, c.hora_saida, u.nome AS motorista 
            FROM {$this->table} r
            JOIN caronas c ON r.carona_id = c.id
            JOIN usuarios u ON c.usuario_id = u.id
            WHERE r.usuario_id = ?
            ORDER BY c.data_saida DESC
        ";
        
        $stmt = $this->prepararQuery($query);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        return $resultado;
    }
    
   
    public function listarPorCarona(int $carona_id): array 
    {
        $query = "
            SELECT r.*, u.nome AS passageiro, u.telefone 
            FROM {$this->table} r
            JOIN usuarios u ON r.usuario_id = u.id
            WHERE r.carona_id = ?
            ORDER BY r.data_reserva DESC
        ";
        
        $stmt = $this->prepararQuery($query);
        $stmt->bind_param("i", $carona_id);
        $stmt->execute();
        
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        return $resultado;
    }
    

    public function criar(): bool 
    {
        $query = "
            INSERT INTO {$this->table} (carona_id, usuario_id, status, data_reserva) 
            VALUES (?, ?, 'pendente', NOW())
        ";
        
        $stmt = $this->prepararQuery($query);
        $stmt->bind_param("ii", $this->carona_id, $this->usuario_id);
        
        $sucesso = $stmt->execute();
        $stmt->close();
        
        return $sucesso;
    }
    

    public function atualizarStatus(int $id, string $status): bool 
    {
        $query = "UPDATE {$this->table} SET status = ? WHERE id = ?";
        
        $stmt = $this->prepararQuery($query);
        $stmt->bind_param("si", $status, $id);
        
        $sucesso = $stmt->execute();
        $stmt->close();
        
        return $sucesso;
    }
    

    public function cancelar(int $id): bool 
    {
        $query = "DELETE FROM {$this->table} WHERE id = ?";
        
        $stmt = $this->prepararQuery($query);
        $stmt->bind_param("i", $id);
        
        $sucesso = $stmt->execute();
        $stmt->close();
        
        return $sucesso;
    }
    

    public function verificarReservaExistente(int $carona_id, int $usuario_id): bool 
    {
        $query = "SELECT id FROM {$this->table} WHERE carona_id = ? AND usuario_id = ?";
        
        $stmt = $this->prepararQuery($query);
        $stmt->bind_param("ii", $carona_id, $usuario_id);
        $stmt->execute();
        
        $resultado = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        return !empty($resultado);
    }

  
    private function prepararQuery(string $query): mysqli_stmt 
    {
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            throw new RuntimeException("Erro ao preparar a query SQL: " . $this->conn->error);
        }
        return $stmt;
    }
}
