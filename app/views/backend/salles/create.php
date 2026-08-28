<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="mb-4">
    <h2 class="page-title"><i class="fa-solid fa-door-open"></i> Ajouter une salle</h2>
    <p class="page-subtitle">Renseignez les caractéristiques de la salle</p>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation me-1"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (empty($etagesDisponibles)): ?>
    <div class="card">
        <div class="empty-state">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <p>Aucun étage disponible. Créez d'abord un bâtiment et un étage.</p>
        </div>
    </div>
<?php else: ?>
<div class="card" style="max-width: 620px;">
    <div class="card-body p-4">
        <form action="index.php?controller=salle&action=processCreate" method="POST">
            <div class="mb-3">
                <label class="form-label">Étage</label>
                <select name="etage_id" class="form-select" required>
                    <?php foreach ($etagesDisponibles as $e): ?>
                        <option value="<?= $e['etage_id'] ?>">
                            <?= htmlspecialchars($e['batiment_nom']) ?> — Étage <?= $e['numero'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Nom de la salle</label>
                <input type="text" name="nom" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Capacité</label>
                <input type="number" name="capacite" class="form-control" min="1" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Équipements</label>
                <input type="text" name="equipements" class="form-control" placeholder="Ex: Vidéoprojecteur, Wifi">
            </div>

            <div class="mb-4">
                <label class="form-label">Localisation</label>
                <input type="text" name="localisation" class="form-control" placeholder="Ex: Aile B">
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Ajouter</button>
            <a href="index.php?controller=salle&action=index" class="btn btn-outline-light">Annuler</a>
        </form>
    </div>
</div>
<?php endif; ?>

<?php require __DIR__ . '/../layout_footer.php'; ?>
