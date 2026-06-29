<?php
/**
 * Configuração Geral da Aplicação
 */

// Definir fuso horário
date_default_timezone_set('America/Sao_Paulo');

// Definir constantes da aplicação
define('APP_NAME', 'Caronas no Campus');
define('APP_VERSION', '1.0.0');

// Detectar a URL base dinamicamente para evitar problemas no Laragon
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('BASE_URL', $protocol . "://" . $host . "/caronas-no-campus/public/index.php?url=");
define('ASSETS_URL', $protocol . "://" . $host . "/caronas-no-campus/public/");

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
