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
     * Aceitar uma reserva pendente (apenas o motorista pode fazer isso)
     */
    public function aceitar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'minhas-caronas');
            exit;
        }
        
        $reservaId = $_POST['id'] ?? null;
        $usuarioId = $_SESSION['usuario_id'];
        
        if (!$reservaId) {
            $_SESSION['mensagem'] = 'ID da reserva inválido';
            $_SESSION['tipo_mensagem'] = 'danger';
            header('Location: ' . BASE_URL . 'minhas-caronas');
            exit;
        }
        
        // Obter detalhes da reserva
        $reserva = $this->reservaModel->buscarPorId($reservaId);
        
        if (!$reserva) {
            $_SESSION['mensagem'] = 'Reserva não encontrada';
            $_SESSION['tipo_mensagem'] = 'danger';
            header('Location: ' . BASE_URL . 'minhas-caronas');
            exit;
        }
        
        // Verificar se a carona pertence ao usuário logado
        $carona = $this->caronaModel->buscarPorId($reserva['carona_id']);
        if ($carona['usuario_id'] != $usuarioId) {
            $_SESSION['mensagem'] = 'Você não tem permissão para aceitar esta reserva';
            $_SESSION['tipo_mensagem'] = 'danger';
            header('Location: ' . BASE_URL . 'minhas-caronas');
            exit;
        }
        
        // Aceitar reserva
        if ($this->reservaModel->atualizarStatus($reservaId, 'confirmada')) {
            $_SESSION['mensagem'] = 'Reserva aceita com sucesso!';
            $_SESSION['tipo_mensagem'] = 'success';
        } else {
            $_SESSION['mensagem'] = 'Erro ao aceitar reserva';
            $_SESSION['tipo_mensagem'] = 'danger';
        }
        
        header('Location: ' . BASE_URL . 'minhas-caronas');
        exit;
    }
    
    /**
     * Recusar uma reserva pendente (apenas o motorista pode fazer isso)
     */
    public function recusar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'minhas-caronas');
            exit;
        }
        
        $reservaId = $_POST['id'] ?? null;
        $usuarioId = $_SESSION['usuario_id'];
        
        if (!$reservaId) {
            $_SESSION['mensagem'] = 'ID da reserva inválido';
            $_SESSION['tipo_mensagem'] = 'danger';
            header('Location: ' . BASE_URL . 'minhas-caronas');
            exit;
        }
        
        // Obter detalhes da reserva
        $reserva = $this->reservaModel->buscarPorId($reservaId);
        
        if (!$reserva) {
            $_SESSION['mensagem'] = 'Reserva não encontrada';
            $_SESSION['tipo_mensagem'] = 'danger';
            header('Location: ' . BASE_URL . 'minhas-caronas');
            exit;
        }
        
        // Verificar se a carona pertence ao usuário logado
        $carona = $this->caronaModel->buscarPorId($reserva['carona_id']);
        if ($carona['usuario_id'] != $usuarioId) {
            $_SESSION['mensagem'] = 'Você não tem permissão para recusar esta reserva';
            $_SESSION['tipo_mensagem'] = 'danger';
            header('Location: ' . BASE_URL . 'minhas-caronas');
            exit;
        }
        
        // Recusar reserva (deletar)
        if ($this->reservaModel->cancelar($reservaId)) {
            // Aumentar vagas disponíveis
            $query = "UPDATE caronas SET vagas_disponiveis = vagas_disponiveis + 1 WHERE id = ?";
            global $conn;
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $reserva['carona_id']);
            $stmt->execute();
            
            $_SESSION['mensagem'] = 'Reserva recusada com sucesso';
            $_SESSION['tipo_mensagem'] = 'success';
        } else {
            $_SESSION['mensagem'] = 'Erro ao recusar reserva';
            $_SESSION['tipo_mensagem'] = 'danger';
        }
        
        header('Location: ' . BASE_URL . 'minhas-caronas');
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

        // Impedir que o motorista reserve sua própria carona
        if ($carona['usuario_id'] == $usuarioId) {
            $_SESSION['mensagem'] = 'Você não pode reservar sua própria carona';
            $_SESSION['tipo_mensagem'] = 'danger';
            header('Location: ' . BASE_URL . 'detalhes-carona?id=' . $caronaId);
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
