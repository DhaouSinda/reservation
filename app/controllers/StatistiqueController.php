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

    public function exportPdf(): void
    {
        $dateDebut = $_GET['date_debut'] ?? date('Y-m-01');
        $dateFin = $_GET['date_fin'] ?? date('Y-m-t');

        $reservations = $this->reservationModel->getByPeriod($dateDebut . ' 00:00:00', $dateFin . ' 23:59:59');
        $stats = $this->reservationModel->getStatsForPeriod($dateDebut . ' 00:00:00', $dateFin . ' 23:59:59');

        $autoload = __DIR__ . '/../../vendor/autoload.php';
        if (!file_exists($autoload)) {
            die("Dompdf n'est pas installé. Exécutez : composer require dompdf/dompdf");
        }
        require_once $autoload;

        ob_start();
        require __DIR__ . '/../views/backend/statistiques/rapport_pdf.php';
        $html = ob_get_clean();

        $dompdf = new Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream('rapport_bookit_' . $dateDebut . '_' . $dateFin . '.pdf', ['Attachment' => true]);
        exit;
    }
}
