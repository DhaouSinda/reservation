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

                <form action="index.php?controller=auth&action=processLogin" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="vous@exemple.com" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Se connecter</button>
                </form>

                <p class="text-center mt-4 mb-0" style="color: var(--muted);">
                    Pas de compte ? <a href="index.php?controller=auth&action=register">Inscrivez-vous</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout_footer.php'; ?>
