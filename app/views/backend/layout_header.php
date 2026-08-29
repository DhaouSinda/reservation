<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookIt — Administration</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/reservation-salles/public/css/backend.css">
</head>
<body>
<?php $currentController = $_GET['controller'] ?? ''; ?>
<div class="app-layout">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-building-columns"></i> BookIt
        </div>

        <nav class="sidebar-nav">
            <a href="index.php?controller=dashboard&action=index"
               class="sidebar-link <?= $currentController === 'dashboard' ? 'active' : '' ?>">
                <i class="fa-solid fa-gauge"></i> Dashboard
            </a>
            <a href="index.php?controller=batiment&action=index"
               class="sidebar-link <?= $currentController === 'batiment' ? 'active' : '' ?>">
                <i class="fa-solid fa-building"></i> Bâtiments
            </a>
            <a href="index.php?controller=salle&action=index"
               class="sidebar-link <?= $currentController === 'salle' ? 'active' : '' ?>">
                <i class="fa-solid fa-door-open"></i> Salles
            </a>
            <a href="index.php?controller=reservation&action=index"
               class="sidebar-link <?= $currentController === 'reservation' ? 'active' : '' ?>">
                <i class="fa-solid fa-clipboard-list"></i> Réservations
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="index.php?controller=home&action=index" class="sidebar-link">
                <i class="fa-solid fa-house"></i> Site public
            </a>
            <a href="index.php?controller=auth&action=logout" class="sidebar-link logout">
                <i class="fa-solid fa-right-from-bracket"></i> Déconnexion
            </a>
        </div>
    </aside>

    <div class="main-content">
        <header class="topbar">
            <button class="sidebar-toggle d-lg-none" onclick="document.querySelector('.sidebar').classList.toggle('open')">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="topbar-user">
                <i class="fa-solid fa-circle-user"></i>
                <?= htmlspecialchars($_SESSION['user_nom'] ?? '') ?>
                <span class="topbar-role"><?= htmlspecialchars($_SESSION['user_role'] ?? '') ?></span>
            </div>
        </header>

        <div class="content-body">
