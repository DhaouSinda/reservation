<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Réservation de Salles</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/reservation-salles/public/css/backend.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="index.php?controller=dashboard&action=index">
            <i class="fa-solid fa-gauge"></i> Administration
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNav">
            <div class="navbar-nav me-auto gap-1">
                <a class="nav-link" href="index.php?controller=batiment&action=index">
                    <i class="fa-solid fa-building"></i> Bâtiments
                </a>
                <a class="nav-link" href="index.php?controller=salle&action=index">
                    <i class="fa-solid fa-door-open"></i> Salles
                </a>
                <a class="nav-link" href="index.php?controller=reservation&action=index">
                    <i class="fa-solid fa-clipboard-list"></i> Réservations
                </a>
            </div>
            <div class="d-flex align-items-center">
                <span class="navbar-text text-white-50 me-3 d-none d-sm-inline">
                    <?= htmlspecialchars($_SESSION['user_nom'] ?? '') ?>
                </span>
                <a class="btn btn-outline-light btn-sm" href="index.php?controller=auth&action=logout">
                    <i class="fa-solid fa-right-from-bracket"></i> Déconnexion
                </a>
            </div>
        </div>
    </div>
</nav>
<div class="container-fluid px-4 my-4">
