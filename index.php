<?php
// C:\xampp\htdocs\gestion-evenements\index.php

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Charger la configuration et les fonctions d'aide
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/helpers.php';

// Déterminer la page demandée
$page = $_GET['page'] ?? 'home';

try {
    switch ($page) {
        case 'home':
            require_once __DIR__ . '/models/Evenement.php';
            // Récupérer les 6 prochains événements
            $evenements = Evenement::getUpcoming($pdo, 6);
            $pageTitle = "EventGabon | Découvrez les événements à Libreville et au Gabon";
            require_once __DIR__ . '/views/home/index.php';
            break;

        case 'auth':
            require_once __DIR__ . '/controllers/AuthController.php';
            break;

        case 'evenements':
            require_once __DIR__ . '/controllers/EvenementController.php';
            break;

        case 'reservations':
            require_once __DIR__ . '/controllers/ReservationController.php';
            break;

        case 'billets':
            require_once __DIR__ . '/controllers/BilletController.php';
            break;

        case 'admin':
            require_once __DIR__ . '/controllers/AdminController.php';
            break;

        default:
            // Page non trouvée -> Redirection vers l'accueil
            flash('error', "La page demandée n'existe pas.");
            redirect('index.php');
    }
} catch (Exception $e) {
    // Gestion globale des erreurs
    $errorMsg = $e->getMessage();
    $pageTitle = "Erreur Système";
    // Si en développement, on peut afficher l'erreur, sinon un message générique.
    require_once __DIR__ . '/views/layout/header.php';
    echo '<div class="alert alert-danger my-5">
            <h4 class="alert-heading"><i class="fa-solid fa-triangle-exclamation"></i> Une erreur système est survenue</h4>
            <p>Désolé, nous rencontrons des difficultés techniques. Veuillez réessayer plus tard.</p>
            <hr>
            <p class="mb-0">Détail technique (Administrateurs) : <code>' . htmlspecialchars($errorMsg) . '</code></p>
          </div>';
    require_once __DIR__ . '/views/layout/footer.php';
}
