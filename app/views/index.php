<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/CaronaController.php';

$userController = new UserController();
$caronaController = new CaronaController();

$usuarioAutenticado = $userController->estaAutenticado();
$caronas = $caronaController->listar();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - Encontre Caronas</title>
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
                    <?php if ($usuarioAutenticado): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>oferecer-carona.php">Oferecer Carona</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>minhas-reservas.php">Minhas Reservas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>perfil.php">Perfil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>logout.php">Sair</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>registro.php">Registrar</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero bg-light py-5">
        <div class="container">
            <h1 class="display-4 mb-4">Encontre Caronas no Campus</h1>
            <p class="lead mb-4">Compartilhe caronas com outros alunos e economize combustível</p>
            
            <!-- Formulário de Busca -->
            <form method="GET" action="<?php echo BASE_URL; ?>buscar.php" class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="origem" placeholder="De onde?" value="<?php echo $_GET['origem'] ?? ''; ?>">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="destino" placeholder="Para onde?" value="<?php echo $_GET['destino'] ?? ''; ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Buscar Caronas</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Lista de Caronas -->
    <section class="py-5">
        <div class="container">
            <h2 class="mb-4">Caronas Disponíveis</h2>
            
            <?php if (empty($caronas)): ?>
                <div class="alert alert-info">
                    Nenhuma carona disponível no momento. Seja o primeiro a oferecer uma!
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($caronas as $carona): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?php echo htmlspecialchars($carona['origem']); ?> → <?php echo htmlspecialchars($carona['destino']); ?>
                                    </h5>
                                    <p class="card-text text-muted">
                                        <small>
                                            <strong>Motorista:</strong> <?php echo htmlspecialchars($carona['motorista']); ?><br>
                                            <strong>Data:</strong> <?php echo date('d/m/Y', strtotime($carona['data_saida'])); ?><br>
                                            <strong>Hora:</strong> <?php echo $carona['hora_saida']; ?><br>
                                            <strong>Vagas:</strong> <?php echo $carona['vagas_disponiveis']; ?>
                                        </small>
                                    </p>
                                    <?php if (!empty($carona['descricao'])): ?>
                                        <p class="card-text">
                                            <small><?php echo htmlspecialchars($carona['descricao']); ?></small>
                                        </p>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer bg-white">
                                    <?php if ($usuarioAutenticado): ?>
                                        <a href="<?php echo BASE_URL; ?>detalhes-carona.php?id=<?php echo $carona['id']; ?>" class="btn btn-sm btn-primary">Ver Detalhes</a>
                                    <?php else: ?>
                                        <a href="<?php echo BASE_URL; ?>login.php" class="btn btn-sm btn-primary">Fazer Login</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p>&copy; 2026 <?php echo APP_NAME; ?>. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
