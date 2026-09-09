<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="text-center mb-4">
            <i class="fa-solid fa-building-columns fa-2x" style="color: var(--sage);"></i>
        </div>
        <div class="card">
            <div class="card-body p-4">
                <h3 class="page-title text-center mb-1"><i class="fa-solid fa-user-plus"></i> Inscription</h3>
                <p class="page-subtitle text-center mb-4">Créez votre compte pour réserver une salle</p>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation me-1"></i> <?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <div id="jsErrors" class="alert alert-danger" style="display:none;"></div>

                <form id="formRegister" action="index.php?controller=auth&action=processRegister" method="POST" novalidate>
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
                    <div class="mb-4">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password" id="passwordInput" class="form-control" placeholder="•••••••• (8 caractères min.)">
                    </div>
                    <button type="submit" class="btn btn-success w-100">S'inscrire</button>
                </form>

                <script>
                document.getElementById('formRegister').addEventListener('submit', function (e) {
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
                    } else {
                        box.style.display = 'none';
                    }
                });
                </script>

                <p class="text-center mt-4 mb-0" style="color: var(--muted);">
                    Déjà un compte ? <a href="index.php?controller=auth&action=login">Connectez-vous</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout_footer.php'; ?>
