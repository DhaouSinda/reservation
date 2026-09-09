<?php
require_once __DIR__ . '/Model.php';

class Utilisateur extends Model
{
    public function create(string $nom, string $prenom, string $email, string $password, string $role = 'utilisateur'): bool
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare(
            "INSERT INTO utilisateurs (nom, prenom, email, password, role)
             VALUES (:nom, :prenom, :email, :password, :role)"
        );

        return $stmt->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => $role,
        ]);
    }

    public function findByEmail(string $email): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function verifyPassword(string $plainPassword, string $hashedPassword): bool
    {
        return password_verify($plainPassword, $hashedPassword);
    }

    public function getAllByRole(string $role): array
    {
        $stmt = $this->pdo->prepare("SELECT id, nom, prenom, email FROM utilisateurs WHERE role = :role ORDER BY nom");
        $stmt->execute(['role' => $role]);
        return $stmt->fetchAll();
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getAll(string $recherche = '', string $role = ''): array
    {
        $sql = "SELECT id, nom, prenom, email, role FROM utilisateurs WHERE 1=1";
        $params = [];

        if ($recherche !== '') {
            $sql .= " AND (nom LIKE :recherche OR prenom LIKE :recherche OR email LIKE :recherche)";
            $params['recherche'] = '%' . $recherche . '%';
        }
        if ($role !== '') {
            $sql .= " AND role = :role";
            $params['role'] = $role;
        }

        $sql .= " ORDER BY nom, prenom";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function emailExisteAilleurs(string $email, int $excludeId): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = :email AND id != :id");
        $stmt->execute(['email' => $email, 'id' => $excludeId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function update(int $id, string $nom, string $prenom, string $email, string $role, ?string $password = null): bool
    {
        if ($password !== null && $password !== '') {
            $stmt = $this->pdo->prepare(
                "UPDATE utilisateurs SET nom = :nom, prenom = :prenom, email = :email, role = :role, password = :password WHERE id = :id"
            );
            return $stmt->execute([
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'role' => $role,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'id' => $id,
            ]);
        }

        $stmt = $this->pdo->prepare(
            "UPDATE utilisateurs SET nom = :nom, prenom = :prenom, email = :email, role = :role WHERE id = :id"
        );
        return $stmt->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'role' => $role,
            'id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM utilisateurs WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function countReservations(int $id): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reservations WHERE utilisateur_id = :id");
        $stmt->execute(['id' => $id]);
        return (int)$stmt->fetchColumn();
    }
}