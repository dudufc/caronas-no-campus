<?php
/**
 * Configuração Geral da Aplicação
 */

// Definir fuso horário
date_default_timezone_set('America/Sao_Paulo');

// Definir constantes da aplicação
define('APP_NAME', 'Caronas no Campus');
define('APP_VERSION', '1.0.0');
define('BASE_URL', 'http://localhost/caronas-no-campus/public/');

// Configurações de segurança
define('SESSION_TIMEOUT', 3600); // 1 hora em segundos

// Iniciar sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Carregar conexão com o banco de dados
if (file_exists(__DIR__ . '/database.php')) {
    require_once __DIR__ . '/database.php';
}
?>
