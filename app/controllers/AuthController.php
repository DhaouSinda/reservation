<?php
require_once __DIR__ . '/../models/Utilisateur.php';

class AuthController
{
    private Utilisateur $userModel;

    public function __construct()
    {
        $this->userModel = new Utilisateur();
    }

    public function login(): void
    {
        require __DIR__ . '/../views/auth/login.php';
    }

    public function processLogin(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $error = "Veuillez remplir tous les champs.";
            require __DIR__ . '/../views/auth/login.php';
            return;
        }

        $user = $this->userModel->findByEmail($email);

        if ($user && $this->userModel->verifyPassword($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_nom'] = $user['nom'];

            header('Location: index.php?controller=dashboard&action=index');
            exit;
        }

        $error = "Email ou mot de passe incorrect.";
        require __DIR__ . '/../views/auth/login.php';
    }

    public function register(): void
    {
        require __DIR__ . '/../views/auth/register.php';
    }

    public function processRegister(): void
    {
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($nom === '' || $prenom === '' || $email === '' || $password === '') {
            $error = "Veuillez remplir tous les champs.";
            require __DIR__ . '/../views/auth/register.php';
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Email invalide.";
            require __DIR__ . '/../views/auth/register.php';
            return;
        }

        if ($this->userModel->findByEmail($email)) {
            $error = "Cet email est déjà utilisé.";
            require __DIR__ . '/../views/auth/register.php';
            return;
        }

        $this->userModel->create($nom, $prenom, $email, $password);

        header('Location: index.php?controller=auth&action=login');
        exit;
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: index.php?controller=auth&action=login');
        exit;
    }
}