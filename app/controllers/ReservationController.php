<?php
require_once __DIR__ . '/../models/Reservation.php';
require_once __DIR__ . '/../models/Salle.php';
require_once __DIR__ . '/../models/Utilisateur.php';
require_once __DIR__ . '/../models/Batiment.php';
require_once __DIR__ . '/../services/NotificationService.php';

class ReservationController
{
    private Reservation $reservationModel;
    private Salle $salleModel;
    private Utilisateur $utilisateurModel;
    private Batiment $batimentModel;

    public function __construct()
    {
        $this->reservationModel = new Reservation();
        $this->salleModel = new Salle();
        $this->utilisateurModel = new Utilisateur();
        $this->batimentModel = new Batiment();
        $this->checkLoggedIn();
    }

    private function checkLoggedIn(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
    }

    private function checkUtilisateur(): void
    {
        $this->checkLoggedIn();

        if ($_SESSION['user_role'] !== 'utilisateur') {
            header('Location: index.php?controller=dashboard&action=index&error=access_denied');
            exit;
        }
    }

    private function checkGestionnaire(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        if ($_SESSION['user_role'] !== 'gestionnaire') {
            header('Location: index.php?controller=dashboard&action=index&error=access_denied');
            exit;
        }
    }

    // ===== Utilisateur: browse available salles + calendar + form =====
    public function create(): void
    {
        $this->checkUtilisateur();
        $filtres = [
            'capacite_min' => $_GET['capacite_min'] ?? '',
            'batiment_id' => $_GET['batiment_id'] ?? '',
            'equipements' => trim($_GET['equipements'] ?? ''),
        ];
        $salles = $this->salleModel->getAvailable($filtres);
        $batimentsListe = $this->batimentModel->getAll();
        require __DIR__ . '/../views/frontend/reservations/create.php';
    }

    public function processCreate(): void
    {
        $this->checkUtilisateur();
        $salleId = (int)($_POST['salle_id'] ?? 0);
        $dateDebut = $_POST['date_debut'] ?? '';
        $dateFin = $_POST['date_fin'] ?? '';
        $motif = trim($_POST['motif'] ?? '');
        $userId = (int)$_SESSION['user_id'];

        $salles = $this->salleModel->getAvailable();
        $batimentsListe = $this->batimentModel->getAll();

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

        $utilisateur = $this->utilisateurModel->getById($userId);
        $salle = $this->salleModel->getById($salleId);
        if ($utilisateur && $salle) {
            NotificationService::confirmationReservation($utilisateur, $dateDebut, $dateFin, $salle['nom']);
        }

        header('Location: index.php?controller=reservation&action=mine');
        exit;
    }

    // ===== Utilisateur: edit an existing (own) reservation =====
    public function edit(): void
    {
        $this->checkUtilisateur();
        $id = (int)($_GET['id'] ?? 0);
        $reservation = $this->reservationModel->getById($id);

        if (!$reservation || (int)$reservation['utilisateur_id'] !== (int)$_SESSION['user_id']) {
            header('Location: index.php?controller=reservation&action=mine');
            exit;
        }

        if (!in_array($reservation['statut'], ['en_attente', 'validee'], true)) {
            header('Location: index.php?controller=reservation&action=mine');
            exit;
        }

        $salles = $this->salleModel->getAvailable();
        require __DIR__ . '/../views/frontend/reservations/edit.php';
    }

    public function processEdit(): void
    {
        $this->checkUtilisateur();
        $id = (int)($_POST['id'] ?? 0);
        $reservation = $this->reservationModel->getById($id);

        if (!$reservation || (int)$reservation['utilisateur_id'] !== (int)$_SESSION['user_id']) {
            header('Location: index.php?controller=reservation&action=mine');
            exit;
        }

        $salleId = (int)($_POST['salle_id'] ?? 0);
        $dateDebut = $_POST['date_debut'] ?? '';
        $dateFin = $_POST['date_fin'] ?? '';
        $motif = trim($_POST['motif'] ?? '');

        $salles = $this->salleModel->getAvailable();

        if ($salleId === 0 || $dateDebut === '' || $dateFin === '') {
            $error = "Tous les champs sont requis.";
            require __DIR__ . '/../views/frontend/reservations/edit.php';
            return;
        }

        if (strtotime($dateDebut) >= strtotime($dateFin)) {
            $error = "La date de fin doit être après la date de début.";
            require __DIR__ . '/../views/frontend/reservations/edit.php';
            return;
        }

        if (strtotime($dateDebut) < time()) {
            $error = "Impossible de réserver dans le passé.";
            require __DIR__ . '/../views/frontend/reservations/edit.php';
            return;
        }

        if ($this->reservationModel->hasConflict($salleId, $dateDebut, $dateFin, $id)) {
            $error = "Cette salle est déjà réservée sur ce créneau. Choisissez un autre horaire.";
            require __DIR__ . '/../views/frontend/reservations/edit.php';
            return;
        }

        $this->reservationModel->reschedule($id, $salleId, $dateDebut, $dateFin);
        header('Location: index.php?controller=reservation&action=mine');
        exit;
    }

    // AJAX endpoint feeding FullCalendar: existing bookings for one salle
    public function calendarEvents(): void
    {
        $this->checkUtilisateur();
        $salleId = (int)($_GET['salle_id'] ?? 0);
        $excludeId = isset($_GET['exclude_id']) ? (int)$_GET['exclude_id'] : null;
        $events = $this->reservationModel->getBySalleForCalendar($salleId, $excludeId);

        header('Content-Type: application/json');
        echo json_encode($events);
        exit;
    }

    // ===== Utilisateur: my reservations history =====
    public function mine(): void
    {
        $this->checkUtilisateur();
        $tri = $_GET['tri'] ?? 'date_debut';
        $ordre = $_GET['ordre'] ?? 'DESC';
        $reservations = $this->reservationModel->getByUser((int)$_SESSION['user_id'], $tri, $ordre);
        require __DIR__ . '/../views/frontend/reservations/mine.php';
    }

    public function cancel(): void
    {
        $this->checkUtilisateur();
        $id = (int)($_GET['id'] ?? 0);
        $this->reservationModel->cancel($id, (int)$_SESSION['user_id']);
        header('Location: index.php?controller=reservation&action=mine');
        exit;
    }

    // ===== Gestionnaire: see all + approve/reject =====
    public function index(): void
    {
        $this->checkGestionnaire();
        $filtres = [
            'salle_id' => $_GET['salle_id'] ?? '',
            'statut' => $_GET['statut'] ?? '',
            'utilisateur' => trim($_GET['utilisateur'] ?? ''),
            'date_debut' => $_GET['date_debut'] ?? '',
            'date_fin' => $_GET['date_fin'] ?? '',
            'tri' => $_GET['tri'] ?? '',
            'ordre' => $_GET['ordre'] ?? 'DESC',
        ];
        $reservations = $this->reservationModel->getAll($filtres);
        $salles = $this->salleModel->getAll();
        require __DIR__ . '/../views/backend/reservations/index.php';
    }

    public function approve(): void
    {
        $this->checkGestionnaire();
        $id = (int)($_GET['id'] ?? 0);
        $reservation = $this->reservationModel->getById($id);
        $this->reservationModel->updateStatut($id, 'validee');

        if ($reservation) {
            $utilisateur = $this->utilisateurModel->getById((int)$reservation['utilisateur_id']);
            $salle = $this->salleModel->getById((int)$reservation['salle_id']);
            if ($utilisateur && $salle) {
                NotificationService::statutReservation($utilisateur, $salle['nom'], 'validee');
            }
        }

        header('Location: index.php?controller=reservation&action=index');
        exit;
    }

    public function reject(): void
    {
        $this->checkGestionnaire();
        $id = (int)($_GET['id'] ?? 0);
        $reservation = $this->reservationModel->getById($id);
        $this->reservationModel->updateStatut($id, 'refusee');

        if ($reservation) {
            $utilisateur = $this->utilisateurModel->getById((int)$reservation['utilisateur_id']);
            $salle = $this->salleModel->getById((int)$reservation['salle_id']);
            if ($utilisateur && $salle) {
                NotificationService::statutReservation($utilisateur, $salle['nom'], 'refusee');
            }
        }

        header('Location: index.php?controller=reservation&action=index');
        exit;
    }

    // ===== Gestionnaire: manual booking on behalf of a user =====
    public function createManuelle(): void
    {
        $this->checkGestionnaire();
        $salles = $this->salleModel->getAvailable();
        $utilisateurs = $this->utilisateurModel->getAllByRole('utilisateur');
        require __DIR__ . '/../views/backend/reservations/create_manuelle.php';
    }

    public function processCreateManuelle(): void
    {
        $this->checkGestionnaire();
        $utilisateurId = (int)($_POST['utilisateur_id'] ?? 0);
        $salleId = (int)($_POST['salle_id'] ?? 0);
        $dateDebut = $_POST['date_debut'] ?? '';
        $dateFin = $_POST['date_fin'] ?? '';
        $motif = trim($_POST['motif'] ?? '');

        $salles = $this->salleModel->getAvailable();
        $utilisateurs = $this->utilisateurModel->getAllByRole('utilisateur');

        if ($utilisateurId === 0 || $salleId === 0 || $dateDebut === '' || $dateFin === '') {
            $error = "Tous les champs sont requis.";
            require __DIR__ . '/../views/backend/reservations/create_manuelle.php';
            return;
        }

        if (strtotime($dateDebut) >= strtotime($dateFin)) {
            $error = "La date de fin doit être après la date de début.";
            require __DIR__ . '/../views/backend/reservations/create_manuelle.php';
            return;
        }

        if ($this->reservationModel->hasConflict($salleId, $dateDebut, $dateFin)) {
            $error = "Cette salle est déjà réservée sur ce créneau.";
            require __DIR__ . '/../views/backend/reservations/create_manuelle.php';
            return;
        }

        $this->reservationModel->create($salleId, $utilisateurId, $dateDebut, $dateFin, $motif, 'validee');

        $utilisateur = $this->utilisateurModel->getById($utilisateurId);
        $salle = $this->salleModel->getById($salleId);
        if ($utilisateur && $salle) {
            NotificationService::confirmationReservation($utilisateur, $dateDebut, $dateFin, $salle['nom']);
        }

        header('Location: index.php?controller=reservation&action=index');
        exit;
    }

    // ===== Gestionnaire: move/reschedule an existing reservation =====
    public function reschedule(): void
    {
        $this->checkGestionnaire();
        $id = (int)($_GET['id'] ?? 0);
        $reservation = $this->reservationModel->getById($id);

        if (!$reservation) {
            header('Location: index.php?controller=reservation&action=index');
            exit;
        }

        $salles = $this->salleModel->getAvailable();
        require __DIR__ . '/../views/backend/reservations/reschedule.php';
    }

    public function processReschedule(): void
    {
        $this->checkGestionnaire();
        $id = (int)($_POST['id'] ?? 0);
        $salleId = (int)($_POST['salle_id'] ?? 0);
        $dateDebut = $_POST['date_debut'] ?? '';
        $dateFin = $_POST['date_fin'] ?? '';

        $reservation = $this->reservationModel->getById($id);
        $salles = $this->salleModel->getAvailable();

        if (!$reservation) {
            header('Location: index.php?controller=reservation&action=index');
            exit;
        }

        if ($salleId === 0 || $dateDebut === '' || $dateFin === '') {
            $error = "Tous les champs sont requis.";
            require __DIR__ . '/../views/backend/reservations/reschedule.php';
            return;
        }

        if (strtotime($dateDebut) >= strtotime($dateFin)) {
            $error = "La date de fin doit être après la date de début.";
            require __DIR__ . '/../views/backend/reservations/reschedule.php';
            return;
        }

        if ($this->reservationModel->hasConflict($salleId, $dateDebut, $dateFin, $id)) {
            $error = "Conflit avec une autre réservation sur ce créneau.";
            require __DIR__ . '/../views/backend/reservations/reschedule.php';
            return;
        }

        $this->reservationModel->reschedule($id, $salleId, $dateDebut, $dateFin);
        header('Location: index.php?controller=reservation&action=index');
        exit;
    }
}
