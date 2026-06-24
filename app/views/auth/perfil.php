<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="card-title mb-4">
                        <i class="bi bi-person-circle"></i> Meu Perfil
                    </h2>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p>
                                <strong>Nome:</strong><br>
                                <?php echo htmlspecialchars($usuario['nome']); ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p>
                                <strong>Email:</strong><br>
                                <?php echo htmlspecialchars($usuario['email']); ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p>
                                <strong>Telefone:</strong><br>
                                <?php echo htmlspecialchars($usuario['telefone']); ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p>
                                <strong>Membro desde:</strong><br>
                                <?php echo date('d/m/Y', strtotime($usuario['data_criacao'] ?? 'now')); ?>
                            </p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-grid gap-2">
                        <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
