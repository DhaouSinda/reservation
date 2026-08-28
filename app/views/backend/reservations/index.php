<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="mb-4">
    <h2 class="page-title"><i class="fa-solid fa-clipboard-list"></i> Gestion des réservations</h2>
    <p class="page-subtitle">Validez, refusez ou consultez toutes les demandes</p>
</div>

<?php if (empty($reservations)): ?>
    <div class="card">
        <div class="empty-state">
            <i class="fa-solid fa-calendar-xmark"></i>
            <p>Aucune réservation pour le moment.</p>
        </div>
    </div>
<?php else: ?>
<div class="table-responsive">
    <table class="table align-middle">
        <thead>
        <tr>
            <th>Utilisateur</th>
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
            <td><?= htmlspecialchars($r['user_prenom'] . ' ' . $r['user_nom']) ?></td>
            <td><?= htmlspecialchars($r['salle_nom']) ?></td>
            <td><?= htmlspecialchars($r['date_debut']) ?></td>
            <td><?= htmlspecialchars($r['date_fin']) ?></td>
            <td><?= htmlspecialchars($r['motif']) ?></td>
            <td><span class="badge-statut badge-<?= htmlspecialchars($r['statut']) ?>"><?= htmlspecialchars($r['statut']) ?></span></td>
            <td>
                <?php if ($r['statut'] === 'en_attente'): ?>
                    <a href="index.php?controller=reservation&action=approve&id=<?= $r['id'] ?>" class="btn btn-sm btn-success-soft btn-sm-action">
                        <i class="fa-solid fa-check"></i> Valider
                    </a>
                    <a href="index.php?controller=reservation&action=reject&id=<?= $r['id'] ?>" class="btn btn-sm btn-danger-soft btn-sm-action">
                        <i class="fa-solid fa-xmark"></i> Refuser
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
