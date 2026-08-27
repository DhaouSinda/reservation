<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une salle</title>
</head>
<body>
    <h2>Ajouter une salle</h2>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if (empty($etagesDisponibles)): ?>
        <p style="color:red;">Aucun étage disponible. Créez d'abord un bâtiment et un étage.</p>
    <?php else: ?>
    <form action="index.php?controller=salle&action=processCreate" method="POST">
        <label>Étage :</label>
        <select name="etage_id" required>
            <?php foreach ($etagesDisponibles as $e): ?>
                <option value="<?= $e['etage_id'] ?>">
                    <?= htmlspecialchars($e['batiment_nom']) ?> - Étage <?= $e['numero'] ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label>Nom de la salle :</label>
        <input type="text" name="nom" required><br>

        <label>Capacité :</label>
        <input type="number" name="capacite" min="1" required><br>

        <label>Équipements :</label>
        <input type="text" name="equipements" placeholder="Ex: Vidéoprojecteur, Wifi"><br>

        <label>Localisation :</label>
        <input type="text" name="localisation" placeholder="Ex: Aile B"><br>

        <button type="submit">Ajouter</button>
    </form>
    <?php endif; ?>

    <p><a href="index.php?controller=salle&action=index">Retour à la liste</a></p>
</body>
</html>