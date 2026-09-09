<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="mb-4">
    <h2 class="page-title"><i class="fa-solid fa-door-closed"></i> Modifier la salle</h2>
    <p class="page-subtitle">Mettez à jour les caractéristiques et le statut</p>
</div>

<div id="jsErrors" class="alert alert-danger" style="display:none;"></div>
<div class="card" style="max-width: 620px;">
    <div class="card-body p-4">
        <form id="formSalleEdit" action="index.php?controller=salle&action=processEdit" method="POST" novalidate>
            <input type="hidden" name="id" value="<?= $salle['id'] ?>">

            <div class="mb-3">
                <label class="form-label">Nom</label>
                <input type="text" name="nom" id="nomInput" class="form-control" value="<?= htmlspecialchars($salle['nom']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Capacité</label>
                <input type="number" name="capacite" id="capaciteInput" class="form-control" value="<?= (int)$salle['capacite'] ?>">
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

<script>
document.getElementById('formSalleEdit').addEventListener('submit', function (e) {
    const nom = document.getElementById('nomInput').value.trim();
    const capacite = document.getElementById('capaciteInput').value;
    const erreurs = [];

    if (nom === '') erreurs.push('Le nom de la salle est requis.');
    if (capacite === '' || !Number.isInteger(Number(capacite)) || Number(capacite) <= 0) {
        erreurs.push('La capacité doit être un nombre entier supérieur à 0.');
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
</script>

<?php require __DIR__ . '/../layout_footer.php'; ?>
