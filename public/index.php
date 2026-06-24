<?php
/**
 * Front Controller - Ponto de Entrada Único da Aplicação
 */

// Carregar configurações
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Router.php';

// Inicializar e processar requisição
$router = new Router();
$router->processar();
?>
