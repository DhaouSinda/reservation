<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier la salle</title>
</head>
<body>
    <h2>Modifier la salle</h2>

    <form action="index.php?controller=salle&action=processEdit" method="POST">
        <input type="hidden" name="id" value="<?= $salle['id'] ?>">

        <label>Nom :</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($salle['nom']) ?>" required><br>

        <label>Capacité :</label>
        <input type="number" name="capacite" value="<?= (int)$salle['capacite'] ?>" min="1" required><br>

        <label>Équipements :</label>
        <input type="text" name="equipements" value="<?= htmlspecialchars($salle['equipements']) ?>"><br>

        <label>Localisation :</label>
        <input type="text" name="localisation" value="<?= htmlspecialchars($salle['localisation']) ?>"><br>

        <label>Statut :</label>
        <select name="statut">
            <option value="disponible" <?= $salle['statut'] === 'disponible' ? 'selected' : '' ?>>Disponible</option>
            <option value="maintenance" <?= $salle['statut'] === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
            <option value="indisponible" <?= $salle['statut'] === 'indisponible' ? 'selected' : '' ?>>Indisponible</option>
        </select><br>

        <button type="submit">Enregistrer</button>
    </form>

    <p><a href="index.php?controller=salle&action=index">Retour à la liste</a></p>
</body>
</html>