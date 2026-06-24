<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../controllers/UserController.php';

$userController = new UserController();
$mensagem = '';
$tipo_mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = $userController->login($_POST['email'] ?? '', $_POST['senha'] ?? '');
    
    if ($resultado['sucesso']) {
        header('Location: ' . BASE_URL . 'index.php');
        exit;
    } else {
        $mensagem = $resultado['mensagem'];
        $tipo_mensagem = 'danger';
    }
}

if ($userController->estaAutenticado()) {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <h2 class="card-title text-center mb-4">Login</h2>
                        
                        <?php if (!empty($mensagem)): ?>
                            <div class="alert alert-<?php echo $tipo_mensagem; ?>" role="alert">
                                <?php echo htmlspecialchars($mensagem); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="senha" name="senha" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">Entrar</button>
                        </form>
                        
                        <hr class="my-4">
                        
                        <p class="text-center mb-0">
                            Não tem conta? <a href="<?php echo BASE_URL; ?>registro.php">Registre-se aqui</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
