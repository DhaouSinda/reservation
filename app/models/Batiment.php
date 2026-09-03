<?php
require_once __DIR__ . '/Model.php';

class Batiment extends Model
{
    public function getAll(string $recherche = '', string $tri = 'nom', string $ordre = 'ASC'): array
    {
        $colonnesAutorisees = ['nom', 'adresse'];
        if (!in_array($tri, $colonnesAutorisees, true)) {
            $tri = 'nom';
        }
        $ordre = strtoupper($ordre) === 'DESC' ? 'DESC' : 'ASC';

        if ($recherche !== '') {
            $stmt = $this->pdo->prepare("SELECT * FROM batiments WHERE nom LIKE :recherche ORDER BY $tri $ordre");
            $stmt->execute(['recherche' => '%' . $recherche . '%']);
        } else {
            $stmt = $this->pdo->query("SELECT * FROM batiments ORDER BY $tri $ordre");
        }
        return $stmt->fetchAll();
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM batiments WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getAllWithEtages(): array
    {
        $stmt = $this->pdo->query(
            "SELECT b.id AS batiment_id, b.nom AS batiment_nom, e.id AS etage_id, e.numero
             FROM batiments b
             JOIN etages e ON e.batiment_id = b.id
             ORDER BY b.nom, e.numero"
        );
        return $stmt->fetchAll();
    }

    public function create(string $nom, string $adresse): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO batiments (nom, adresse) VALUES (:nom, :adresse)");
        return $stmt->execute(['nom' => $nom, 'adresse' => $adresse]);
    }

    public function getLastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }

    public function update(int $id, string $nom, string $adresse): bool
    {
        $stmt = $this->pdo->prepare("UPDATE batiments SET nom = :nom, adresse = :adresse WHERE id = :id");
        return $stmt->execute(['nom' => $nom, 'adresse' => $adresse, 'id' => $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM batiments WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}