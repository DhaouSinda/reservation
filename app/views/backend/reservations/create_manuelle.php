<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="mb-4">
    <h2 class="page-title"><i class="fa-solid fa-user-plus"></i> Nouvelle réservation manuelle</h2>
    <p class="page-subtitle">Créez une réservation directement au nom d'un utilisateur</p>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation me-1"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (empty($utilisateurs)): ?>
    <div class="card">
        <div class="empty-state">
            <i class="fa-solid fa-user-slash"></i>
            <p>Aucun utilisateur avec le rôle "utilisateur" pour le moment.</p>
        </div>
    </div>
<?php elseif (empty($salles)): ?>
    <div class="card">
        <div class="empty-state">
            <i class="fa-solid fa-door-closed"></i>
            <p>Aucune salle disponible pour le moment.</p>
        </div>
    </div>
<?php else: ?>
<div class="card" style="max-width: 620px;">
    <div class="card-body p-4">
        <form action="index.php?controller=reservation&action=processCreateManuelle" method="POST">
            <div class="mb-3">
                <label class="form-label">Utilisateur</label>
                <select name="utilisateur_id" class="form-select" required>
                    <?php foreach ($utilisateurs as $u): ?>
                        <option value="<?= $u['id'] ?>">
                            <?= htmlspecialchars($u['prenom'] . ' ' . $u['nom']) ?> (<?= htmlspecialchars($u['email']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

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
                    <label class="form-label">Début</label>
                    <input type="datetime-local" name="date_debut" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Fin</label>
                    <input type="datetime-local" name="date_fin" class="form-control" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Motif</label>
                <input type="text" name="motif" class="form-control" placeholder="Ex: Réunion d'équipe">
            </div>

            <p style="color: var(--muted); font-size: 0.85rem;">
                <i class="fa-solid fa-circle-info"></i> Cette réservation sera créée avec le statut "Validée" directement.
            </p>

            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Créer la réservation</button>
            <a href="index.php?controller=reservation&action=index" class="btn btn-outline-light">Annuler</a>
        </form>
    </div>
</div>
<?php endif; ?>

<?php require __DIR__ . '/../layout_footer.php'; ?>
