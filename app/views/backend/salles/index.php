<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="page-title"><i class="fa-solid fa-door-open"></i> Salles</h2>
        <p class="page-subtitle">Gérez les salles et leur disponibilité</p>
    </div>
    <a href="index.php?controller=salle&action=create" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Ajouter une salle
    </a>
</div>

<?php if (empty($salles)): ?>
    <div class="card">
        <div class="empty-state">
            <i class="fa-solid fa-door-closed"></i>
            <p>Aucune salle enregistrée pour le moment.</p>
        </div>
    </div>
<?php else: ?>
<div class="table-responsive">
    <table class="table align-middle">
        <thead>
        <tr>
            <th>Nom</th>
            <th>Bâtiment</th>
            <th>Étage</th>
            <th>Capacité</th>
            <th>Équipements</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($salles as $s): ?>
        <tr>
            <td><?= htmlspecialchars($s['nom']) ?></td>
            <td><?= htmlspecialchars($s['batiment_nom']) ?></td>
            <td><?= htmlspecialchars($s['etage_numero']) ?></td>
            <td><?= (int)$s['capacite'] ?></td>
            <td><?= htmlspecialchars($s['equipements']) ?></td>
            <td><span class="badge-statut badge-<?= htmlspecialchars($s['statut']) ?>"><?= htmlspecialchars($s['statut']) ?></span></td>
            <td>
                <a href="index.php?controller=salle&action=edit&id=<?= $s['id'] ?>" class="btn btn-sm btn-primary btn-sm-action">
                    <i class="fa-solid fa-pen"></i> Modifier
                </a>
                <a href="index.php?controller=salle&action=delete&id=<?= $s['id'] ?>"
                   class="btn btn-sm btn-danger-soft btn-sm-action"
                   onclick="return confirm('Supprimer cette salle ?');">
                    <i class="fa-solid fa-trash"></i> Supprimer
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php require __DIR__ . '/../layout_footer.php'; ?>
