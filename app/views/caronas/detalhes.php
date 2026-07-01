<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="card-title mb-4">
                        <?php echo htmlspecialchars($carona['origem']); ?> → <?php echo htmlspecialchars($carona['destino']); ?>
                    </h2>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Informações da Viagem</h5>
                            <p>
                                <strong>Data:</strong> <?php echo date('d/m/Y', strtotime($carona['data_saida'])); ?><br>
                                <strong>Hora:</strong> <?php echo htmlspecialchars($carona['hora_saida']); ?><br>
                                <strong>Vagas Disponíveis:</strong> <?php echo $carona['vagas_disponiveis']; ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h5>Motorista</h5>
                            <p>
                                <strong>Nome:</strong> <?php echo htmlspecialchars($carona['motorista']); ?><br>
                                <strong>Telefone:</strong> <?php echo htmlspecialchars($carona['telefone']); ?>
                            </p>
                        </div>
                    </div>
                    
                    <?php if (!empty($carona['descricao'])): ?>
                        <div class="mb-4">
                            <h5>Descrição</h5>
                            <p><?php echo htmlspecialchars($carona['descricao']); ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <?php 
                        $usuarioEhMotorista = isset($_SESSION['usuario_id']) && $_SESSION['usuario_id'] == $carona['usuario_id'];
                    ?>
                    
                    <?php if ($usuarioEhMotorista): ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Esta é sua carona. Você pode gerenciar os pedidos de reserva em <a href="<?php echo BASE_URL; ?>minhas-caronas">Minhas Caronas</a>.
                        </div>
                    <?php elseif ($carona['vagas_disponiveis'] > 0): ?>
                        <form method="POST" action="<?php echo BASE_URL; ?>criar-reserva">
                            <input type="hidden" name="carona_id" value="<?php echo $carona['id']; ?>">
                            <button type="submit" class="btn btn-lg btn-success">
                                <i class="bi bi-check-circle"></i> Reservar Vaga
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> Esta carona não tem vagas disponíveis
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <a href="<?php echo BASE_URL; ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>
