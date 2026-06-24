<?php
/**
 * Controlador de Caronas
 * Responsável por gerenciar ações relacionadas a caronas
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Carona.php';
require_once __DIR__ . '/../models/User.php';

class CaronaController {
    private $caronaModel;
    private $userModel;
    
    public function __construct() {
        global $conn;
        $this->caronaModel = new Carona($conn);
        $this->userModel = new User($conn);
    }
    
    /**
     * Listar caronas na página principal
     */
    public function listarPrincipal() {
        $caronas = $this->caronaModel->listar();
        $usuarioAutenticado = isset($_SESSION['usuario_id']);
        
        $titulo = APP_NAME . ' - Encontre Caronas';
        $view = __DIR__ . '/../views/caronas/index.php';
        
        require __DIR__ . '/../views/layout.php';
    }
    
    /**
     * Buscar caronas por origem/destino
     */
    public function buscar() {
        $origem = $_GET['origem'] ?? '';
        $destino = $_GET['destino'] ?? '';
        
        if (!empty($origem) || !empty($destino)) {
            $caronas = $this->caronaModel->buscar($origem, $destino);
        } else {
            $caronas = $this->caronaModel->listar();
        }
        
        $usuarioAutenticado = isset($_SESSION['usuario_id']);
        
        $titulo = APP_NAME . ' - Buscar Caronas';
        $view = __DIR__ . '/../views/caronas/buscar.php';
        
        require __DIR__ . '/../views/layout.php';
    }
    
    /**
     * Mostrar detalhes de uma carona
     */
    public function detalhes() {
        $caronaId = $_GET['id'] ?? null;
        
        if (!$caronaId) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        $carona = $this->caronaModel->buscarPorId($caronaId);
        
        if (!$carona) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        $usuarioAutenticado = isset($_SESSION['usuario_id']);
        
        $titulo = APP_NAME . ' - Detalhes da Carona';
        $view = __DIR__ . '/../views/caronas/detalhes.php';
        
        require __DIR__ . '/../views/layout.php';
    }
    
    /**
     * Mostrar formulário de oferta de carona
     */
    public function mostrarFormulario() {
        $titulo = APP_NAME . ' - Oferecer Carona';
        $view = __DIR__ . '/../views/caronas/oferecer.php';
        
        require __DIR__ . '/../views/layout.php';
    }
    
    /**
     * Criar nova carona
     */
    public function criar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'oferecer-carona');
            exit;
        }
        
        $origem = $_POST['origem'] ?? '';
        $destino = $_POST['destino'] ?? '';
        $data_saida = $_POST['data_saida'] ?? '';
        $hora_saida = $_POST['hora_saida'] ?? '';
        $vagas = $_POST['vagas'] ?? 1;
        $descricao = $_POST['descricao'] ?? '';
        
        // Validações
        if (empty($origem) || empty($destino) || empty($data_saida) || empty($hora_saida)) {
            $_SESSION['mensagem'] = 'Todos os campos obrigatórios devem ser preenchidos';
            $_SESSION['tipo_mensagem'] = 'danger';
            header('Location: ' . BASE_URL . 'oferecer-carona');
            exit;
        }
        
        // Validar data (não pode ser retroativa)
        $dataAtual = new DateTime();
        $dataSaida = DateTime::createFromFormat('Y-m-d', $data_saida);
        
        if ($dataSaida < $dataAtual) {
            $_SESSION['mensagem'] = 'A data não pode ser no passado';
            $_SESSION['tipo_mensagem'] = 'danger';
            header('Location: ' . BASE_URL . 'oferecer-carona');
            exit;
        }
        
        // Validar vagas
        if ($vagas < 1 || $vagas > 10) {
            $_SESSION['mensagem'] = 'O número de vagas deve estar entre 1 e 10';
            $_SESSION['tipo_mensagem'] = 'danger';
            header('Location: ' . BASE_URL . 'oferecer-carona');
            exit;
        }
        
        // Criar carona
        $this->caronaModel->origem = $origem;
        $this->caronaModel->destino = $destino;
        $this->caronaModel->data_saida = $data_saida;
        $this->caronaModel->hora_saida = $hora_saida;
        $this->caronaModel->vagas_disponiveis = $vagas;
        $this->caronaModel->descricao = $descricao;
        $this->caronaModel->usuario_id = $_SESSION['usuario_id'];
        
        if ($this->caronaModel->criar()) {
            $_SESSION['mensagem'] = 'Carona criada com sucesso!';
            $_SESSION['tipo_mensagem'] = 'success';
            header('Location: ' . BASE_URL);
        } else {
            $_SESSION['mensagem'] = 'Erro ao criar carona';
            $_SESSION['tipo_mensagem'] = 'danger';
            header('Location: ' . BASE_URL . 'oferecer-carona');
        }
        exit;
    }
    
    /**
     * Listar caronas do usuário
     */
    public function listarMinhas() {
        $usuarioId = $_SESSION['usuario_id'];
        $caronas = $this->caronaModel->listarPorUsuario($usuarioId);
        
        $titulo = APP_NAME . ' - Minhas Caronas';
        $view = __DIR__ . '/../views/caronas/minhas-caronas.php';
        
        require __DIR__ . '/../views/layout.php';
    }
}
?>
