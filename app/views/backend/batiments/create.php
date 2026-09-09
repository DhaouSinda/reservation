<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="mb-4">
    <h2 class="page-title"><i class="fa-solid fa-building-circle-check"></i> Ajouter un bâtiment</h2>
    <p class="page-subtitle">Créez un bâtiment et, en option, ses étages</p>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation me-1"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div id="jsErrors" class="alert alert-danger" style="display:none;"></div>

<div class="card" style="max-width: 560px;">
    <div class="card-body p-4">
        <form id="formBatimentCreate" action="index.php?controller=batiment&action=processCreate" method="POST" novalidate>
            <div class="mb-3">
                <label class="form-label">Nom</label>
                <input type="text" name="nom" id="nomInput" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Adresse</label>
                <input type="text" name="adresse" id="adresseInput" class="form-control">
            </div>
            <div class="mb-4">
                <label class="form-label">Étages (numéros séparés par des virgules)</label>
                <input type="text" name="etages" id="etagesInput" class="form-control" placeholder="Ex: 0,1,2,3">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Ajouter</button>
            <a href="index.php?controller=batiment&action=index" class="btn btn-outline-light">Annuler</a>
        </form>
    </div>
</div>

<script>
document.getElementById('formBatimentCreate').addEventListener('submit', function (e) {
    const nom = document.getElementById('nomInput').value.trim();
    const etages = document.getElementById('etagesInput').value.trim();
    const erreurs = [];

    if (nom === '') erreurs.push('Le nom du bâtiment est requis.');

    if (etages !== '') {
        const parties = etages.split(',').map(v => v.trim()).filter(v => v !== '');
        const toutesValides = parties.every(v => /^-?\d+$/.test(v));
        if (!toutesValides) erreurs.push('Les numéros d\'étage doivent être des nombres entiers séparés par des virgules.');
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
