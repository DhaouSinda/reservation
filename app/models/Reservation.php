<?php
require_once __DIR__ . '/Model.php';

class Reservation extends Model
{
    public function getAll(array $filtres = []): array
    {
        $sql = "SELECT r.*, s.nom AS salle_nom, u.nom AS user_nom, u.prenom AS user_prenom
                FROM reservations r
                JOIN salles s ON r.salle_id = s.id
                JOIN utilisateurs u ON r.utilisateur_id = u.id
                WHERE 1=1";
        $params = [];

        if (!empty($filtres['salle_id'])) {
            $sql .= " AND r.salle_id = :salle_id";
            $params['salle_id'] = (int)$filtres['salle_id'];
        }
        if (!empty($filtres['statut'])) {
            $sql .= " AND r.statut = :statut";
            $params['statut'] = $filtres['statut'];
        }
        if (!empty($filtres['utilisateur'])) {
            $sql .= " AND (u.nom LIKE :utilisateur OR u.prenom LIKE :utilisateur)";
            $params['utilisateur'] = '%' . $filtres['utilisateur'] . '%';
        }
        if (!empty($filtres['date_debut'])) {
            $sql .= " AND r.date_debut >= :date_debut";
            $params['date_debut'] = $filtres['date_debut'] . ' 00:00:00';
        }
        if (!empty($filtres['date_fin'])) {
            $sql .= " AND r.date_debut <= :date_fin";
            $params['date_fin'] = $filtres['date_fin'] . ' 23:59:59';
        }

        $colonnesTri = [
            'date_debut' => 'r.date_debut',
            'salle' => 's.nom',
            'utilisateur' => 'u.nom',
            'statut' => 'r.statut',
        ];
        $triCol = $colonnesTri[$filtres['tri'] ?? ''] ?? 'r.date_debut';
        $ordre = strtoupper($filtres['ordre'] ?? 'DESC') === 'ASC' ? 'ASC' : 'DESC';

        $sql .= " ORDER BY $triCol $ordre";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getByUser(int $userId, string $tri = 'date_debut', string $ordre = 'DESC'): array
    {
        $colonnesTri = [
            'date_debut' => 'r.date_debut',
            'salle' => 's.nom',
            'statut' => 'r.statut',
        ];
        $triCol = $colonnesTri[$tri] ?? 'r.date_debut';
        $ordre = strtoupper($ordre) === 'ASC' ? 'ASC' : 'DESC';

        $stmt = $this->pdo->prepare(
            "SELECT r.*, s.nom AS salle_nom
             FROM reservations r
             JOIN salles s ON r.salle_id = s.id
             WHERE r.utilisateur_id = :uid
             ORDER BY $triCol $ordre"
        );
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll();
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM reservations WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function hasConflict(int $salleId, string $dateDebut, string $dateFin, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM reservations
                WHERE salle_id = :salle_id
                AND statut IN ('en_attente', 'validee')
                AND date_debut < :date_fin
                AND date_fin > :date_debut";

        $params = [
            'salle_id' => $salleId,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
        ];

        if ($excludeId !== null) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return (int)$stmt->fetchColumn() > 0;
    }

    public function create(int $salleId, int $userId, string $dateDebut, string $dateFin, string $motif, string $statut = 'en_attente'): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO reservations (salle_id, utilisateur_id, date_debut, date_fin, motif, statut)
             VALUES (:salle_id, :utilisateur_id, :date_debut, :date_fin, :motif, :statut)"
        );
        return $stmt->execute([
            'salle_id' => $salleId,
            'utilisateur_id' => $userId,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'motif' => $motif,
            'statut' => $statut,
        ]);
    }

    public function updateStatut(int $id, string $statut): bool
    {
        $stmt = $this->pdo->prepare("UPDATE reservations SET statut = :statut WHERE id = :id");
        return $stmt->execute(['statut' => $statut, 'id' => $id]);
    }

    public function cancel(int $id, int $userId): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE reservations SET statut = 'annulee' WHERE id = :id AND utilisateur_id = :uid"
        );
        return $stmt->execute(['id' => $id, 'uid' => $userId]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM reservations WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function getBySalleForCalendar(int $salleId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT id, date_debut, date_fin, statut FROM reservations
             WHERE salle_id = :salle_id AND statut IN ('en_attente', 'validee')"
        );
        $stmt->execute(['salle_id' => $salleId]);
        $rows = $stmt->fetchAll();

        $events = [];
        foreach ($rows as $r) {
            $events[] = [
                'title' => $r['statut'] === 'validee' ? 'Réservé' : 'En attente',
                'start' => str_replace(' ', 'T', $r['date_debut']),
                'end' => str_replace(' ', 'T', $r['date_fin']),
                'color' => $r['statut'] === 'validee' ? '#f87171' : '#fbbf24',
            ];
        }
        return $events;
    }

    public function reschedule(int $id, int $salleId, string $dateDebut, string $dateFin): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE reservations SET salle_id = :salle_id, date_debut = :date_debut, date_fin = :date_fin WHERE id = :id"
        );
        return $stmt->execute([
            'salle_id' => $salleId,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'id' => $id,
        ]);
    }

    public function getStatsBySalle(): array
    {
        $sql = "SELECT s.id, s.nom AS salle_nom, b.nom AS batiment_nom,
                       COUNT(r.id) AS nombre_reservations,
                       COALESCE(SUM(TIMESTAMPDIFF(MINUTE, r.date_debut, r.date_fin)), 0) AS minutes_totales
                FROM salles s
                JOIN etages e ON s.etage_id = e.id
                JOIN batiments b ON e.batiment_id = b.id
                LEFT JOIN reservations r ON r.salle_id = s.id AND r.statut IN ('validee', 'en_attente')
                GROUP BY s.id, s.nom, b.nom
                ORDER BY nombre_reservations DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getStatsByStatut(): array
    {
        $stmt = $this->pdo->query("SELECT statut, COUNT(*) AS total FROM reservations GROUP BY statut");
        return $stmt->fetchAll();
    }

    public function getByPeriod(string $dateDebut, string $dateFin): array
    {
        $sql = "SELECT r.*, s.nom AS salle_nom, b.nom AS batiment_nom, u.nom AS user_nom, u.prenom AS user_prenom
                FROM reservations r
                JOIN salles s ON r.salle_id = s.id
                JOIN etages e ON s.etage_id = e.id
                JOIN batiments b ON e.batiment_id = b.id
                JOIN utilisateurs u ON r.utilisateur_id = u.id
                WHERE r.date_debut >= :date_debut AND r.date_debut <= :date_fin
                ORDER BY r.date_debut ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['date_debut' => $dateDebut, 'date_fin' => $dateFin]);
        return $stmt->fetchAll();
    }
}