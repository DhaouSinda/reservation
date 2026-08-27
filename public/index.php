<?php
session_start();

$controllerName = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? 'login';

$controllerFile = __DIR__ . '/../app/controllers/' . ucfirst($controllerName) . 'Controller.php';

if (!file_exists($controllerFile)) {
    die("Contrôleur introuvable : " . $controllerName);
}

require_once $controllerFile;

$controllerClass = ucfirst($controllerName) . 'Controller';
$controller = new $controllerClass();

if (!method_exists($controller, $action)) {
    die("Action introuvable : " . $action);
}

$controller->$action();