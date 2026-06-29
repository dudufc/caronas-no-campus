<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="card-title text-center mb-4">
                        <i class="bi bi-person-plus"></i> Registrar
                    </h2>
                    
                    <form method="POST" action="<?php echo BASE_URL; ?>registro">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="tel" class="form-control" id="telefone" name="telefone" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="senha" name="senha" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirma_senha" class="form-label">Confirmar Senha</label>
                            <input type="password" class="form-control" id="confirma_senha" name="confirma_senha" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Registrar</button>
                    </form>
                    
                    <hr class="my-4">
                    
                    <p class="text-center">
                        Já tem conta? <a href="<?php echo BASE_URL; ?>/login">Faça login aqui</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
