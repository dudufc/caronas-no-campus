<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/CaronaController.php';

$userController = new UserController();
$caronaController = new CaronaController();

if (!$userController->estaAutenticado()) {
    header('Location: ' . BASE_URL . 'login.php');
    exit;
}

$usuario = $userController->obterUsuarioAutenticado();
$mensagem = '';
$tipo_mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = $caronaController->criar(
        $usuario['id'],
        $_POST['origem'] ?? '',
        $_POST['destino'] ?? '',
        $_POST['data_saida'] ?? '',
        $_POST['hora_saida'] ?? '',
        $_POST['vagas'] ?? 0,
        $_POST['descricao'] ?? ''
    );
    
    if ($resultado['sucesso']) {
        $mensagem = $resultado['mensagem'];
        $tipo_mensagem = 'success';
        // Limpar formulário
        $_POST = [];
    } else {
        $mensagem = $resultado['mensagem'];
        $tipo_mensagem = 'danger';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oferecer Carona - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css">
</head>
<body>
    <!-- Navegação -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
                <i class="bi bi-car-front"></i> <?php echo APP_NAME; ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>index.php">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo BASE_URL; ?>oferecer-carona.php">Oferecer Carona</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>minhas-reservas.php">Minhas Reservas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>logout.php">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <h2 class="card-title mb-4">Oferecer Carona</h2>
                        
                        <?php if (!empty($mensagem)): ?>
                            <div class="alert alert-<?php echo $tipo_mensagem; ?>" role="alert">
                                <?php echo htmlspecialchars($mensagem); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="origem" class="form-label">Origem</label>
                                    <input type="text" class="form-control" id="origem" name="origem" placeholder="De onde?" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="destino" class="form-label">Destino</label>
                                    <input type="text" class="form-control" id="destino" name="destino" placeholder="Para onde?" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="data_saida" class="form-label">Data da Saída</label>
                                    <input type="date" class="form-control" id="data_saida" name="data_saida" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="hora_saida" class="form-label">Hora da Saída</label>
                                    <input type="time" class="form-control" id="hora_saida" name="hora_saida" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="vagas" class="form-label">Número de Vagas</label>
                                <input type="number" class="form-control" id="vagas" name="vagas" min="1" max="10" value="4" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="descricao" class="form-label">Descrição (Opcional)</label>
                                <textarea class="form-control" id="descricao" name="descricao" rows="4" placeholder="Adicione informações sobre a carona..."></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">Oferecer Carona</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p>&copy; 2026 <?php echo APP_NAME; ?>. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
