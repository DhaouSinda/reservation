<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="page-title"><i class="fa-solid fa-calendar-check"></i> Mes réservations</h2>
        <p class="page-subtitle">Historique et suivi de vos demandes</p>
    </div>
    <a href="index.php?controller=reservation&action=create" class="btn btn-success">
        <i class="fa-solid fa-plus"></i> Nouvelle réservation
    </a>
</div>

<?php if (empty($reservations)): ?>
    <div class="card">
        <div class="empty-state">
            <i class="fa-solid fa-calendar-xmark"></i>
            <p>Vous n'avez aucune réservation pour le moment.</p>
        </div>
    </div>
<?php else: ?>
<div class="table-responsive">
    <table class="table align-middle">
        <thead>
        <tr>
            <th>Salle</th>
            <th>Début</th>
            <th>Fin</th>
            <th>Motif</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($reservations as $r): ?>
        <tr>
            <td><?= htmlspecialchars($r['salle_nom']) ?></td>
            <td><?= htmlspecialchars($r['date_debut']) ?></td>
            <td><?= htmlspecialchars($r['date_fin']) ?></td>
            <td><?= htmlspecialchars($r['motif']) ?></td>
            <td><span class="badge-statut badge-<?= htmlspecialchars($r['statut']) ?>"><?= htmlspecialchars($r['statut']) ?></span></td>
            <td>
                <?php if ($r['statut'] === 'en_attente' || $r['statut'] === 'validee'): ?>
                    <a href="index.php?controller=reservation&action=cancel&id=<?= $r['id'] ?>"
                       class="btn btn-sm btn-outline-danger"
                       onclick="return confirm('Annuler cette réservation ?');">
                        <i class="fa-solid fa-xmark"></i> Annuler
                    </a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php require __DIR__ . '/../layout_footer.php'; ?>
