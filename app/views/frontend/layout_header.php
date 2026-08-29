<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookIt — Réservation de salles</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/reservation-salles/public/css/frontend.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php?controller=home&action=index">
            <i class="fa-solid fa-building-columns"></i> BookIt
        </a>
        <div class="d-flex align-items-center">
            <?php if (!empty($_SESSION['user_id'])): ?>
                <a class="nav-link-pill me-2" href="index.php?controller=reservation&action=mine">
                    <i class="fa-solid fa-calendar-check"></i> Mes réservations
                </a>
                <a class="nav-link-pill me-2" href="index.php?controller=dashboard&action=index">
                    <i class="fa-solid fa-gauge"></i> Mon espace
                </a>
                <span class="navbar-text text-white-50 me-3 d-none d-sm-inline">
                    <?= htmlspecialchars($_SESSION['user_nom']) ?>
                </span>
                <a class="btn btn-outline-light btn-sm" href="index.php?controller=auth&action=logout">
                    <i class="fa-solid fa-right-from-bracket"></i> Déconnexion
                </a>
            <?php else: ?>
                <a class="nav-link-pill me-2" href="index.php?controller=auth&action=login">
                    <i class="fa-solid fa-right-to-bracket"></i> Connexion
                </a>
                <a class="btn btn-light btn-sm fw-semibold" href="index.php?controller=auth&action=register">
                    <i class="fa-solid fa-user-plus"></i> S'inscrire
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<div class="container my-4">
