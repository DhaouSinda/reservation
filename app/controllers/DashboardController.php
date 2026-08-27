<?php
class DashboardController
{
    public function index(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        echo "Bienvenue " . htmlspecialchars($_SESSION['user_nom']) . " ! (rôle : " . htmlspecialchars($_SESSION['user_role']) . ")";
    }
}