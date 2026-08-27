<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un bâtiment</title>
</head>
<body>
    <h2>Ajouter un bâtiment</h2>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="index.php?controller=batiment&action=processCreate" method="POST">
        <label>Nom :</label>
        <input type="text" name="nom" required><br>

        <label>Adresse :</label>
        <input type="text" name="adresse"><br>

        <label>Étages (numéros séparés par des virgules) :</label>
        <input type="text" name="etages" placeholder="Ex: 0,1,2,3"><br>

        <button type="submit">Ajouter</button>
    </form>

    <p><a href="index.php?controller=batiment&action=index">Retour à la liste</a></p>
</body>
</html>