<div class="container py-5">
    <h2 class="mb-4">
        <i class="bi bi-search"></i> Resultados da Busca
    </h2>
    
    <div class="mb-4">
        <form method="GET" action="<?php echo BASE_URL; ?>buscar" class="row g-3">
            <div class="col-md-4">
                <input type="text" class="form-control" name="origem" placeholder="De onde?" value="<?php echo htmlspecialchars($_GET['origem'] ?? ''); ?>">
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" name="destino" placeholder="Para onde?" value="<?php echo htmlspecialchars($_GET['destino'] ?? ''); ?>">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">Buscar Novamente</button>
            </div>
        </form>
    </div>
    
    <?php if (empty($caronas)): ?>
        <div class="alert alert-info">
            Nenhuma carona encontrada com esses critérios. Tente novamente com outros termos.
        </div>
    <?php else: ?>
        <p class="text-muted mb-4">Encontrados <?php echo count($caronas); ?> resultado(s)</p>
        
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
                                <a href="<?php echo BASE_URL; ?>detalhes-carona?id=<?php echo $carona['id']; ?>" class="btn btn-sm btn-primary">Ver Detalhes</a>
                            <?php else: ?>
                                <a href="<?php echo BASE_URL; ?>login" class="btn btn-sm btn-primary">Fazer Login</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <div class="mt-4">
        <a href="<?php echo BASE_URL; ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>
