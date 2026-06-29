<?php
/**
 * Front Controller - Ponto de Entrada Único da Aplicação
 */

// Carregar configurações
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Router.php';

// Garantir que a URL padrão seja a raiz se nada for passado
if (!isset($_GET['url'])) {
    $_GET['url'] = '/';
}

// Inicializar e processar requisição
$router = new Router();
$router->processar();
?>
