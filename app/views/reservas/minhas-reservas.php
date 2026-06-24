<div class="container py-5">
    <h2 class="mb-4">
        <i class="bi bi-bookmark-check"></i> Minhas Reservas
    </h2>
    
    <?php if (empty($reservas)): ?>
        <div class="alert alert-info">
            Você ainda não tem reservas. <a href="<?php echo BASE_URL; ?>">Busque caronas disponíveis</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Origem</th>
                        <th>Destino</th>
                        <th>Motorista</th>
                        <th>Data</th>
                        <th>Hora</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservas as $reserva): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reserva['origem']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['destino']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['motorista']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($reserva['data_saida'])); ?></td>
                            <td><?php echo htmlspecialchars($reserva['hora_saida']); ?></td>
                            <td>
                                <span class="badge bg-<?php echo $reserva['status'] === 'confirmada' ? 'success' : 'warning'; ?>">
                                    <?php echo ucfirst($reserva['status']); ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="<?php echo BASE_URL; ?>cancelar-reserva" style="display: inline;">
                                    <input type="hidden" name="id" value="<?php echo $reserva['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja cancelar esta reserva?')">
                                        <i class="bi bi-trash"></i> Cancelar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    
    <div class="mt-4">
        <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">
            <i class="bi bi-search"></i> Buscar Mais Caronas
        </a>
    </div>
</div>
