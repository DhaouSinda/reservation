<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="page-title"><i class="fa-solid fa-building"></i> Bâtiments</h2>
        <p class="page-subtitle">Gérez les bâtiments et leurs étages</p>
    </div>
    <a href="index.php?controller=batiment&action=create" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Ajouter un bâtiment
    </a>
</div>

<?php if (empty($batiments) && $recherche === ''): ?>
    <div class="card">
        <div class="empty-state">
            <i class="fa-solid fa-building-circle-xmark"></i>
            <p>Aucun bâtiment enregistré pour le moment.</p>
        </div>
    </div>
<?php else: ?>
<div class="card p-3 mb-4">
    <form action="index.php" method="GET" class="d-flex gap-2">
        <input type="hidden" name="controller" value="batiment">
        <input type="hidden" name="action" value="index">
        <input type="text" name="recherche" class="form-control" placeholder="Rechercher un bâtiment..." value="<?= htmlspecialchars($recherche) ?>">
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
        <?php if ($recherche !== ''): ?>
            <a href="index.php?controller=batiment&action=index" class="btn btn-outline-light"><i class="fa-solid fa-xmark"></i></a>
        <?php endif; ?>
    </form>
</div>

<?php if (empty($batiments)): ?>
    <div class="card">
        <div class="empty-state">
            <i class="fa-solid fa-magnifying-glass"></i>
            <p>Aucun bâtiment ne correspond à « <?= htmlspecialchars($recherche) ?> ».</p>
        </div>
    </div>
<?php else: ?>
<?php
function sortLinkBatiment($colKey, $label, $recherche, $tri, $ordre) {
    $currentTri = $tri ?: 'nom';
    $currentOrdre = $ordre ?: 'ASC';
    $nextOrdre = ($currentTri === $colKey && $currentOrdre === 'ASC') ? 'DESC' : 'ASC';
    $params = ['controller' => 'batiment', 'action' => 'index', 'recherche' => $recherche, 'tri' => $colKey, 'ordre' => $nextOrdre];
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
            <th><?= sortLinkBatiment('nom', 'Nom', $recherche, $tri, $ordre) ?></th>
            <th><?= sortLinkBatiment('adresse', 'Adresse', $recherche, $tri, $ordre) ?></th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($batiments as $b): ?>
        <tr>
            <td><?= htmlspecialchars($b['nom']) ?></td>
            <td><?= htmlspecialchars($b['adresse']) ?></td>
            <td>
                <a href="index.php?controller=batiment&action=edit&id=<?= $b['id'] ?>" class="btn btn-sm btn-primary btn-sm-action">
                    <i class="fa-solid fa-pen"></i> Modifier
                </a>
                <a href="index.php?controller=batiment&action=delete&id=<?= $b['id'] ?>"
                   class="btn btn-sm btn-danger-soft btn-sm-action"
                   onclick="return confirm('Supprimer ce bâtiment ?');">
                    <i class="fa-solid fa-trash"></i> Supprimer
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
<?php endif; ?>

<?php require __DIR__ . '/../layout_footer.php'; ?>
