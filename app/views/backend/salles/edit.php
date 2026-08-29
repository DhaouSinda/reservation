<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="mb-4">
    <h2 class="page-title"><i class="fa-solid fa-door-closed"></i> Modifier la salle</h2>
    <p class="page-subtitle">Mettez à jour les caractéristiques et le statut</p>
</div>

<div class="card" style="max-width: 620px;">
    <div class="card-body p-4">
        <form action="index.php?controller=salle&action=processEdit" method="POST">
            <input type="hidden" name="id" value="<?= $salle['id'] ?>">

            <div class="mb-3">
                <label class="form-label">Nom</label>
                <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($salle['nom']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Capacité</label>
                <input type="number" name="capacite" class="form-control" value="<?= (int)$salle['capacite'] ?>" min="1" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Équipements</label>
                <input type="text" name="equipements" class="form-control" value="<?= htmlspecialchars($salle['equipements']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Localisation</label>
                <input type="text" name="localisation" class="form-control" value="<?= htmlspecialchars($salle['localisation']) ?>">
            </div>

            <div class="mb-4">
                <label class="form-label">Statut</label>
                <select name="statut" class="form-select">
                    <option value="disponible" <?= $salle['statut'] === 'disponible' ? 'selected' : '' ?>>Disponible</option>
                    <option value="maintenance" <?= $salle['statut'] === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                    <option value="indisponible" <?= $salle['statut'] === 'indisponible' ? 'selected' : '' ?>>Indisponible</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Enregistrer</button>
            <a href="index.php?controller=salle&action=index" class="btn btn-outline-light">Annuler</a>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layout_footer.php'; ?>
