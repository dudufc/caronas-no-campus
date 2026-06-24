<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/controllers/UserController.php';

$userController = new UserController();
$userController->logout();

header('Location: ' . BASE_URL . 'index.php');
exit;
?>
