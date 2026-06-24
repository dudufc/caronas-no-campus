<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/ReservaController.php';

$userController = new UserController();
$reservaController = new ReservaController();

if (!$userController->estaAutenticado()) {
    header('Location: ' . BASE_URL . 'login.php');
    exit;
}

$usuario = $userController->obterUsuarioAutenticado();
$reservas = $reservaController->listarPorUsuario($usuario['id']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Reservas - <?php echo APP_NAME; ?></title>
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
                        <a class="nav-link" href="<?php echo BASE_URL; ?>oferecer-carona.php">Oferecer Carona</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo BASE_URL; ?>minhas-reservas.php">Minhas Reservas</a>
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
        <h2 class="mb-4">Minhas Reservas</h2>
        
        <?php if (empty($reservas)): ?>
            <div class="alert alert-info">
                Você ainda não tem nenhuma reserva. <a href="<?php echo BASE_URL; ?>index.php">Busque caronas disponíveis</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Origem</th>
                            <th>Destino</th>
                            <th>Data</th>
                            <th>Hora</th>
                            <th>Motorista</th>
                            <th>Status</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservas as $reserva): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($reserva['origem']); ?></td>
                                <td><?php echo htmlspecialchars($reserva['destino']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($reserva['data_saida'])); ?></td>
                                <td><?php echo $reserva['hora_saida']; ?></td>
                                <td><?php echo htmlspecialchars($reserva['motorista']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $reserva['status'] === 'confirmada' ? 'success' : 'warning'; ?>">
                                        <?php echo ucfirst($reserva['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>cancelar-reserva.php?id=<?php echo $reserva['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja cancelar esta reserva?')">Cancelar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
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
