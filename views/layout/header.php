<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Découvrez les meilleurs événements au Gabon (concerts, conférences, ateliers) et réservez vos billets en ligne en toute sécurité.">
    <title><?= $pageTitle ?? 'EventGabon | Gestion d\'Événements' ?></title>
    
    <!-- Google Font Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom style.css -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- Navigation Header -->
    <header class="navbar no-print">
        <a href="index.php" class="nav-brand">
            <i class="fa-solid fa-ticket-simple"></i> EventGabon
        </a>
        
        <button class="nav-toggle" aria-label="Toggle menu">
            <i class="fa-solid fa-bars"></i>
        </button>
        
        <ul class="nav-links">
            <li><a href="index.php" class="nav-link <?= !isset($_GET['page']) || $_GET['page'] === 'home' ? 'active' : '' ?>">Accueil</a></li>
            <li><a href="index.php?page=evenements" class="nav-link <?= isset($_GET['page']) && $_GET['page'] === 'evenements' && ($_GET['action'] ?? '') === 'liste' ? 'active' : '' ?>">Événements</a></li>
            
            <?php if (isLoggedIn()): ?>
                <?php if (isAdmin() || isOrganisateur()): ?>
                    <li><a href="index.php?page=evenements&action=creer" class="nav-link <?= isset($_GET['page']) && $_GET['page'] === 'evenements' && ($_GET['action'] ?? '') === 'creer' ? 'active' : '' ?>">Créer un événement</a></li>
                    <li><a href="index.php?page=billets&action=scanner" class="nav-link <?= isset($_GET['page']) && $_GET['page'] === 'billets' && ($_GET['action'] ?? '') === 'scanner' ? 'active' : '' ?>">Scanner QR</a></li>
                    <?php if (isAdmin()): ?>
                        <li><a href="index.php?page=admin" class="nav-link <?= isset($_GET['page']) && $_GET['page'] === 'admin' ? 'active' : '' ?>">Dashboard Admin</a></li>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php 
                    $userSession = $_SESSION['user'];
                    $prenomGreeting = is_object($userSession) ? ($userSession->prenom ?? '') : ($userSession['prenom'] ?? '');
                ?>
                <li><span class="nav-link" style="color: var(--accent);"><i class="fa-solid fa-user"></i> Bonjour, <?= htmlspecialchars($prenomGreeting) ?></span></li>
                <li><a href="index.php?page=auth&action=logout" class="nav-link nav-btn">Déconnexion</a></li>
            <?php else: ?>
                <li><a href="index.php?page=auth&action=login" class="nav-link <?= isset($_GET['page']) && $_GET['page'] === 'auth' && ($_GET['action'] ?? '') === 'login' ? 'active' : '' ?>">Connexion</a></li>
                <li><a href="index.php?page=auth&action=register" class="nav-link nav-btn">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </header>

    <!-- Main Container -->
    <main class="container">
        <!-- Notification Area -->
        <?php if ($successMsg = flash('success')): ?>
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check fa-lg"></i>
                <div><?= $successMsg ?></div>
            </div>
        <?php endif; ?>

        <?php if ($errorMsg = flash('error')): ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-xmark fa-lg"></i>
                <div><?= $errorMsg ?></div>
            </div>
        <?php endif; ?>
