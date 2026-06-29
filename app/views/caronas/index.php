<!-- Hero Section -->
<section class="hero bg-light py-5">
    <div class="container">
        <h1 class="display-4 mb-4">Encontre Caronas no Campus</h1>
        <p class="lead mb-4">Compartilhe caronas com outros alunos e economize combustível</p>
        
        <!-- Formulário de Busca -->
        <form method="GET" action="<?php echo BASE_URL; ?>/buscar" class="row g-3">
            <div class="col-md-4">
                <input type="text" class="form-control" name="origem" placeholder="De onde?" value="<?php echo htmlspecialchars($_GET['origem'] ?? ''); ?>">
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" name="destino" placeholder="Para onde?" value="<?php echo htmlspecialchars($_GET['destino'] ?? ''); ?>">
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
                                        <strong>Hora:</strong> <?php echo htmlspecialchars($carona['hora_saida']); ?><br>
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
                                    <a href="<?php echo BASE_URL; ?>/detalhes-carona&id=<?php echo $carona['id']; ?>" class="btn btn-sm btn-primary">Ver Detalhes</a>
                                <?php else: ?>
                                    <a href="<?php echo BASE_URL; ?>/login" class="btn btn-sm btn-primary">Fazer Login</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
