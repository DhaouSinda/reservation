<?php
require_once __DIR__ . '/../models/Salle.php';
require_once __DIR__ . '/../models/Batiment.php';

class SalleController
{
    private Salle $salleModel;
    private Batiment $batimentModel;

    public function __construct()
    {
        $this->salleModel = new Salle();
        $this->batimentModel = new Batiment();
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
        $filtres = [
            'recherche' => trim($_GET['recherche'] ?? ''),
            'batiment_id' => $_GET['batiment_id'] ?? '',
            'statut' => $_GET['statut'] ?? '',
            'tri' => $_GET['tri'] ?? '',
            'ordre' => $_GET['ordre'] ?? 'ASC',
        ];
        $salles = $this->salleModel->getAll($filtres);
        $batimentsListe = $this->batimentModel->getAll();
        require __DIR__ . '/../views/backend/salles/index.php';
    }

    public function create(): void
    {
        $etagesDisponibles = $this->batimentModel->getAllWithEtages();
        require __DIR__ . '/../views/backend/salles/create.php';
    }

    public function processCreate(): void
    {
        $etageId = (int)($_POST['etage_id'] ?? 0);
        $nom = trim($_POST['nom'] ?? '');
        $capacite = (int)($_POST['capacite'] ?? 0);
        $equipements = trim($_POST['equipements'] ?? '');
        $localisation = trim($_POST['localisation'] ?? '');

        if ($etageId === 0 || $nom === '' || $capacite <= 0) {
            $error = "Étage, nom et capacité (> 0) sont requis.";
            $etagesDisponibles = $this->batimentModel->getAllWithEtages();
            require __DIR__ . '/../views/backend/salles/create.php';
            return;
        }

        $this->salleModel->create($etageId, $nom, $capacite, $equipements, $localisation);
        header('Location: index.php?controller=salle&action=index');
        exit;
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $salle = $this->salleModel->getById($id);

        if (!$salle) {
            die("Salle introuvable.");
        }

        require __DIR__ . '/../views/backend/salles/edit.php';
    }

    public function processEdit(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $nom = trim($_POST['nom'] ?? '');
        $capacite = (int)($_POST['capacite'] ?? 0);
        $equipements = trim($_POST['equipements'] ?? '');
        $localisation = trim($_POST['localisation'] ?? '');
        $statut = $_POST['statut'] ?? 'disponible';

        if ($nom === '' || $capacite <= 0) {
            die("Nom et capacité (> 0) sont requis.");
        }

        $this->salleModel->update($id, $nom, $capacite, $equipements, $localisation, $statut);
        header('Location: index.php?controller=salle&action=index');
        exit;
    }

    public function delete(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $this->salleModel->delete($id);
        header('Location: index.php?controller=salle&action=index');
        exit;
    }

    public function changerStatut(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $statut = $_GET['statut'] ?? '';
        $statutsValides = ['disponible', 'maintenance', 'indisponible'];

        if (in_array($statut, $statutsValides, true)) {
            $salle = $this->salleModel->getById($id);
            if ($salle) {
                $this->salleModel->update(
                    $id,
                    $salle['nom'],
                    (int)$salle['capacite'],
                    $salle['equipements'],
                    $salle['localisation'],
                    $statut
                );
            }
        }

        header('Location: index.php?controller=salle&action=index');
        exit;
    }
}