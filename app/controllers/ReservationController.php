<?php
require_once __DIR__ . '/../models/Reservation.php';
require_once __DIR__ . '/../models/Salle.php';

class ReservationController
{
    private Reservation $reservationModel;
    private Salle $salleModel;

    public function __construct()
    {
        $this->reservationModel = new Reservation();
        $this->salleModel = new Salle();
        $this->checkLoggedIn();
    }

    private function checkLoggedIn(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
    }

    // Utilisateur: browse available salles + form
    public function create(): void
    {
        $salles = $this->salleModel->getAvailable();
        require __DIR__ . '/../views/frontend/reservations/create.php';
    }

    public function processCreate(): void
    {
        $salleId = (int)($_POST['salle_id'] ?? 0);
        $dateDebut = $_POST['date_debut'] ?? '';
        $dateFin = $_POST['date_fin'] ?? '';
        $motif = trim($_POST['motif'] ?? '');
        $userId = (int)$_SESSION['user_id'];

        $salles = $this->salleModel->getAvailable();

        if ($salleId === 0 || $dateDebut === '' || $dateFin === '') {
            $error = "Tous les champs sont requis.";
            require __DIR__ . '/../views/frontend/reservations/create.php';
            return;
        }

        if (strtotime($dateDebut) >= strtotime($dateFin)) {
            $error = "La date de fin doit être après la date de début.";
            require __DIR__ . '/../views/frontend/reservations/create.php';
            return;
        }

        if (strtotime($dateDebut) < time()) {
            $error = "Impossible de réserver dans le passé.";
            require __DIR__ . '/../views/frontend/reservations/create.php';
            return;
        }

        if ($this->reservationModel->hasConflict($salleId, $dateDebut, $dateFin)) {
            $error = "Cette salle est déjà réservée sur ce créneau. Choisissez un autre horaire.";
            require __DIR__ . '/../views/frontend/reservations/create.php';
            return;
        }

        $this->reservationModel->create($salleId, $userId, $dateDebut, $dateFin, $motif);
        header('Location: index.php?controller=reservation&action=mine');
        exit;
    }

    // Utilisateur: my reservations history
    public function mine(): void
    {
        $reservations = $this->reservationModel->getByUser((int)$_SESSION['user_id']);
        require __DIR__ . '/../views/frontend/reservations/mine.php';
    }

    public function cancel(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $this->reservationModel->cancel($id, (int)$_SESSION['user_id']);
        header('Location: index.php?controller=reservation&action=mine');
        exit;
    }

    // Gestionnaire: see all + approve/reject
    public function index(): void
    {
        $this->checkGestionnaire();
        $reservations = $this->reservationModel->getAll();
        require __DIR__ . '/../views/backend/reservations/index.php';
    }

    public function approve(): void
    {
        $this->checkGestionnaire();
        $id = (int)($_GET['id'] ?? 0);
        $this->reservationModel->updateStatut($id, 'validee');
        header('Location: index.php?controller=reservation&action=index');
        exit;
    }

    public function reject(): void
    {
        $this->checkGestionnaire();
        $id = (int)($_GET['id'] ?? 0);
        $this->reservationModel->updateStatut($id, 'refusee');
        header('Location: index.php?controller=reservation&action=index');
        exit;
    }

    private function checkGestionnaire(): void
    {
        if ($_SESSION['user_role'] !== 'gestionnaire') {
            die("Accès refusé.");
        }
    }
}