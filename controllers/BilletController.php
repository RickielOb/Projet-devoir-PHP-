<?php
// C:\xampp\htdocs\gestion-evenements\controllers\BilletController.php

require_once __DIR__ . '/../models/Billet.php';
require_once __DIR__ . '/../includes/helpers.php';

$action = $_GET['action'] ?? 'scanner';
$error = '';

switch ($action) {
    case 'afficher':
        $code = sanitize($_GET['code'] ?? '');
        $billet = Billet::getByCode($pdo, $code);
        if (!$billet) {
            flash('error', "Billet introuvable.");
            redirect('index.php');
        }

        $pageTitle = "Mon Billet - " . htmlspecialchars($billet->evenement_titre);
        require_once __DIR__ . '/../views/billets/afficher.php';
        break;

    case 'scanner':
        if (!isLoggedIn() || (!isAdmin() && !isOrganisateur())) {
            flash('error', "Accès refusé. Seuls les administrateurs et organisateurs peuvent scanner des billets.");
            redirect('index.php');
        }

        $pageTitle = "Scanner les Billets à l'entrée - EventGabon";
        require_once __DIR__ . '/../views/billets/scanner.php';
        break;

    case 'valider':
        // Cet endpoint est appelé principalement via AJAX (en POST ou en GET)
        if (!isLoggedIn() || (!isAdmin() && !isOrganisateur())) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'Non autorisé. Veuillez vous connecter.']);
            exit;
        }

        $codeQr = sanitize($_POST['code_qr'] ?? $_GET['code_qr'] ?? '');
        if (empty($codeQr)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'Aucun QR code fourni.']);
            exit;
        }

        $result = Billet::valider($pdo, $codeQr);
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
        exit;

    default:
        redirect('index.php?page=billets&action=scanner');
}
