<?php

date_default_timezone_set('America/Sao_Paulo');

define('APP_NAME', 'Caronas no Campus');
define('APP_VERSION', '1.0.0');
define('SESSION_TIMEOUT', 3600);


$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$basePath = "{$protocol}://{$host}/caronas-no-campus/public";

define('BASE_URL', "{$basePath}/index.php?url=");
define('ASSETS_URL', "{$basePath}/");

unset($protocol, $host, $basePath); 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$databasePath = __DIR__ . '/database.php';
if (file_exists($databasePath)) {
    require_once $databasePath;
}

?>
