<?php
require_once __DIR__ . '/Model.php';

class Salle extends Model
{
    public function getAll(array $filtres = []): array
    {
        $sql = "SELECT s.*, e.numero AS etage_numero, b.nom AS batiment_nom, b.id AS batiment_id
                FROM salles s
                JOIN etages e ON s.etage_id = e.id
                JOIN batiments b ON e.batiment_id = b.id
                WHERE 1=1";
        $params = [];

        if (!empty($filtres['recherche'])) {
            $sql .= " AND s.nom LIKE :recherche";
            $params['recherche'] = '%' . $filtres['recherche'] . '%';
        }
        if (!empty($filtres['batiment_id'])) {
            $sql .= " AND b.id = :batiment_id";
            $params['batiment_id'] = (int)$filtres['batiment_id'];
        }
        if (!empty($filtres['statut'])) {
            $sql .= " AND s.statut = :statut";
            $params['statut'] = $filtres['statut'];
        }

        $colonnesTri = [
            'nom' => 's.nom',
            'batiment' => 'b.nom',
            'capacite' => 's.capacite',
            'statut' => 's.statut',
        ];
        $triCol = $colonnesTri[$filtres['tri'] ?? ''] ?? 'b.nom';
        $ordre = strtoupper($filtres['ordre'] ?? 'ASC') === 'DESC' ? 'DESC' : 'ASC';

        $sql .= " ORDER BY $triCol $ordre";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM salles WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create(int $etageId, string $nom, int $capacite, string $equipements, string $localisation): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO salles (etage_id, nom, capacite, equipements, localisation)
             VALUES (:etage_id, :nom, :capacite, :equipements, :localisation)"
        );
        return $stmt->execute([
            'etage_id' => $etageId,
            'nom' => $nom,
            'capacite' => $capacite,
            'equipements' => $equipements,
            'localisation' => $localisation,
        ]);
    }

    public function update(int $id, string $nom, int $capacite, string $equipements, string $localisation, string $statut): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE salles SET nom = :nom, capacite = :capacite, equipements = :equipements,
             localisation = :localisation, statut = :statut WHERE id = :id"
        );
        return $stmt->execute([
            'nom' => $nom,
            'capacite' => $capacite,
            'equipements' => $equipements,
            'localisation' => $localisation,
            'statut' => $statut,
            'id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM salles WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function getAvailable(): array
    {
        $stmt = $this->pdo->query(
            "SELECT s.*, e.numero AS etage_numero, b.nom AS batiment_nom
             FROM salles s
             JOIN etages e ON s.etage_id = e.id
             JOIN batiments b ON e.batiment_id = b.id
             WHERE s.statut = 'disponible'
             ORDER BY b.nom, e.numero, s.nom"
        );
        return $stmt->fetchAll();
    }
}