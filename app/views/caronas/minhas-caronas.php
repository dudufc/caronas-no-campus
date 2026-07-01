<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">
                <i class="bi bi-car-front"></i> Minhas Caronas
            </h2>
            
            <?php if (empty($caronas)): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Você ainda não ofereceu nenhuma carona. 
                    <a href="<?php echo BASE_URL; ?>oferecer-carona">Clique aqui para oferecer uma carona!</a>
                </div>
            <?php else: ?>
                <?php foreach ($caronas as $carona): ?>
                    <div class="card mb-4 shadow">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-geo-alt"></i> 
                                <?php echo htmlspecialchars($carona['origem']); ?> → 
                                <?php echo htmlspecialchars($carona['destino']); ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p>
                                        <strong>Data:</strong> <?php echo date('d/m/Y', strtotime($carona['data_saida'])); ?><br>
                                        <strong>Hora:</strong> <?php echo htmlspecialchars($carona['hora_saida']); ?><br>
                                        <strong>Vagas Disponíveis:</strong> <?php echo $carona['vagas_disponiveis']; ?>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <?php if (!empty($carona['descricao'])): ?>
                                        <p>
                                            <strong>Descrição:</strong><br>
                                            <?php echo htmlspecialchars($carona['descricao']); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Seção de Reservas Pendentes -->
                            <?php 
                                // Buscar reservas para esta carona
                                $reservas = $this->reservaModel->listarPorCarona($carona['id']);
                                $reservasPendentes = array_filter($reservas, function($r) { return $r['status'] === 'pendente'; });
                                $reservasConfirmadas = array_filter($reservas, function($r) { return $r['status'] === 'confirmada'; });
                            ?>
                            
                            <?php if (!empty($reservasPendentes)): ?>
                                <div class="alert alert-warning mt-3">
                                    <h6><i class="bi bi-clock-history"></i> Pedidos Pendentes (<?php echo count($reservasPendentes); ?>)</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Passageiro</th>
                                                    <th>Telefone</th>
                                                    <th>Data da Solicitação</th>
                                                    <th>Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($reservasPendentes as $reserva): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($reserva['passageiro']); ?></td>
                                                        <td><?php echo htmlspecialchars($reserva['telefone']); ?></td>
                                                        <td><?php echo date('d/m/Y H:i', strtotime($reserva['data_reserva'])); ?></td>
                                                        <td>
                                                            <form method="POST" style="display: inline;">
                                                                <input type="hidden" name="id" value="<?php echo $reserva['id']; ?>">
                                                                <button type="submit" formaction="<?php echo BASE_URL; ?>aceitar-reserva" class="btn btn-sm btn-success" title="Aceitar">
                                                                    <i class="bi bi-check-circle"></i> Aceitar
                                                                </button>
                                                            </form>
                                                            <form method="POST" style="display: inline;">
                                                                <input type="hidden" name="id" value="<?php echo $reserva['id']; ?>">
                                                                <button type="submit" formaction="<?php echo BASE_URL; ?>recusar-reserva" class="btn btn-sm btn-danger" title="Recusar" onclick="return confirm('Tem certeza que deseja recusar esta reserva?');">
                                                                    <i class="bi bi-x-circle"></i> Recusar
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Seção de Reservas Confirmadas -->
                            <?php if (!empty($reservasConfirmadas)): ?>
                                <div class="alert alert-success mt-3">
                                    <h6><i class="bi bi-check-circle"></i> Passageiros Confirmados (<?php echo count($reservasConfirmadas); ?>)</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Passageiro</th>
                                                    <th>Telefone</th>
                                                    <th>Data da Confirmação</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($reservasConfirmadas as $reserva): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($reserva['passageiro']); ?></td>
                                                        <td><?php echo htmlspecialchars($reserva['telefone']); ?></td>
                                                        <td><?php echo date('d/m/Y H:i', strtotime($reserva['data_reserva'])); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (empty($reservas)): ?>
                                <p class="text-muted mt-3">Nenhuma reserva para esta carona ainda.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="mt-4">
        <a href="<?php echo BASE_URL; ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>
