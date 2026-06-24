<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="card-title mb-4">
                        <i class="bi bi-car-front"></i> Oferecer Carona
                    </h2>
                    
                    <form method="POST" action="<?php echo BASE_URL; ?>oferecer-carona">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="origem" class="form-label">Origem</label>
                                <input type="text" class="form-control" id="origem" name="origem" placeholder="De onde sai?" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="destino" class="form-label">Destino</label>
                                <input type="text" class="form-control" id="destino" name="destino" placeholder="Para onde vai?" required>
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
                            <input type="number" class="form-control" id="vagas" name="vagas" min="1" max="10" value="1" required>
                            <small class="form-text text-muted">Máximo de 10 vagas</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição (Opcional)</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="3" placeholder="Adicione informações extras (ex: paradas no caminho, tipo de carro, etc)"></textarea>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Publicar Carona</button>
                            <a href="<?php echo BASE_URL; ?>" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
