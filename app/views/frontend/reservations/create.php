<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="page-title"><i class="fa-solid fa-calendar-plus"></i> Réserver une salle</h2>
        <p class="page-subtitle">Choisissez une salle disponible et un créneau</p>
    </div>
    <a href="index.php?controller=reservation&action=mine" class="btn btn-outline-success">
        <i class="fa-solid fa-clock-rotate-left"></i> Mes réservations
    </a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation me-1"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (empty($salles)): ?>
    <div class="card">
        <div class="empty-state">
            <i class="fa-solid fa-door-closed"></i>
            <p>Aucune salle disponible pour le moment.</p>
        </div>
    </div>
<?php else: ?>
<div class="card">
    <div class="card-body p-4">
        <form action="index.php?controller=reservation&action=processCreate" method="POST">
            <div class="mb-3">
                <label class="form-label">Salle</label>
                <select name="salle_id" class="form-select" required>
                    <?php foreach ($salles as $s): ?>
                        <option value="<?= $s['id'] ?>">
                            <?= htmlspecialchars($s['batiment_nom']) ?> — Étage <?= $s['etage_numero'] ?>
                            — <?= htmlspecialchars($s['nom']) ?> (capacité <?= $s['capacite'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date et heure de début</label>
                    <input type="datetime-local" name="date_debut" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date et heure de fin</label>
                    <input type="datetime-local" name="date_fin" class="form-control" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Motif</label>
                <input type="text" name="motif" class="form-control" placeholder="Ex: Réunion d'équipe">
            </div>

            <button type="submit" class="btn btn-success"><i class="fa-solid fa-check"></i> Réserver</button>
        </form>
    </div>
</div>
<?php endif; ?>

<?php require __DIR__ . '/../layout_footer.php'; ?>
