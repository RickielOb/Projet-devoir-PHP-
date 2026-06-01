<?php
// C:\xampp\htdocs\gestion-evenements\controllers\ReservationController.php

require_once __DIR__ . '/../models/Evenement.php';
require_once __DIR__ . '/../models/Reservation.php';
require_once __DIR__ . '/../models/Billet.php';
require_once __DIR__ . '/../models/Participant.php';
require_once __DIR__ . '/../includes/helpers.php';

$action = $_GET['action'] ?? 'creer';
$error = '';

switch ($action) {
    case 'creer':
        $eventId = (int)($_GET['evenement_id'] ?? $_POST['evenement_id'] ?? 0);
        $evenement = Evenement::getById($pdo, $eventId);
        if (!$evenement) {
            flash('error', "Événement inconnu.");
            redirect('index.php');
        }

        $placesDispo = Evenement::getPlacesDisponibles($pdo, $eventId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = sanitize($_POST['nom'] ?? '');
            $prenom = sanitize($_POST['prenom'] ?? '');
            $email = sanitize($_POST['email'] ?? '');
            $telephone = sanitize($_POST['telephone'] ?? '');
            $nbPlaces = (int)($_POST['nb_places'] ?? 1);

            // Validation
            if ($nbPlaces <= 0) {
                $error = "Le nombre de places doit être au moins de 1.";
            } elseif ($nbPlaces > $placesDispo) {
                $error = "Désolé, il ne reste plus que $placesDispo places disponibles pour cet événement.";
            } elseif (empty($nom) || empty($prenom) || empty($email)) {
                $error = "Veuillez renseigner votre nom, prénom et adresse email.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Adresse email invalide.";
            } else {
                try {
                    $pdo->beginTransaction();

                    // 1. Récupérer ou créer le participant
                    $participantId = Participant::getOrCreate($pdo, $nom, $prenom, $email, $telephone);

                    // 2. Calculer le montant total
                    $montantTotal = $nbPlaces * $evenement->prix_billet;

                    // 3. Créer la réservation
                    $reservationId = Reservation::create($pdo, $eventId, $participantId, $nbPlaces, $montantTotal);

                    // 4. Générer les billets
                    for ($i = 0; $i < $nbPlaces; $i++) {
                        $codeQr = generateCodeQR();
                        Billet::create($pdo, $reservationId, $codeQr);
                    }

                    $pdo->commit();

                    // 5. Envoyer l'email de confirmation (Simulé)
                    $subject = "Confirmation de votre reservation - " . $evenement->titre;
                    $body = "Bonjour " . htmlspecialchars($prenom) . " " . htmlspecialchars($nom) . ",\n\n";
                    $body .= "Nous vous confirmons l'enregistrement de votre reservation pour l'evenement :\n";
                    $body .= ">> " . htmlspecialchars($evenement->titre) . "\n";
                    $body .= "Date : " . formatDate($evenement->date) . "\n";
                    $body .= "Lieu : " . htmlspecialchars($evenement->lieu) . "\n";
                    $body .= "Nombre de places : $nbPlaces\n";
                    $body .= "Montant regle : " . formatPrix($montantTotal) . "\n\n";
                    $body .= "Vous pouvez telecharger vos billets numeriques et codes QR directement sur le lien suivant :\n";
                    $body .= "http://localhost/gestion-evenements/index.php?page=reservations&action=confirmation&id=$reservationId\n\n";
                    $body .= "Merci de votre confiance,\nL'equipe EventGabon";
                    
                    envoyerEmailMock($email, $subject, $body);

                    flash('success', "Félicitations, votre réservation a été enregistrée avec succès ! Un email de confirmation vous a été envoyé (simulé).");
                    $_SESSION['last_reservation_id'] = $reservationId;
                    redirect('index.php?page=reservations&action=confirmation&id=' . $reservationId);

                } catch (Exception $e) {
                    $pdo->rollBack();
                    $error = "Une erreur est survenue lors de l'enregistrement de votre réservation : " . $e->getMessage();
                }
            }
        }

        $pageTitle = "Réserver des places pour : " . htmlspecialchars($evenement->titre);
        require_once __DIR__ . '/../views/reservations/formulaire.php';
        break;

    case 'confirmation':
        $id = (int)($_GET['id'] ?? $_SESSION['last_reservation_id'] ?? 0);
        $reservation = Reservation::getById($pdo, $id);
        if (!$reservation) {
            flash('error', "Réservation introuvable.");
            redirect('index.php');
        }

        // Récupérer les billets correspondants
        $billets = Billet::getByReservation($pdo, $id);
        $evenement = Evenement::getById($pdo, $reservation->evenement_id);

        $pageTitle = "Réservation Confirmée ! - EventGabon";
        require_once __DIR__ . '/../views/reservations/confirmation.php';
        break;

    default:
        redirect('index.php');
}
