<?php
require_once __DIR__ . '/../../config/db.php';

abstract class Model
{
    protected PDO $pdo;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->connect();
    }
}