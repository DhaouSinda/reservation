<?php
require_once __DIR__ . '/../models/Batiment.php';
require_once __DIR__ . '/../models/Salle.php';
require_once __DIR__ . '/../models/Reservation.php';

class DashboardController
{
    public function index(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $role = $_SESSION['user_role'];

        if ($role === 'admin_batiments' || $role === 'gestionnaire') {
            $batimentModel = new Batiment();
            $salleModel = new Salle();
            $reservationModel = new Reservation();

            $batiments = $batimentModel->getAll();
            $salles = $salleModel->getAll();
            $reservations = $reservationModel->getAll();

            $totalBatiments = count($batiments);
            $totalSalles = count($salles);
            $totalReservations = count($reservations);
            $enAttente = count(array_filter($reservations, fn($r) => $r['statut'] === 'en_attente'));

            require __DIR__ . '/../views/backend/dashboard.php';
            return;
        }

        require __DIR__ . '/../views/frontend/dashboard.php';
    }
}
