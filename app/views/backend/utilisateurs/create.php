<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="mb-4">
    <h2 class="page-title"><i class="fa-solid fa-user-plus"></i> Ajouter un utilisateur</h2>
    <p class="page-subtitle">Créez un compte et attribuez-lui un rôle</p>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation me-1"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div id="jsErrors" class="alert alert-danger" style="display:none;"></div>

<div class="card" style="max-width: 620px;">
    <div class="card-body p-4">
        <form id="formUtilisateurCreate" action="index.php?controller=utilisateur&action=processCreate" method="POST" novalidate>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nom" id="nomInput" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Prénom</label>
                    <input type="text" name="prenom" id="prenomInput" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="text" name="email" id="emailInput" class="form-control" placeholder="vous@exemple.com">
            </div>

            <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="password" id="passwordInput" class="form-control" placeholder="8 caractères min.">
            </div>

            <div class="mb-4">
                <label class="form-label">Rôle</label>
                <select name="role" id="roleInput" class="form-select">
                    <option value="utilisateur">Utilisateur</option>
                    <option value="gestionnaire">Gestionnaire de réservations</option>
                    <option value="admin_batiments">Administrateur bâtiments</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Créer</button>
            <a href="index.php?controller=utilisateur&action=index" class="btn btn-outline-light">Annuler</a>
        </form>
    </div>
</div>

<script>
document.getElementById('formUtilisateurCreate').addEventListener('submit', function (e) {
    const nom = document.getElementById('nomInput').value.trim();
    const prenom = document.getElementById('prenomInput').value.trim();
    const email = document.getElementById('emailInput').value.trim();
    const password = document.getElementById('passwordInput').value;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const erreurs = [];

    if (nom === '') erreurs.push('Le nom est requis.');
    if (prenom === '') erreurs.push('Le prénom est requis.');
    if (email === '' || !emailRegex.test(email)) erreurs.push('Un email valide est requis.');
    if (password.length < 8) erreurs.push('Le mot de passe doit contenir au moins 8 caractères.');

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
