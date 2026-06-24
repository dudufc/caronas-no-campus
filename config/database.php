<?php
/**
 * Configuração do Banco de Dados
 * Arquivo de configuração centralizado para conexão com MySQL
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'caronas_campus');

// Criar conexão
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    // Verificar conexão
    if ($conn->connect_error) {
        throw new Exception("Erro na conexão: " . $conn->connect_error);
    }
    
    // Definir charset para UTF-8
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>
