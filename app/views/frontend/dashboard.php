<?php require __DIR__ . '/layout_header.php'; ?>

<?php if (($_GET['error'] ?? '') === 'access_denied'): ?>
    <div class="alert alert-danger mb-4">
        <i class="fa-solid fa-lock me-1"></i> Vous n'avez pas accès à cette section avec votre rôle actuel.
    </div>
<?php endif; ?>

<div class="welcome-banner mb-4">
    <h2><i class="fa-solid fa-hand-wave"></i> Bienvenue, <?= htmlspecialchars($_SESSION['user_nom']) ?> !</h2>
    <p>Voici votre espace de gestion des réservations de salles.</p>
    <span class="role-chip"><?= htmlspecialchars($_SESSION['user_role']) ?></span>
</div>

<div class="row g-4">
    <?php if ($_SESSION['user_role'] === 'utilisateur'): ?>
        <div class="col-md-6 col-lg-4">
            <a href="index.php?controller=reservation&action=create" class="dashboard-tile">
                <i class="fa-solid fa-calendar-plus"></i>
                <h5>Réserver une salle</h5>
                <p>Trouvez une salle disponible</p>
            </a>
        </div>
        <div class="col-md-6 col-lg-4">
            <a href="index.php?controller=reservation&action=mine" class="dashboard-tile">
                <i class="fa-solid fa-calendar-check"></i>
                <h5>Mes réservations</h5>
                <p>Suivez vos demandes</p>
            </a>
        </div>
    <?php endif; ?>

    <?php if ($_SESSION['user_role'] === 'admin_batiments'): ?>
        <div class="col-md-6 col-lg-4">
            <a href="index.php?controller=batiment&action=index" class="dashboard-tile">
                <i class="fa-solid fa-building"></i>
                <h5>Bâtiments</h5>
                <p>Gérer bâtiments et étages</p>
            </a>
        </div>
        <div class="col-md-6 col-lg-4">
            <a href="index.php?controller=salle&action=index" class="dashboard-tile">
                <i class="fa-solid fa-door-open"></i>
                <h5>Salles</h5>
                <p>Gérer les salles et leur statut</p>
            </a>
        </div>
    <?php endif; ?>

    <?php if ($_SESSION['user_role'] === 'gestionnaire'): ?>
        <div class="col-md-6 col-lg-4">
            <a href="index.php?controller=reservation&action=index" class="dashboard-tile">
                <i class="fa-solid fa-clipboard-list"></i>
                <h5>Réservations</h5>
                <p>Valider ou refuser les demandes</p>
            </a>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/layout_footer.php'; ?>
