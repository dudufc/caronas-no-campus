<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/controllers/UserController.php';
require_once __DIR__ . '/../app/controllers/ReservaController.php';

$userController = new UserController();
$reservaController = new ReservaController();

if (!$userController->estaAutenticado()) {
    header('Location: ' . BASE_URL . 'login.php');
    exit;
}

$id = $_GET['id'] ?? null;

if ($id) {
    $resultado = $reservaController->cancelar($id);
    
    if ($resultado['sucesso']) {
        $_SESSION['mensagem'] = $resultado['mensagem'];
        $_SESSION['tipo_mensagem'] = 'success';
    } else {
        $_SESSION['mensagem'] = $resultado['mensagem'];
        $_SESSION['tipo_mensagem'] = 'danger';
    }
}

header('Location: ' . BASE_URL . 'minhas-reservas.php');
exit;
?>
