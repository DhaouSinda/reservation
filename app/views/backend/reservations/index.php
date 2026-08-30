<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="mb-4">
    <h2 class="page-title"><i class="fa-solid fa-clipboard-list"></i> Gestion des réservations</h2>
    <p class="page-subtitle">Validez, refusez ou consultez toutes les demandes</p>
</div>

<div class="card p-3 mb-4">
    <form action="index.php" method="GET" class="row g-2 align-items-end">
        <input type="hidden" name="controller" value="reservation">
        <input type="hidden" name="action" value="index">
        <div class="col-md-3">
            <label class="form-label">Utilisateur</label>
            <input type="text" name="utilisateur" class="form-control" placeholder="Nom ou prénom..." value="<?= htmlspecialchars($filtres['utilisateur']) ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label">Salle</label>
            <select name="salle_id" class="form-select">
                <option value="">Toutes</option>
                <?php foreach ($salles as $s): ?>
                    <option value="<?= $s['id'] ?>" <?= (string)$filtres['salle_id'] === (string)$s['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($s['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Statut</label>
            <select name="statut" class="form-select">
                <option value="">Tous</option>
                <option value="en_attente" <?= $filtres['statut'] === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                <option value="validee" <?= $filtres['statut'] === 'validee' ? 'selected' : '' ?>>Validée</option>
                <option value="refusee" <?= $filtres['statut'] === 'refusee' ? 'selected' : '' ?>>Refusée</option>
                <option value="annulee" <?= $filtres['statut'] === 'annulee' ? 'selected' : '' ?>>Annulée</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Du</label>
            <input type="date" name="date_debut" class="form-control" value="<?= htmlspecialchars($filtres['date_debut']) ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label">Au</label>
            <input type="date" name="date_fin" class="form-control" value="<?= htmlspecialchars($filtres['date_fin']) ?>">
        </div>
        <div class="col-md-1 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill"><i class="fa-solid fa-filter"></i></button>
        </div>
    </form>
    <?php if (array_filter($filtres)): ?>
        <div class="mt-2">
            <a href="index.php?controller=reservation&action=index" style="font-size: 0.85rem;">
                <i class="fa-solid fa-xmark"></i> Réinitialiser les filtres
            </a>
        </div>
    <?php endif; ?>
</div>

<?php if (empty($reservations)): ?>
    <div class="card">
        <div class="empty-state">
            <i class="fa-solid fa-calendar-xmark"></i>
            <p>Aucune réservation ne correspond à ces critères.</p>
        </div>
    </div>
<?php else: ?>
<div class="table-responsive">
    <table class="table align-middle">
        <thead>
        <tr>
            <th>Utilisateur</th>
            <th>Salle</th>
            <th>Début</th>
            <th>Fin</th>
            <th>Motif</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($reservations as $r): ?>
        <tr>
            <td><?= htmlspecialchars($r['user_prenom'] . ' ' . $r['user_nom']) ?></td>
            <td><?= htmlspecialchars($r['salle_nom']) ?></td>
            <td><?= htmlspecialchars($r['date_debut']) ?></td>
            <td><?= htmlspecialchars($r['date_fin']) ?></td>
            <td><?= htmlspecialchars($r['motif']) ?></td>
            <td><span class="badge-statut badge-<?= htmlspecialchars($r['statut']) ?>"><?= htmlspecialchars($r['statut']) ?></span></td>
            <td>
                <?php if ($r['statut'] === 'en_attente'): ?>
                    <a href="index.php?controller=reservation&action=approve&id=<?= $r['id'] ?>" class="btn btn-sm btn-success-soft btn-sm-action">
                        <i class="fa-solid fa-check"></i> Valider
                    </a>
                    <a href="index.php?controller=reservation&action=reject&id=<?= $r['id'] ?>" class="btn btn-sm btn-danger-soft btn-sm-action">
                        <i class="fa-solid fa-xmark"></i> Refuser
                    </a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php require __DIR__ . '/../layout_footer.php'; ?>
