<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="mb-4">
    <h2 class="page-title"><i class="fa-solid fa-building-circle-check"></i> Ajouter un bâtiment</h2>
    <p class="page-subtitle">Créez un bâtiment et, en option, ses étages</p>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation me-1"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card" style="max-width: 560px;">
    <div class="card-body p-4">
        <form action="index.php?controller=batiment&action=processCreate" method="POST">
            <div class="mb-3">
                <label class="form-label">Nom</label>
                <input type="text" name="nom" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Adresse</label>
                <input type="text" name="adresse" class="form-control">
            </div>
            <div class="mb-4">
                <label class="form-label">Étages (numéros séparés par des virgules)</label>
                <input type="text" name="etages" class="form-control" placeholder="Ex: 0,1,2,3">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Ajouter</button>
            <a href="index.php?controller=batiment&action=index" class="btn btn-outline-light">Annuler</a>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layout_footer.php'; ?>
