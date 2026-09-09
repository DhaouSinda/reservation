<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    body { font-family: Helvetica, Arial, sans-serif; color: #2f3e36; font-size: 12px; }
    .header { background-color: #4f6f5e; color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
    .header h1 { margin: 0; font-size: 22px; }
    .header p { margin: 4px 0 0; font-size: 12px; color: #e2ece6; }
    table.stats-row { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
    table.stats-row td { width: 20%; padding: 4px; }
    .stat-box { border: 1px solid #d8ddd8; border-radius: 6px; padding: 10px; text-align: center; }
    .stat-box .value { font-size: 18px; font-weight: bold; color: #4f6f5e; }
    .stat-box .label { font-size: 9px; color: #6b7a72; }
    .section-title { font-size: 14px; font-weight: bold; color: #4f6f5e; border-bottom: 2px solid #a4c3a2; padding-bottom: 4px; margin: 20px 0 10px; }
    .insight-box { background-color: #f6f7f2; border-left: 4px solid #6b9080; padding: 8px 12px; margin-bottom: 6px; font-size: 11px; }
    table.data { width: 100%; border-collapse: collapse; font-size: 10px; }
    table.data th { background-color: #4f6f5e; color: white; padding: 6px; text-align: left; }
    table.data td { padding: 6px; border-bottom: 1px solid #e2e6e1; }
    .barre-container { background-color: #e2e6e1; border-radius: 4px; height: 12px; width: 100%; }
    .barre-fill { background-color: #6b9080; height: 12px; border-radius: 4px; }
    .footer { margin-top: 20px; font-size: 9px; color: #6b7a72; text-align: center; }
</style>
</head>
<body>

<div class="header">
    <h1>BookIt — Rapport de réservations</h1>
    <p>Période du <?= htmlspecialchars($dateDebut) ?> au <?= htmlspecialchars($dateFin) ?> — Généré le <?= date('d/m/Y à H:i') ?></p>
</div>

<table class="stats-row">
<tr>
    <td><div class="stat-box"><div class="value"><?= $stats['total'] ?></div><div class="label">Total</div></div></td>
    <td><div class="stat-box"><div class="value"><?= $stats['par_statut']['validee'] ?></div><div class="label">Validées</div></div></td>
    <td><div class="stat-box"><div class="value"><?= $stats['par_statut']['refusee'] ?></div><div class="label">Refusées</div></div></td>
    <td><div class="stat-box"><div class="value"><?= $stats['par_statut']['en_attente'] ?></div><div class="label">En attente</div></div></td>
    <td><div class="stat-box"><div class="value"><?= $stats['taux_validation'] ?>%</div><div class="label">Taux validation</div></div></td>
</tr>
</table>

<div class="section-title">Analyse de la période</div>

<?php if ($stats['salle_top']): ?>
<div class="insight-box">
    Salle la plus demandée : <strong><?= htmlspecialchars($stats['salle_top']) ?></strong>
    (<?= $stats['salle_top_count'] ?> réservation<?= $stats['salle_top_count'] > 1 ? 's' : '' ?>)
</div>
<?php endif; ?>

<?php if ($stats['jour_top']): ?>
<div class="insight-box">
    Journée la plus chargée : <strong><?= date('d/m/Y', strtotime($stats['jour_top'])) ?></strong>
    (<?= $stats['jour_top_count'] ?> réservation<?= $stats['jour_top_count'] > 1 ? 's' : '' ?>)
</div>
<?php endif; ?>

<div class="insight-box">
    Durée moyenne d'une réservation : <strong><?= number_format($stats['duree_moyenne_minutes'] / 60, 1) ?> heure(s)</strong>
</div>

<?php if (!empty($stats['par_salle'])): ?>
<div class="section-title">Répartition par salle</div>
<table class="data">
<?php
$maxCount = max($stats['par_salle']);
foreach ($stats['par_salle'] as $salleNom => $count):
    $pourcentage = $maxCount > 0 ? round(($count / $maxCount) * 100) : 0;
?>
<tr>
    <td style="width: 25%;"><?= htmlspecialchars($salleNom) ?></td>
    <td style="width: 60%;">
        <div class="barre-container"><div class="barre-fill" style="width: <?= $pourcentage ?>%;"></div></div>
    </td>
    <td style="width: 15%; text-align: right;"><?= $count ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>

<div class="section-title">Détail des réservations (<?= count($reservations) ?>)</div>
<?php if (empty($reservations)): ?>
    <p style="color: #6b7a72;">Aucune réservation sur cette période.</p>
<?php else: ?>
<table class="data">
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
    <td><?= htmlspecialchars($r['statut']) ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php endif; ?>

<div class="footer">Rapport généré automatiquement par BookIt — Système de réservation de salles</div>

</body>
</html>
