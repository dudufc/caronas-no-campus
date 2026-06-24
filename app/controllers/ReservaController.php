<?php
/**
 * Controlador de Reservas
 * Responsável por gerenciar ações relacionadas a reservas de caronas
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Carona.php';

class ReservaController {
    private $reservaModel;
    private $caronaModel;
    
    public function __construct() {
        global $conn;
        $this->reservaModel = new Reserva($conn);
        $this->caronaModel = new Carona($conn);
    }
    
    /**
     * Listar reservas de um usuário
     */
    public function listarPorUsuario($usuario_id) {
        if (empty($usuario_id)) {
            return [];
        }
        
        return $this->reservaModel->listarPorUsuario($usuario_id);
    }
    
    /**
     * Listar reservas de uma carona
     */
    public function listarPorCarona($carona_id) {
        if (empty($carona_id)) {
            return [];
        }
        
        return $this->reservaModel->listarPorCarona($carona_id);
    }
    
    /**
     * Criar nova reserva
     */
    public function criar($carona_id, $usuario_id) {
        // Validações
        if (empty($carona_id) || empty($usuario_id)) {
            return ['sucesso' => false, 'mensagem' => 'Dados inválidos'];
        }
        
        // Verificar se carona existe e tem vagas
        $carona = $this->caronaModel->buscarPorId($carona_id);
        if (!$carona) {
            return ['sucesso' => false, 'mensagem' => 'Carona não encontrada'];
        }
        
        if ($carona['vagas_disponiveis'] <= 0) {
            return ['sucesso' => false, 'mensagem' => 'Não há vagas disponíveis'];
        }
        
        // Verificar se usuário já tem reserva para esta carona
        if ($this->reservaModel->verificarReservaExistente($carona_id, $usuario_id)) {
            return ['sucesso' => false, 'mensagem' => 'Você já tem uma reserva para esta carona'];
        }
        
        // Criar reserva
        $this->reservaModel->carona_id = $carona_id;
        $this->reservaModel->usuario_id = $usuario_id;
        
        if ($this->reservaModel->criar()) {
            // Reduzir vagas disponíveis
            $this->caronaModel->reduzirVagas($carona_id, 1);
            
            return ['sucesso' => true, 'mensagem' => 'Reserva realizada com sucesso'];
        } else {
            return ['sucesso' => false, 'mensagem' => 'Erro ao criar reserva'];
        }
    }
    
    /**
     * Cancelar reserva
     */
    public function cancelar($id) {
        if (empty($id)) {
            return ['sucesso' => false, 'mensagem' => 'ID da reserva inválido'];
        }
        
        // Obter detalhes da reserva
        $reserva = $this->reservaModel->buscarPorId($id);
        if (!$reserva) {
            return ['sucesso' => false, 'mensagem' => 'Reserva não encontrada'];
        }
        
        if ($this->reservaModel->cancelar($id)) {
            // Aumentar vagas disponíveis
            $query = "UPDATE caronas SET vagas_disponiveis = vagas_disponiveis + 1 WHERE id = ?";
            global $conn;
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $reserva['carona_id']);
            $stmt->execute();
            
            return ['sucesso' => true, 'mensagem' => 'Reserva cancelada com sucesso'];
        } else {
            return ['sucesso' => false, 'mensagem' => 'Erro ao cancelar reserva'];
        }
    }
    
    /**
     * Confirmar reserva
     */
    public function confirmar($id) {
        if (empty($id)) {
            return ['sucesso' => false, 'mensagem' => 'ID da reserva inválido'];
        }
        
        if ($this->reservaModel->atualizarStatus($id, 'confirmada')) {
            return ['sucesso' => true, 'mensagem' => 'Reserva confirmada com sucesso'];
        } else {
            return ['sucesso' => false, 'mensagem' => 'Erro ao confirmar reserva'];
        }
    }
}
?>
