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
        <div id="jsErrors" class="alert alert-danger" style="display:none;"></div>
        <form id="formManuelle" action="index.php?controller=reservation&action=processCreateManuelle" method="POST" novalidate>
            <div class="mb-3">
                <label class="form-label">Utilisateur</label>
                <select name="utilisateur_id" class="form-select">
                    <option value="">-- Choisir un utilisateur --</option>
                    <?php foreach ($utilisateurs as $u): ?>
                        <option value="<?= $u['id'] ?>">
                            <?= htmlspecialchars($u['prenom'] . ' ' . $u['nom']) ?> (<?= htmlspecialchars($u['email']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Salle</label>
                <select name="salle_id" class="form-select">
                    <option value="">-- Choisir une salle --</option>
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
                    <input type="datetime-local" name="date_debut" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Fin</label>
                    <input type="datetime-local" name="date_fin" class="form-control">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formManuelle');
    if (!form) return;
    form.addEventListener('submit', function(e) {
        const utilisateurId = form.querySelector('[name="utilisateur_id"]').value;
        const salleId = form.querySelector('[name="salle_id"]').value;
        const debut = form.querySelector('[name="date_debut"]');
        const fin = form.querySelector('[name="date_fin"]');
        const erreurs = [];

        if (utilisateurId === '') erreurs.push('Veuillez choisir un utilisateur.');
        if (salleId === '') erreurs.push('Veuillez choisir une salle.');
        if (!debut.value || !fin.value) {
            erreurs.push('Les dates de début et de fin sont requises.');
        } else if (new Date(fin.value) <= new Date(debut.value)) {
            erreurs.push('La date de fin doit être après la date de début.');
        }

        const box = document.getElementById('jsErrors');
        if (erreurs.length > 0) {
            e.preventDefault();
            box.innerHTML = erreurs.join('<br>');
            box.style.display = 'block';
            box.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            box.style.display = 'none';
        }
    });
});
</script>

<?php require __DIR__ . '/../layout_footer.php'; ?>
