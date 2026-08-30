<?php
require_once __DIR__ . '/../models/Reservation.php';

class StatistiqueController
{
    private Reservation $reservationModel;

    public function __construct()
    {
        $this->reservationModel = new Reservation();
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

    public function index(): void
    {
        $statsSalles = $this->reservationModel->getStatsBySalle();
        $statsStatuts = $this->reservationModel->getStatsByStatut();
        require __DIR__ . '/../views/backend/statistiques/index.php';
    }

    public function rapport(): void
    {
        $dateDebut = $_GET['date_debut'] ?? date('Y-m-01');
        $dateFin = $_GET['date_fin'] ?? date('Y-m-t');

        $reservations = $this->reservationModel->getByPeriod($dateDebut . ' 00:00:00', $dateFin . ' 23:59:59');

        require __DIR__ . '/../views/backend/statistiques/rapport.php';
    }

    public function exportCsv(): void
    {
        $dateDebut = $_GET['date_debut'] ?? date('Y-m-01');
        $dateFin = $_GET['date_fin'] ?? date('Y-m-t');

        $reservations = $this->reservationModel->getByPeriod($dateDebut . ' 00:00:00', $dateFin . ' 23:59:59');

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=rapport_' . $dateDebut . '_' . $dateFin . '.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Utilisateur', 'Salle', 'Batiment', 'Debut', 'Fin', 'Motif', 'Statut']);

        foreach ($reservations as $r) {
            fputcsv($output, [
                $r['user_prenom'] . ' ' . $r['user_nom'],
                $r['salle_nom'],
                $r['batiment_nom'],
                $r['date_debut'],
                $r['date_fin'],
                $r['motif'],
                $r['statut'],
            ]);
        }

        fclose($output);
        exit;
    }
}
