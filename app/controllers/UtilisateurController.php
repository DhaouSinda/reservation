<?php
require_once __DIR__ . '/../models/Utilisateur.php';

class UtilisateurController
{
    private Utilisateur $utilisateurModel;

    private const ROLES_VALIDES = ['utilisateur', 'gestionnaire', 'admin_batiments'];

    public function __construct()
    {
        $this->utilisateurModel = new Utilisateur();
        $this->checkAccess();
    }

    private function checkAccess(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        if ($_SESSION['user_role'] !== 'admin_batiments') {
            header('Location: index.php?controller=dashboard&action=index&error=access_denied');
            exit;
        }
    }

    // ===== Liste + recherche/filtre par rôle =====
    public function index(): void
    {
        $recherche = trim($_GET['recherche'] ?? '');
        $role = $_GET['role'] ?? '';
        $utilisateurs = $this->utilisateurModel->getAll($recherche, $role);
        require __DIR__ . '/../views/backend/utilisateurs/index.php';
    }

    // ===== Création (permet de créer un gestionnaire / admin_batiments) =====
    public function create(): void
    {
        require __DIR__ . '/../views/backend/utilisateurs/create.php';
    }

    public function processCreate(): void
    {
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'utilisateur';

        if ($nom === '' || $prenom === '' || $email === '' || $password === '') {
            $error = "Veuillez remplir tous les champs.";
            require __DIR__ . '/../views/backend/utilisateurs/create.php';
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Email invalide.";
            require __DIR__ . '/../views/backend/utilisateurs/create.php';
            return;
        }

        if (strlen($password) < 8) {
            $error = "Le mot de passe doit contenir au moins 8 caractères.";
            require __DIR__ . '/../views/backend/utilisateurs/create.php';
            return;
        }

        if (!in_array($role, self::ROLES_VALIDES, true)) {
            $role = 'utilisateur';
        }

        if ($this->utilisateurModel->findByEmail($email)) {
            $error = "Cet email est déjà utilisé.";
            require __DIR__ . '/../views/backend/utilisateurs/create.php';
            return;
        }

        $this->utilisateurModel->create($nom, $prenom, $email, $password, $role);
        header('Location: index.php?controller=utilisateur&action=index');
        exit;
    }

    // ===== Modification (infos, rôle, mot de passe optionnel) =====
    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $utilisateur = $this->utilisateurModel->getById($id);

        if (!$utilisateur) {
            die("Utilisateur introuvable.");
        }

        require __DIR__ . '/../views/backend/utilisateurs/edit.php';
    }

    public function processEdit(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = $_POST['role'] ?? 'utilisateur';
        $password = trim($_POST['password'] ?? '');

        $utilisateur = $this->utilisateurModel->getById($id);
        if (!$utilisateur) {
            die("Utilisateur introuvable.");
        }

        if ($nom === '' || $prenom === '' || $email === '') {
            $error = "Nom, prénom et email sont requis.";
            require __DIR__ . '/../views/backend/utilisateurs/edit.php';
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Email invalide.";
            require __DIR__ . '/../views/backend/utilisateurs/edit.php';
            return;
        }

        if ($password !== '' && strlen($password) < 8) {
            $error = "Le mot de passe doit contenir au moins 8 caractères.";
            require __DIR__ . '/../views/backend/utilisateurs/edit.php';
            return;
        }

        if (!in_array($role, self::ROLES_VALIDES, true)) {
            $role = 'utilisateur';
        }

        if ($this->utilisateurModel->emailExisteAilleurs($email, $id)) {
            $error = "Cet email est déjà utilisé par un autre compte.";
            require __DIR__ . '/../views/backend/utilisateurs/edit.php';
            return;
        }

        $this->utilisateurModel->update($id, $nom, $prenom, $email, $role, $password !== '' ? $password : null);
        header('Location: index.php?controller=utilisateur&action=index');
        exit;
    }

    // ===== Suppression (impossible de se supprimer soi-même) =====
    public function delete(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id === (int)$_SESSION['user_id']) {
            header('Location: index.php?controller=utilisateur&action=index&error=self_delete');
            exit;
        }

        if ($this->utilisateurModel->countReservations($id) > 0) {
            header('Location: index.php?controller=utilisateur&action=index&error=has_reservations');
            exit;
        }

        $this->utilisateurModel->delete($id);
        header('Location: index.php?controller=utilisateur&action=index');
        exit;
    }
}
