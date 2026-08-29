<?php require __DIR__ . '/layout_header.php'; ?>

<div class="hero-section text-center">
    <span class="hero-badge"><i class="fa-solid fa-sparkles"></i> Simple, rapide, sans conflit</span>
    <h1 class="hero-title">La réservation de salles,<br>enfin simple avec <span class="brand-highlight">BookIt</span></h1>
    <p class="hero-subtitle">Consultez la disponibilité en temps réel, réservez en quelques clics et laissez BookIt gérer les conflits d'horaires à votre place.</p>

    <?php if (empty($_SESSION['user_id'])): ?>
        <div class="d-flex justify-content-center flex-wrap gap-3 mt-4">
            <a href="index.php?controller=auth&action=register" class="btn btn-success btn-lg">
                <i class="fa-solid fa-rocket"></i> Commencer gratuitement
            </a>
            <a href="index.php?controller=auth&action=login" class="btn btn-outline-success btn-lg">
                <i class="fa-solid fa-right-to-bracket"></i> Se connecter
            </a>
        </div>
    <?php else: ?>
        <div class="mt-4">
            <a href="index.php?controller=dashboard&action=index" class="btn btn-success btn-lg">
                <i class="fa-solid fa-gauge"></i> Accéder à mon espace
            </a>
        </div>
    <?php endif; ?>
</div>

<div class="stats-banner my-5">
    <div class="stat-item">
        <div class="stat-number"><i class="fa-solid fa-building"></i></div>
        <div class="stat-text">Multi-bâtiments</div>
    </div>
    <div class="stat-item">
        <div class="stat-number"><i class="fa-solid fa-bolt"></i></div>
        <div class="stat-text">Réservation instantanée</div>
    </div>
    <div class="stat-item">
        <div class="stat-number"><i class="fa-solid fa-shield-halved"></i></div>
        <div class="stat-text">Zéro double-réservation</div>
    </div>
    <div class="stat-item">
        <div class="stat-number"><i class="fa-solid fa-mobile-screen"></i></div>
        <div class="stat-text">100% responsive</div>
    </div>
</div>

<div class="text-center mb-4">
    <h2 class="section-title">Pourquoi BookIt ?</h2>
    <p class="section-subtitle">Tout ce qu'il faut pour gérer vos salles de réunion, sans friction</p>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="feature-card">
            <div class="feature-icon"><i class="fa-solid fa-building"></i></div>
            <h5>Bâtiments &amp; Salles</h5>
            <p>Consultez tous les bâtiments, étages et salles avec leurs équipements et capacités.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="feature-card">
            <div class="feature-icon"><i class="fa-solid fa-calendar-check"></i></div>
            <h5>Réservation instantanée</h5>
            <p>Choisissez un créneau libre et réservez en quelques clics, sans conflit d'horaire possible.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="feature-card">
            <div class="feature-icon"><i class="fa-solid fa-clipboard-list"></i></div>
            <h5>Suivi en temps réel</h5>
            <p>Consultez l'historique de vos réservations et leur statut de validation à tout moment.</p>
        </div>
    </div>
</div>

<div class="how-it-works mb-5">
    <div class="text-center mb-5">
        <h2 class="section-title">Comment ça marche</h2>
        <p class="section-subtitle">Trois étapes, et votre salle est réservée</p>
    </div>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="step-card">
                <div class="step-number">1</div>
                <h5>Créez votre compte</h5>
                <p>Inscription rapide avec votre email professionnel.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="step-card">
                <div class="step-number">2</div>
                <h5>Choisissez une salle</h5>
                <p>Filtrez par bâtiment, capacité ou équipements disponibles.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="step-card">
                <div class="step-number">3</div>
                <h5>Réservez votre créneau</h5>
                <p>Confirmez la date et l'heure, recevez votre confirmation.</p>
            </div>
        </div>
    </div>
</div>

<?php if (empty($_SESSION['user_id'])): ?>
<div class="cta-banner text-center mb-5">
    <h3>Prêt à simplifier vos réservations ?</h3>
    <p>Rejoignez BookIt dès aujourd'hui, c'est gratuit.</p>
    <a href="index.php?controller=auth&action=register" class="btn btn-light btn-lg fw-semibold">
        <i class="fa-solid fa-rocket"></i> Créer mon compte
    </a>
</div>
<?php endif; ?>

<footer class="site-footer text-center">
    <p><i class="fa-solid fa-building-columns"></i> <strong>BookIt</strong> — Réservation de salles simplifiée</p>
</footer>

<?php require __DIR__ . '/layout_footer.php'; ?>
