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
     * Listar minhas reservas
     */
    public function listarMinhas() {
        $usuarioId = $_SESSION['usuario_id'];
        $reservas = $this->reservaModel->listarPorUsuario($usuarioId);
        
        $titulo = APP_NAME . ' - Minhas Reservas';
        $view = __DIR__ . '/../views/reservas/minhas-reservas.php';
        
        require __DIR__ . '/../views/layout.php';
    }
    
    /**
     * Cancelar reserva (com validação de propriedade)
     */
    public function cancelar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'minhas-reservas');
            exit;
        }
        
        $reservaId = $_POST['id'] ?? null;
        $usuarioId = $_SESSION['usuario_id'];
        
        if (!$reservaId) {
            $_SESSION['mensagem'] = 'ID da reserva inválido';
            $_SESSION['tipo_mensagem'] = 'danger';
            header('Location: ' . BASE_URL . 'minhas-reservas');
            exit;
        }
        
        // Obter detalhes da reserva
        $reserva = $this->reservaModel->buscarPorId($reservaId);
        
        if (!$reserva) {
            $_SESSION['mensagem'] = 'Reserva não encontrada';
            $_SESSION['tipo_mensagem'] = 'danger';
            header('Location: ' . BASE_URL . 'minhas-reservas');
            exit;
        }
        
        // VALIDAÇÃO CRÍTICA: Verificar se a reserva pertence ao usuário logado
        if ($reserva['usuario_id'] != $usuarioId) {
            $_SESSION['mensagem'] = 'Você não tem permissão para cancelar esta reserva';
            $_SESSION['tipo_mensagem'] = 'danger';
            header('Location: ' . BASE_URL . 'minhas-reservas');
            exit;
        }
        
        // Cancelar reserva
        if ($this->reservaModel->cancelar($reservaId)) {
            // Aumentar vagas disponíveis
            $query = "UPDATE caronas SET vagas_disponiveis = vagas_disponiveis + 1 WHERE id = ?";
            global $conn;
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $reserva['carona_id']);
            $stmt->execute();
            
            $_SESSION['mensagem'] = 'Reserva cancelada com sucesso';
            $_SESSION['tipo_mensagem'] = 'success';
        } else {
            $_SESSION['mensagem'] = 'Erro ao cancelar reserva';
            $_SESSION['tipo_mensagem'] = 'danger';
        }
        
        header('Location: ' . BASE_URL . 'minhas-reservas');
        exit;
    }
    
    /**
     * Criar nova reserva
     */
    public function criar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        $caronaId = $_POST['carona_id'] ?? null;
        $usuarioId = $_SESSION['usuario_id'];
        
        if (!$caronaId) {
            $_SESSION['mensagem'] = 'ID da carona inválido';
            $_SESSION['tipo_mensagem'] = 'danger';
            header('Location: ' . BASE_URL);
            exit;
        }
        
        // Verificar se carona existe e tem vagas
        $carona = $this->caronaModel->buscarPorId($caronaId);
        if (!$carona) {
            $_SESSION['mensagem'] = 'Carona não encontrada';
            $_SESSION['tipo_mensagem'] = 'danger';
            header('Location: ' . BASE_URL);
            exit;
        }
        
        if ($carona['vagas_disponiveis'] <= 0) {
            $_SESSION['mensagem'] = 'Não há vagas disponíveis';
            $_SESSION['tipo_mensagem'] = 'danger';
            header('Location: ' . BASE_URL . 'detalhes-carona?id=' . $caronaId);
            exit;
        }
        
        // Verificar se usuário já tem reserva para esta carona
        if ($this->reservaModel->verificarReservaExistente($caronaId, $usuarioId)) {
            $_SESSION['mensagem'] = 'Você já tem uma reserva para esta carona';
            $_SESSION['tipo_mensagem'] = 'danger';
            header('Location: ' . BASE_URL . 'detalhes-carona?id=' . $caronaId);
            exit;
        }
        
        // Criar reserva
        $this->reservaModel->carona_id = $caronaId;
        $this->reservaModel->usuario_id = $usuarioId;
        
        if ($this->reservaModel->criar()) {
            // Reduzir vagas disponíveis
            $this->caronaModel->reduzirVagas($caronaId, 1);
            
            $_SESSION['mensagem'] = 'Reserva realizada com sucesso!';
            $_SESSION['tipo_mensagem'] = 'success';
            header('Location: ' . BASE_URL . 'minhas-reservas');
        } else {
            $_SESSION['mensagem'] = 'Erro ao criar reserva';
            $_SESSION['tipo_mensagem'] = 'danger';
            header('Location: ' . BASE_URL . 'detalhes-carona?id=' . $caronaId);
        }
        exit;
    }
}
?>
