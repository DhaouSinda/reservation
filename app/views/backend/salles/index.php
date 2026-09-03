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

<div class="card p-3 mb-4">
    <form action="index.php" method="GET" class="row g-2 align-items-end">
        <input type="hidden" name="controller" value="salle">
        <input type="hidden" name="action" value="index">
        <div class="col-md-4">
            <label class="form-label">Recherche</label>
            <input type="text" name="recherche" class="form-control" placeholder="Nom de la salle..." value="<?= htmlspecialchars($filtres['recherche']) ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Bâtiment</label>
            <select name="batiment_id" class="form-select">
                <option value="">Tous</option>
                <?php foreach ($batimentsListe as $b): ?>
                    <option value="<?= $b['id'] ?>" <?= (string)$filtres['batiment_id'] === (string)$b['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($b['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Statut</label>
            <select name="statut" class="form-select">
                <option value="">Tous</option>
                <option value="disponible" <?= $filtres['statut'] === 'disponible' ? 'selected' : '' ?>>Disponible</option>
                <option value="maintenance" <?= $filtres['statut'] === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                <option value="indisponible" <?= $filtres['statut'] === 'indisponible' ? 'selected' : '' ?>>Indisponible</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill"><i class="fa-solid fa-filter"></i></button>
            <a href="index.php?controller=salle&action=index" class="btn btn-outline-light" title="Réinitialiser">
                <i class="fa-solid fa-xmark"></i>
            </a>
        </div>
    </form>
</div>

<?php if (empty($salles)): ?>
    <div class="card">
        <div class="empty-state">
            <i class="fa-solid fa-door-closed"></i>
            <p>Aucune salle ne correspond à ces critères.</p>
        </div>
    </div>
<?php else: ?>
<?php
function sortLinkSalle($colKey, $label, $filtres) {
    $currentTri = $filtres['tri'] ?: 'nom';
    $currentOrdre = $filtres['ordre'] ?: 'ASC';
    $nextOrdre = ($currentTri === $colKey && $currentOrdre === 'ASC') ? 'DESC' : 'ASC';
    $params = array_merge($filtres, ['controller' => 'salle', 'action' => 'index', 'tri' => $colKey, 'ordre' => $nextOrdre]);
    $icon = '';
    if ($currentTri === $colKey) {
        $icon = $currentOrdre === 'ASC' ? ' <i class="fa-solid fa-arrow-up-short-wide"></i>' : ' <i class="fa-solid fa-arrow-down-wide-short"></i>';
    }
    return '<a href="index.php?' . http_build_query($params) . '" class="text-decoration-none" style="color: inherit;">' . $label . $icon . '</a>';
}
?>

<div class="table-responsive">
    <table class="table align-middle">
        <thead>
        <tr>
            <th><?= sortLinkSalle('nom', 'Nom', $filtres) ?></th>
            <th><?= sortLinkSalle('batiment', 'Bâtiment', $filtres) ?></th>
            <th>Étage</th>
            <th><?= sortLinkSalle('capacite', 'Capacité', $filtres) ?></th>
            <th>Équipements</th>
            <th><?= sortLinkSalle('statut', 'Statut', $filtres) ?></th>
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
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-light dropdown-toggle btn-sm-action" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-toggle-on"></i> Statut
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item" href="index.php?controller=salle&action=changerStatut&id=<?= $s['id'] ?>&statut=disponible">Disponible</a></li>
                        <li><a class="dropdown-item" href="index.php?controller=salle&action=changerStatut&id=<?= $s['id'] ?>&statut=maintenance">Maintenance</a></li>
                        <li><a class="dropdown-item" href="index.php?controller=salle&action=changerStatut&id=<?= $s['id'] ?>&statut=indisponible">Indisponible</a></li>
                    </ul>
                </div>
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
