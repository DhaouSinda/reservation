<?php require __DIR__ . '/layout_header.php'; ?>

<div class="mb-4">
    <h2 class="page-title"><i class="fa-solid fa-gauge"></i> Dashboard</h2>
    <p class="page-subtitle">Vue d'ensemble de BookIt</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card-color stat-blue">
            <div class="stat-icon"><i class="fa-solid fa-building"></i></div>
            <div class="stat-value"><?= $totalBatiments ?></div>
            <div class="stat-label">Bâtiments</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card-color stat-purple">
            <div class="stat-icon"><i class="fa-solid fa-door-open"></i></div>
            <div class="stat-value"><?= $totalSalles ?></div>
            <div class="stat-label">Salles</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card-color stat-green">
            <div class="stat-icon"><i class="fa-solid fa-clipboard-list"></i></div>
            <div class="stat-value"><?= $totalReservations ?></div>
            <div class="stat-label">Réservations totales</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card-color stat-orange">
            <div class="stat-icon"><i class="fa-solid fa-hourglass-half"></i></div>
            <div class="stat-value"><?= $enAttente ?></div>
            <div class="stat-label">En attente de validation</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <a href="index.php?controller=batiment&action=index" class="card p-4 text-decoration-none d-block h-100">
            <i class="fa-solid fa-building mb-2" style="color: var(--accent); font-size: 1.6rem;"></i>
            <h5 style="color: var(--text);">Gérer les bâtiments</h5>
            <p class="mb-0" style="color: var(--muted); font-size: 0.9rem;">Bâtiments, étages et adresses</p>
        </a>
    </div>
    <div class="col-md-4">
        <a href="index.php?controller=salle&action=index" class="card p-4 text-decoration-none d-block h-100">
            <i class="fa-solid fa-door-open mb-2" style="color: var(--accent); font-size: 1.6rem;"></i>
            <h5 style="color: var(--text);">Gérer les salles</h5>
            <p class="mb-0" style="color: var(--muted); font-size: 0.9rem;">Capacité, équipements, statut</p>
        </a>
    </div>
    <div class="col-md-4">
        <a href="index.php?controller=reservation&action=index" class="card p-4 text-decoration-none d-block h-100">
            <i class="fa-solid fa-clipboard-list mb-2" style="color: var(--accent); font-size: 1.6rem;"></i>
            <h5 style="color: var(--text);">Gérer les réservations</h5>
            <p class="mb-0" style="color: var(--muted); font-size: 0.9rem;">Valider ou refuser les demandes</p>
        </a>
    </div>
</div>

<?php require __DIR__ . '/layout_footer.php'; ?>
