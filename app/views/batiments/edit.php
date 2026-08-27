<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le bâtiment</title>
</head>
<body>
    <h2>Modifier le bâtiment</h2>

    <form action="index.php?controller=batiment&action=processEdit" method="POST">
        <input type="hidden" name="id" value="<?= $batiment['id'] ?>">

        <label>Nom :</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($batiment['nom']) ?>" required><br>

        <label>Adresse :</label>
        <input type="text" name="adresse" value="<?= htmlspecialchars($batiment['adresse']) ?>"><br>

        <button type="submit">Enregistrer</button>
    </form>

    <h3>Étages</h3>
    <ul>
        <?php foreach ($etages as $e): ?>
            <li>Étage <?= $e['numero'] ?></li>
        <?php endforeach; ?>
    </ul>

    <form action="index.php?controller=batiment&action=addEtage" method="POST">
        <input type="hidden" name="batiment_id" value="<?= $batiment['id'] ?>">
        <label>Numéro d'étage :</label>
        <input type="number" name="numero" required>
        <button type="submit">Ajouter un étage</button>
    </form>

    <p><a href="index.php?controller=batiment&action=index">Retour à la liste</a></p>
</body>
</html>