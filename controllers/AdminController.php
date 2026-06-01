<?php
// C:\xampp\htdocs\gestion-evenements\controllers\AdminController.php

require_once __DIR__ . '/../models/Evenement.php';
require_once __DIR__ . '/../models/Reservation.php';
require_once __DIR__ . '/../models/Billet.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../includes/helpers.php';

if (!isLoggedIn() || !isAdmin()) {
    flash('error', "Accès réservé aux administrateurs.");
    redirect('index.php');
}

$action = $_GET['action'] ?? 'dashboard';

switch ($action) {
    case 'dashboard':
        // Statistiques globales
        $evenementsAll = Evenement::getAll($pdo);
        $totalEvenements = count($evenementsAll);
        $totalReservations = Reservation::countAll($pdo);
        $totalBillets = Billet::countAll($pdo);
        $billetsUtilises = Billet::countByStatut($pdo, 'utilise');
        $totalRevenus = Reservation::getTotalRevenus($pdo);

        // Récupérer les dernières réservations (limité à 10)
        $reservations = Reservation::getAll($pdo, 10);

        // Récupérer la liste des utilisateurs du site
        $users = User::getAll($pdo);

        $pageTitle = "Tableau de Bord Administrateur - EventGabon";
        require_once __DIR__ . '/../views/admin/dashboard.php';
        break;

    default:
        redirect('index.php?page=admin&action=dashboard');
}
