<?php
/**
 * Roteador Central da Aplicação
 * Mapeia URLs para Controllers e Actions
 */

class Router {
    private $routes = [];
    
    public function __construct() {
        $this->defineRoutes();
    }
    
    /**
     * Definir todas as rotas da aplicação
     */
    private function defineRoutes() {
        // Rotas públicas
        $this->routes['GET']['/'] = ['controller' => 'CaronaController', 'action' => 'listarPrincipal'];
        $this->routes['GET']['/buscar'] = ['controller' => 'CaronaController', 'action' => 'buscar'];
        $this->routes['GET']['/detalhes-carona'] = ['controller' => 'CaronaController', 'action' => 'detalhes'];
        $this->routes['POST']['/login'] = ['controller' => 'UserController', 'action' => 'processarLogin'];
        $this->routes['GET']['/login'] = ['controller' => 'UserController', 'action' => 'mostrarLogin'];
        $this->routes['POST']['/registro'] = ['controller' => 'UserController', 'action' => 'processarRegistro'];
        $this->routes['GET']['/registro'] = ['controller' => 'UserController', 'action' => 'mostrarRegistro'];
        
        // Rotas autenticadas
        $this->routes['GET']['/oferecer-carona'] = ['controller' => 'CaronaController', 'action' => 'mostrarFormulario', 'auth' => true];
        $this->routes['POST']['/oferecer-carona'] = ['controller' => 'CaronaController', 'action' => 'criar', 'auth' => true];
        $this->routes['GET']['/minhas-reservas'] = ['controller' => 'ReservaController', 'action' => 'listarMinhas', 'auth' => true];
        $this->routes['POST']['/cancelar-reserva'] = ['controller' => 'ReservaController', 'action' => 'cancelar', 'auth' => true];
        $this->routes['POST']['/criar-reserva'] = ['controller' => 'ReservaController', 'action' => 'criar', 'auth' => true];
        $this->routes['GET']['/logout'] = ['controller' => 'UserController', 'action' => 'logout', 'auth' => true];
        $this->routes['GET']['/perfil'] = ['controller' => 'UserController', 'action' => 'mostrarPerfil', 'auth' => true];
    }
    
    /**
     * Processar a requisição atual
     */
    public function processar() {
        $metodo = $_SERVER['REQUEST_METHOD'];
        
        // Suporte para rotas via parâmetro 'url' ou via PATH_INFO
        $uri = $_GET['url'] ?? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = str_replace('/caronas-no-campus/public', '', $uri);
        
        // Limpar a URI para garantir que comece com / e não termine com /
        $uri = '/' . trim($uri, '/');
        
        if ($uri === '//') $uri = '/';
        
        // Buscar rota correspondente
        if (isset($this->routes[$metodo][$uri])) {
            $rota = $this->routes[$metodo][$uri];
            
            // Verificar autenticação
            if (isset($rota['auth']) && $rota['auth']) {
                if (!isset($_SESSION['usuario_id'])) {
                    header('Location: ' . BASE_URL . 'login');
                    exit;
                }
            }
            
            $this->executarRota($rota);
        } else {
            // Se não encontrar, tenta a rota raiz ou 404
            if ($uri === '/') {
                $this->executarRota($this->routes['GET']['/']);
            } else {
                http_response_code(404);
                echo "Página não encontrada: " . htmlspecialchars($uri);
                exit;
            }
        }
    }
    
    /**
     * Executar a rota encontrada
     */
    private function executarRota($rota) {
        $controller = $rota['controller'];
        $action = $rota['action'];
        
        $caminhoController = __DIR__ . '/controllers/' . $controller . '.php';
        
        if (file_exists($caminhoController)) {
            require_once $caminhoController;
            $instancia = new $controller();
            $instancia->$action();
        } else {
            http_response_code(500);
            echo "Erro interno: Controller não encontrado.";
        }
        exit;
    }
}
?>
