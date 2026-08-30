<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="page-title"><i class="fa-solid fa-file-lines"></i> Rapport de réservations</h2>
        <p class="page-subtitle">Consultez les réservations sur une période donnée</p>
    </div>
    <a href="index.php?controller=statistique&action=index" class="btn btn-outline-light">
        <i class="fa-solid fa-chart-line"></i> Voir les statistiques
    </a>
</div>

<div class="card p-4 mb-4">
    <form action="index.php" method="GET" class="row g-3 align-items-end">
        <input type="hidden" name="controller" value="statistique">
        <input type="hidden" name="action" value="rapport">
        <div class="col-md-4">
            <label class="form-label">Du</label>
            <input type="date" name="date_debut" class="form-control" value="<?= htmlspecialchars($dateDebut) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Au</label>
            <input type="date" name="date_fin" class="form-control" value="<?= htmlspecialchars($dateFin) ?>">
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill">
                <i class="fa-solid fa-filter"></i> Filtrer
            </button>
            <a href="index.php?controller=statistique&action=exportCsv&date_debut=<?= urlencode($dateDebut) ?>&date_fin=<?= urlencode($dateFin) ?>"
               class="btn btn-outline-light" title="Exporter en CSV">
                <i class="fa-solid fa-file-csv"></i>
            </a>
        </div>
    </form>
</div>

<?php
$totalPeriode = count($reservations);
$valideesPeriode = count(array_filter($reservations, fn($r) => $r['statut'] === 'validee'));
?>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card-color stat-blue">
            <div class="stat-icon"><i class="fa-solid fa-clipboard-list"></i></div>
            <div class="stat-value"><?= $totalPeriode ?></div>
            <div class="stat-label">Réservations sur la période</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card-color stat-green">
            <div class="stat-icon"><i class="fa-solid fa-check"></i></div>
            <div class="stat-value"><?= $valideesPeriode ?></div>
            <div class="stat-label">Validées</div>
        </div>
    </div>
</div>

<?php if (empty($reservations)): ?>
    <div class="card">
        <div class="empty-state">
            <i class="fa-solid fa-calendar-xmark"></i>
            <p>Aucune réservation sur cette période.</p>
        </div>
    </div>
<?php else: ?>
<div class="table-responsive">
    <table class="table align-middle">
        <thead>
        <tr>
            <th>Utilisateur</th>
            <th>Salle</th>
            <th>Bâtiment</th>
            <th>Début</th>
            <th>Fin</th>
            <th>Statut</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($reservations as $r): ?>
        <tr>
            <td><?= htmlspecialchars($r['user_prenom'] . ' ' . $r['user_nom']) ?></td>
            <td><?= htmlspecialchars($r['salle_nom']) ?></td>
            <td><?= htmlspecialchars($r['batiment_nom']) ?></td>
            <td><?= htmlspecialchars($r['date_debut']) ?></td>
            <td><?= htmlspecialchars($r['date_fin']) ?></td>
            <td><span class="badge-statut badge-<?= htmlspecialchars($r['statut']) ?>"><?= htmlspecialchars($r['statut']) ?></span></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php require __DIR__ . '/../layout_footer.php'; ?>
