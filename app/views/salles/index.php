<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Salles</title>
</head>
<body>
    <h2>Liste des salles</h2>
    <a href="index.php?controller=salle&action=create">+ Ajouter une salle</a>

    <table border="1" cellpadding="8">
        <tr>
            <th>Nom</th>
            <th>Bâtiment</th>
            <th>Étage</th>
            <th>Capacité</th>
            <th>Équipements</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($salles as $s): ?>
        <tr>
            <td><?= htmlspecialchars($s['nom']) ?></td>
            <td><?= htmlspecialchars($s['batiment_nom']) ?></td>
            <td><?= htmlspecialchars($s['etage_numero']) ?></td>
            <td><?= (int)$s['capacite'] ?></td>
            <td><?= htmlspecialchars($s['equipements']) ?></td>
            <td><?= htmlspecialchars($s['statut']) ?></td>
            <td>
                <a href="index.php?controller=salle&action=edit&id=<?= $s['id'] ?>">Modifier</a>
                |
                <a href="index.php?controller=salle&action=delete&id=<?= $s['id'] ?>"
                   onclick="return confirm('Supprimer cette salle ?');">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <p><a href="index.php?controller=dashboard&action=index">Retour au tableau de bord</a></p>
</body>
</html>