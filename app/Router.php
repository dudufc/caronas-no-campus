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
        $this->routes['GET']['/minhas-caronas'] = ['controller' => 'CaronaController', 'action' => 'listarMinhas', 'auth' => true];
        $this->routes['GET']['/minhas-reservas'] = ['controller' => 'ReservaController', 'action' => 'listarMinhas', 'auth' => true];
        $this->routes['POST']['/cancelar-reserva'] = ['controller' => 'ReservaController', 'action' => 'cancelar', 'auth' => true];
        $this->routes['POST']['/criar-reserva'] = ['controller' => 'ReservaController', 'action' => 'criar', 'auth' => true];
        $this->routes['POST']['/aceitar-reserva'] = ['controller' => 'ReservaController', 'action' => 'aceitar', 'auth' => true];
        $this->routes['POST']['/recusar-reserva'] = ['controller' => 'ReservaController', 'action' => 'recusar', 'auth' => true];
        $this->routes['GET']['/logout'] = ['controller' => 'UserController', 'action' => 'logout', 'auth' => true];
        $this->routes['GET']['/perfil'] = ['controller' => 'UserController', 'action' => 'mostrarPerfil', 'auth' => true];
    }
    
    /**
     * Processar a requisição atual
     */
    public function processar() {
        $metodo = $_SERVER['REQUEST_METHOD'];
        
        // Obter a URI do parâmetro 'url'
        $uri = $_GET['url'] ?? '/';
        
        // Limpar a URI: garantir que comece com / e remover o index.php se ele vier por engano
        $uri = '/' . trim($uri, '/');
        if ($uri === '/index.php' || empty($uri)) {
            $uri = '/';
        }
        
        // Buscar rota correspondente
        if (isset($this->routes[$metodo][$uri])) {
            $rota = $this->routes[$metodo][$uri];
            
            // Verificar autenticação
            if (isset($rota['auth']) && $rota['auth']) {
                if (!isset($_SESSION['usuario_id'])) {
                    header('Location: ' . BASE_URL . '/login');
                    exit;
                }
            }
            
            $this->executarRota($rota);
        } else {
            // Fallback para a página inicial se a URI for inválida ou vazia
            $this->executarRota($this->routes['GET']['/']);
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
