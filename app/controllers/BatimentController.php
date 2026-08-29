<?php
require_once __DIR__ . '/../models/Batiment.php';
require_once __DIR__ . '/../models/Etage.php';

class BatimentController
{
    private Batiment $batimentModel;
    private Etage $etageModel;

    public function __construct()
    {
        $this->batimentModel = new Batiment();
        $this->etageModel = new Etage();
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
        $batiments = $this->batimentModel->getAll();
        require __DIR__ . '/../views/backend/batiments/index.php';
    }

    public function create(): void
    {
        require __DIR__ . '/../views/backend/batiments/create.php';
    }

    public function processCreate(): void
    {
        $nom = trim($_POST['nom'] ?? '');
        $adresse = trim($_POST['adresse'] ?? '');
        $etagesInput = trim($_POST['etages'] ?? '');

        if ($nom === '') {
            $error = "Le nom du bâtiment est requis.";
            require __DIR__ . '/../views/backend/batiments/create.php';
            return;
        }

        $this->batimentModel->create($nom, $adresse);
        $batimentId = (int)$this->batimentModel->getLastInsertId();

        if ($etagesInput !== '') {
            $numeros = array_map('trim', explode(',', $etagesInput));
            foreach ($numeros as $numero) {
                if ($numero !== '' && is_numeric($numero)) {
                    $this->etageModel->create($batimentId, (int)$numero);
                }
            }
        }

        header('Location: index.php?controller=batiment&action=index');
        exit;
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $batiment = $this->batimentModel->getById($id);

        if (!$batiment) {
            die("Bâtiment introuvable.");
        }

        $etages = $this->etageModel->getByBatiment($id);
        require __DIR__ . '/../views/backend/batiments/edit.php';
    }

    public function processEdit(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $nom = trim($_POST['nom'] ?? '');
        $adresse = trim($_POST['adresse'] ?? '');

        if ($nom === '') {
            die("Le nom est requis.");
        }

        $this->batimentModel->update($id, $nom, $adresse);
        header('Location: index.php?controller=batiment&action=index');
        exit;
    }

    public function delete(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $this->batimentModel->delete($id);
        header('Location: index.php?controller=batiment&action=index');
        exit;
    }

    public function addEtage(): void
    {
        $batimentId = (int)($_POST['batiment_id'] ?? 0);
        $numero = (int)($_POST['numero'] ?? 0);

        $this->etageModel->create($batimentId, $numero);
        header('Location: index.php?controller=batiment&action=edit&id=' . $batimentId);
        exit;
    }
}