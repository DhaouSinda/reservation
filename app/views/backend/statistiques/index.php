<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="page-title"><i class="fa-solid fa-chart-line"></i> Statistiques d'utilisation</h2>
        <p class="page-subtitle">Utilisation des salles et répartition des réservations</p>
    </div>
    <a href="index.php?controller=statistique&action=rapport" class="btn btn-primary">
        <i class="fa-solid fa-file-lines"></i> Générer un rapport
    </a>
</div>

<?php
$colorMap = ['en_attente' => '#fbbf24', 'validee' => '#34d399', 'refusee' => '#f87171', 'annulee' => '#9ca3af'];
$orderedColors = array_map(fn($s) => $colorMap[$s['statut']] ?? '#5b8def', $statsStatuts);
?>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card p-4 h-100">
            <h5 class="mb-3" style="color: var(--text);"><i class="fa-solid fa-door-open me-1"></i> Réservations par salle</h5>
            <canvas id="chartSalles" height="110"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card p-4 h-100">
            <h5 class="mb-3" style="color: var(--text);"><i class="fa-solid fa-chart-pie me-1"></i> Répartition par statut</h5>
            <canvas id="chartStatuts"></canvas>
        </div>
    </div>
</div>

<?php if (empty($statsSalles)): ?>
    <div class="card">
        <div class="empty-state">
            <i class="fa-solid fa-chart-simple"></i>
            <p>Aucune donnée pour le moment.</p>
        </div>
    </div>
<?php else: ?>
<div class="table-responsive">
    <table class="table align-middle">
        <thead>
        <tr>
            <th>Salle</th>
            <th>Bâtiment</th>
            <th>Réservations</th>
            <th>Heures totales réservées</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($statsSalles as $s): ?>
        <tr>
            <td><?= htmlspecialchars($s['salle_nom']) ?></td>
            <td><?= htmlspecialchars($s['batiment_nom']) ?></td>
            <td><?= (int)$s['nombre_reservations'] ?></td>
            <td><?= number_format($s['minutes_totales'] / 60, 1) ?> h</td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
const salleLabels = <?= json_encode(array_column($statsSalles, 'salle_nom')) ?>;
const salleData = <?= json_encode(array_map('intval', array_column($statsSalles, 'nombre_reservations'))) ?>;

new Chart(document.getElementById('chartSalles'), {
    type: 'bar',
    data: {
        labels: salleLabels,
        datasets: [{
            label: 'Réservations',
            data: salleData,
            backgroundColor: '#5b8def',
            borderRadius: 6,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: {
            x: { ticks: { color: '#8b93a7' }, grid: { color: 'rgba(255,255,255,0.05)' } },
            y: { beginAtZero: true, ticks: { color: '#8b93a7', stepSize: 1 }, grid: { color: 'rgba(255,255,255,0.05)' } }
        }
    }
});

const statutLabels = <?= json_encode(array_column($statsStatuts, 'statut')) ?>;
const statutData = <?= json_encode(array_map('intval', array_column($statsStatuts, 'total'))) ?>;
const statutColors = <?= json_encode($orderedColors) ?>;

new Chart(document.getElementById('chartStatuts'), {
    type: 'doughnut',
    data: {
        labels: statutLabels,
        datasets: [{
            data: statutData,
            backgroundColor: statutColors,
            borderWidth: 0,
        }]
    },
    options: {
        plugins: { legend: { position: 'bottom', labels: { color: '#e4e8f1' } } }
    }
});
</script>

<?php require __DIR__ . '/../layout_footer.php'; ?>
