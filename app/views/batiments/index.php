<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bâtiments</title>
</head>
<body>
    <h2>Liste des bâtiments</h2>
    <a href="index.php?controller=batiment&action=create">+ Ajouter un bâtiment</a>

    <table border="1" cellpadding="8">
        <tr>
            <th>Nom</th>
            <th>Adresse</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($batiments as $b): ?>
        <tr>
            <td><?= htmlspecialchars($b['nom']) ?></td>
            <td><?= htmlspecialchars($b['adresse']) ?></td>
            <td>
                <a href="index.php?controller=batiment&action=edit&id=<?= $b['id'] ?>">Modifier</a>
                |
                <a href="index.php?controller=batiment&action=delete&id=<?= $b['id'] ?>"
                   onclick="return confirm('Supprimer ce bâtiment ?');">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <p><a href="index.php?controller=dashboard&action=index">Retour au tableau de bord</a></p>
</body>
</html>