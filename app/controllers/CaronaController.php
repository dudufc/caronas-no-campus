<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Carona.php';
require_once __DIR__ . '/../models/User.php';

require_once __DIR__ . '/../models/Reserva.php';

class CaronaController {
    private Carona $caronaModel;
    private User $userModel;
    private Reserva $reservaModel;
    
    public function __construct() {
        global $conn;
        $this->caronaModel = new Carona($conn);
        $this->userModel = new User($conn);
        $this->reservaModel = new Reserva($conn);
    }
    
    
    public function listarPrincipal(): void {
        $caronas = $this->caronaModel->listar();
        $usuarioAutenticado = isset($_SESSION['usuario_id']);
        
        $titulo = APP_NAME . ' - Encontre Caronas';
        $view = __DIR__ . '/../views/caronas/index.php';
        
        require __DIR__ . '/../views/layout.php';
    }
    
   
    public function buscar(): void {
        // Sanitização básica de entrada
        $origem = isset($_GET['origem']) ? htmlspecialchars(trim($_GET['origem'])) : '';
        $destino = isset($_GET['destino']) ? htmlspecialchars(trim($_GET['destino'])) : '';
        
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
    
  /
    public function detalhes(): void {
        $caronaId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
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
    
  
    public function mostrarFormulario(): void {
        $titulo = APP_NAME . ' - Oferecer Carona';
        $view = __DIR__ . '/../views/caronas/oferecer.php';
        
        require __DIR__ . '/../views/layout.php';
    }
    
 
    public function criar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'oferecer-carona');
            exit;
        }
        
        $origem = isset($_POST['origem']) ? htmlspecialchars(trim($_POST['origem'])) : '';
        $destino = isset($_POST['destino']) ? htmlspecialchars(trim($_POST['destino'])) : '';
        $data_saida = $_POST['data_saida'] ?? '';
        $hora_saida = $_POST['hora_saida'] ?? '';
        $vagas = filter_input(INPUT_POST, 'vagas', FILTER_VALIDATE_INT) ?: 1;
        $descricao = isset($_POST['descricao']) ? htmlspecialchars(trim($_POST['descricao'])) : '';
        
        // Validações de campos vazios
        if (empty($origem) || empty($destino) || empty($data_saida) || empty($hora_saida)) {
            $_SESSION['mensagem'] = 'Todos os campos obrigatórios devem ser preenchidos';
            $_SESSION['tipo_mensagem'] = 'danger';
            header('Location: ' . BASE_URL . 'oferecer-carona');
            exit;
        }
        
        // Validar formato e data retroativa com prevenção de erro fatal
        $dataAtual = new DateTime();
        $dataAtual->setTime(0, 0, 0); // Zera a hora para comparar apenas o dia
        $dataSaida = DateTime::createFromFormat('Y-m-d', $data_saida);
        
        if (!$dataSaida) {
            $_SESSION['mensagem'] = 'Formato de data inválido';
            $_SESSION['tipo_mensagem'] = 'danger';
            header('Location: ' . BASE_URL . 'oferecer-carona');
            exit;
        }
        
        $dataSaida->setTime(0, 0, 0);
        
        // Permitir a data de hoje e datas futuras
        $dataAtualMenos1 = clone $dataAtual;
        $dataAtualMenos1->modify('-1 day');
        
        if ($dataSaida <= $dataAtualMenos1) {
            $_SESSION['mensagem'] = 'A data não pode ser no passado';
            $_SESSION['tipo_mensagem'] = 'danger';
            header('Location: ' . BASE_URL . 'oferecer-carona');
            exit;
        }
        
        if ($vagas < 1 || $vagas > 10) {
            $_SESSION['mensagem'] = 'O número de vagas deve estar entre 1 e 10';
            $_SESSION['tipo_mensagem'] = 'danger';
            header('Location: ' . BASE_URL . 'oferecer-carona');
            exit;
        }
        
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
    
   
    public function listarMinhas(): void {
        $usuarioId = $_SESSION['usuario_id'];
        $caronas = $this->caronaModel->listarPorUsuario($usuarioId);
        
        $titulo = APP_NAME . ' - Minhas Caronas';
        $view = __DIR__ . '/../views/caronas/minhas-caronas.php';
        
        require __DIR__ . '/../views/layout.php';
    }
}
