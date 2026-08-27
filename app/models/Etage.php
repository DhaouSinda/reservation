<?php
require_once __DIR__ . '/Model.php';

class Etage extends Model
{
    public function getByBatiment(int $batimentId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM etages WHERE batiment_id = :batiment_id ORDER BY numero");
        $stmt->execute(['batiment_id' => $batimentId]);
        return $stmt->fetchAll();
    }

    public function create(int $batimentId, int $numero): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO etages (batiment_id, numero) VALUES (:batiment_id, :numero)");
        return $stmt->execute(['batiment_id' => $batimentId, 'numero' => $numero]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM etages WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}