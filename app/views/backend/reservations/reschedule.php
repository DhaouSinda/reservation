<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="mb-4">
    <h2 class="page-title"><i class="fa-solid fa-arrows-turn-right"></i> Déplacer une réservation</h2>
    <p class="page-subtitle">Changez la salle ou l'horaire d'une réservation existante</p>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation me-1"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card" style="max-width: 620px;">
    <div class="card-body p-4">
        <form action="index.php?controller=reservation&action=processReschedule" method="POST">
            <input type="hidden" name="id" value="<?= $reservation['id'] ?>">

            <div class="mb-3">
                <label class="form-label">Salle</label>
                <select name="salle_id" class="form-select" required>
                    <?php foreach ($salles as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= (int)$reservation['salle_id'] === (int)$s['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['batiment_nom']) ?> — Étage <?= $s['etage_numero'] ?>
                            — <?= htmlspecialchars($s['nom']) ?> (capacité <?= $s['capacite'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nouveau début</label>
                    <input type="datetime-local" name="date_debut" class="form-control"
                           value="<?= htmlspecialchars(str_replace(' ', 'T', substr($reservation['date_debut'], 0, 16))) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nouvelle fin</label>
                    <input type="datetime-local" name="date_fin" class="form-control"
                           value="<?= htmlspecialchars(str_replace(' ', 'T', substr($reservation['date_fin'], 0, 16))) ?>" required>
                </div>
            </div>

            <p style="color: var(--muted); font-size: 0.85rem;">
                <i class="fa-solid fa-user"></i> Motif original : <?= htmlspecialchars($reservation['motif']) ?>
            </p>

            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Enregistrer le déplacement</button>
            <a href="index.php?controller=reservation&action=index" class="btn btn-outline-light">Annuler</a>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layout_footer.php'; ?>
