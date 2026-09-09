<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
        <div class="text-center mb-4">
            <i class="fa-solid fa-building-columns fa-2x" style="color: var(--sage);"></i>
        </div>
        <div class="card">
            <div class="card-body p-4">
                <h3 class="page-title text-center mb-1"><i class="fa-solid fa-right-to-bracket"></i> Connexion</h3>
                <p class="page-subtitle text-center mb-4">Accédez à votre espace de réservation</p>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation me-1"></i> <?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <div id="jsErrors" class="alert alert-danger" style="display:none;"></div>

                <form id="formLogin" action="index.php?controller=auth&action=processLogin" method="POST" novalidate>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text" name="email" id="emailInput" class="form-control" placeholder="vous@exemple.com">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password" id="passwordInput" class="form-control" placeholder="••••••••">
                    </div>
                    <button type="submit" class="btn btn-success w-100">Se connecter</button>
                </form>

                <script>
                document.getElementById('formLogin').addEventListener('submit', function (e) {
                    const email = document.getElementById('emailInput').value.trim();
                    const password = document.getElementById('passwordInput').value;
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    const erreurs = [];

                    if (email === '' || !emailRegex.test(email)) erreurs.push('Un email valide est requis.');
                    if (password === '') erreurs.push('Le mot de passe est requis.');

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
                    Pas de compte ? <a href="index.php?controller=auth&action=register">Inscrivez-vous</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout_footer.php'; ?>
