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

                <form action="index.php?controller=auth&action=processRegister" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prénom</label>
                            <input type="text" name="prenom" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="vous@exemple.com" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">S'inscrire</button>
                </form>

                <p class="text-center mt-4 mb-0" style="color: var(--muted);">
                    Déjà un compte ? <a href="index.php?controller=auth&action=login">Connectez-vous</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout_footer.php'; ?>
