<?php
class DashboardController
{
    public function index(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        require __DIR__ . '/../views/frontend/dashboard.php';
    }
}
