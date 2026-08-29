<?php
require_once __DIR__ . '/Model.php';

class Reservation extends Model
{
    public function getAll(): array
    {
        $stmt = $this->pdo->query(
            "SELECT r.*, s.nom AS salle_nom, u.nom AS user_nom, u.prenom AS user_prenom
             FROM reservations r
             JOIN salles s ON r.salle_id = s.id
             JOIN utilisateurs u ON r.utilisateur_id = u.id
             ORDER BY r.date_debut DESC"
        );
        return $stmt->fetchAll();
    }

    public function getByUser(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT r.*, s.nom AS salle_nom
             FROM reservations r
             JOIN salles s ON r.salle_id = s.id
             WHERE r.utilisateur_id = :uid
             ORDER BY r.date_debut DESC"
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
}