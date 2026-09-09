<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="mb-4">
    <h2 class="page-title"><i class="fa-solid fa-building-shield"></i> Modifier le bâtiment</h2>
    <p class="page-subtitle">Mettez à jour les informations et gérez les étages</p>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-3"><i class="fa-solid fa-pen"></i> Informations générales</h5>
                <div id="jsErrorsEdit" class="alert alert-danger" style="display:none;"></div>
                <form id="formBatimentEdit" action="index.php?controller=batiment&action=processEdit" method="POST" novalidate>
                    <input type="hidden" name="id" value="<?= $batiment['id'] ?>">

                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="nom" id="nomEditInput" class="form-control" value="<?= htmlspecialchars($batiment['nom']) ?>">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Adresse</label>
                        <input type="text" name="adresse" class="form-control" value="<?= htmlspecialchars($batiment['adresse']) ?>">
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Enregistrer</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-body p-4">
                <h5 class="mb-3"><i class="fa-solid fa-layer-group"></i> Étages</h5>
                <?php if (empty($etages)): ?>
                    <p style="color: var(--muted);">Aucun étage pour ce bâtiment.</p>
                <?php else: ?>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <?php foreach ($etages as $e): ?>
                            <span class="badge-statut badge-disponible">Étage <?= $e['numero'] ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div id="jsErrorsEtage" class="alert alert-danger" style="display:none;"></div>
                <form id="formAddEtage" action="index.php?controller=batiment&action=addEtage" method="POST" class="d-flex gap-2" novalidate>
                    <input type="hidden" name="batiment_id" value="<?= $batiment['id'] ?>">
                    <input type="text" name="numero" id="numeroEtageInput" class="form-control" placeholder="N° d'étage">
                    <button type="submit" class="btn btn-primary text-nowrap"><i class="fa-solid fa-plus"></i> Ajouter</button>
                </form>
            </div>
        </div>
    </div>
</div>

<p class="mt-3"><a href="index.php?controller=batiment&action=index"><i class="fa-solid fa-arrow-left"></i> Retour à la liste</a></p>

<script>
document.getElementById('formBatimentEdit').addEventListener('submit', function (e) {
    const nom = document.getElementById('nomEditInput').value.trim();
    const box = document.getElementById('jsErrorsEdit');
    if (nom === '') {
        e.preventDefault();
        box.innerHTML = 'Le nom du bâtiment est requis.';
        box.style.display = 'block';
        box.scrollIntoView({ behavior: 'smooth', block: 'center' });
    } else {
        box.style.display = 'none';
    }
});

document.getElementById('formAddEtage').addEventListener('submit', function (e) {
    const numero = document.getElementById('numeroEtageInput').value.trim();
    const box = document.getElementById('jsErrorsEtage');
    const erreurs = [];

    if (numero === '') erreurs.push('Le numéro d\'étage est requis.');
    else if (!/^-?\d+$/.test(numero)) erreurs.push('Le numéro d\'étage doit être un nombre entier.');

    if (erreurs.length > 0) {
        e.preventDefault();
        box.innerHTML = erreurs.join('<br>');
        box.style.display = 'block';
    } else {
        box.style.display = 'none';
    }
});
</script>

<?php require __DIR__ . '/../layout_footer.php'; ?>
