<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="page-title"><i class="fa-solid fa-users"></i> Utilisateurs</h2>
        <p class="page-subtitle">Gérez les comptes et les rôles (utilisateur, gestionnaire, admin bâtiments)</p>
    </div>
    <a href="index.php?controller=utilisateur&action=create" class="btn btn-primary">
        <i class="fa-solid fa-user-plus"></i> Ajouter un utilisateur
    </a>
</div>

<?php if (($_GET['error'] ?? '') === 'self_delete'): ?>
    <div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation me-1"></i> Vous ne pouvez pas supprimer votre propre compte.</div>
<?php elseif (($_GET['error'] ?? '') === 'has_reservations'): ?>
    <div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation me-1"></i> Impossible de supprimer : cet utilisateur a des réservations associées.</div>
<?php endif; ?>

<div class="card p-3 mb-4">
    <form action="index.php" method="GET" class="row g-2 align-items-end">
        <input type="hidden" name="controller" value="utilisateur">
        <input type="hidden" name="action" value="index">
        <div class="col-md-6">
            <label class="form-label">Recherche</label>
            <input type="text" name="recherche" class="form-control" placeholder="Nom, prénom, email..." value="<?= htmlspecialchars($_GET['recherche'] ?? '') ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Rôle</label>
            <select name="role" class="form-select">
                <option value="">Tous</option>
                <option value="utilisateur" <?= ($_GET['role'] ?? '') === 'utilisateur' ? 'selected' : '' ?>>Utilisateur</option>
                <option value="gestionnaire" <?= ($_GET['role'] ?? '') === 'gestionnaire' ? 'selected' : '' ?>>Gestionnaire</option>
                <option value="admin_batiments" <?= ($_GET['role'] ?? '') === 'admin_batiments' ? 'selected' : '' ?>>Admin bâtiments</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill"><i class="fa-solid fa-filter"></i></button>
            <a href="index.php?controller=utilisateur&action=index" class="btn btn-outline-light" title="Réinitialiser">
                <i class="fa-solid fa-xmark"></i>
            </a>
        </div>
    </form>
</div>

<?php if (empty($utilisateurs)): ?>
    <div class="card">
        <div class="empty-state">
            <i class="fa-solid fa-user-slash"></i>
            <p>Aucun utilisateur ne correspond à ces critères.</p>
        </div>
    </div>
<?php else: ?>
<div class="table-responsive">
    <table class="table align-middle">
        <thead>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($utilisateurs as $u): ?>
        <tr>
            <td><?= htmlspecialchars($u['nom']) ?></td>
            <td><?= htmlspecialchars($u['prenom']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><span class="badge-statut badge-disponible"><?= htmlspecialchars($u['role']) ?></span></td>
            <td>
                <a href="index.php?controller=utilisateur&action=edit&id=<?= $u['id'] ?>" class="btn btn-sm btn-primary btn-sm-action">
                    <i class="fa-solid fa-pen"></i> Modifier
                </a>
                <?php if ((int)$u['id'] !== (int)$_SESSION['user_id']): ?>
                <a href="index.php?controller=utilisateur&action=delete&id=<?= $u['id'] ?>"
                   class="btn btn-sm btn-danger-soft btn-sm-action"
                   onclick="return confirm('Supprimer cet utilisateur ?');">
                    <i class="fa-solid fa-trash"></i> Supprimer
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
