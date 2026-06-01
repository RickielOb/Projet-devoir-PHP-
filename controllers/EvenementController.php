<?php
// C:\xampp\htdocs\gestion-evenements\controllers\EvenementController.php

require_once __DIR__ . '/../models/Evenement.php';
require_once __DIR__ . '/../models/Organisateur.php';
require_once __DIR__ . '/../includes/helpers.php';

$action = $_GET['action'] ?? 'liste';
$error = '';

switch ($action) {
    case 'liste':
        $q = sanitize($_GET['q'] ?? '');
        $lieu = sanitize($_GET['lieu'] ?? '');
        $dateDebut = sanitize($_GET['date_debut'] ?? '');
        $dateFin = sanitize($_GET['date_fin'] ?? '');

        // Pagination setup
        $limit = 6;
        $currentPage = (int)($_GET['p'] ?? 1);
        if ($currentPage < 1) $currentPage = 1;
        $offset = ($currentPage - 1) * $limit;

        // Récupérer les événements filtrés et paginés
        $evenements = Evenement::getPaginated($pdo, $offset, $limit, $q, $lieu, $dateDebut, $dateFin);
        $totalCount = Evenement::countWithFilters($pdo, $q, $lieu, $dateDebut, $dateFin);
        $totalPages = ceil($totalCount / $limit);
        if ($totalPages < 1) $totalPages = 1;

        // Calculer les places disponibles pour chaque événement
        $placesDisponibles = [];
        foreach ($evenements as $event) {
            $placesDisponibles[$event->id] = Evenement::getPlacesDisponibles($pdo, $event->id);
        }

        $pageTitle = "Découvrir les Événements au Gabon - EventGabon";
        require_once __DIR__ . '/../views/evenements/liste.php';
        break;

    case 'detail':
        $id = (int)($_GET['id'] ?? 0);
        $evenement = Evenement::getById($pdo, $id);
        if (!$evenement) {
            flash('error', "L'événement demandé n'existe pas.");
            redirect('index.php');
        }

        $placesDispo = Evenement::getPlacesDisponibles($pdo, $id);
        $pageTitle = htmlspecialchars($evenement->titre) . " - EventGabon";
        require_once __DIR__ . '/../views/evenements/detail.php';
        break;

    case 'creer':
        if (!isLoggedIn() || (!isAdmin() && !isOrganisateur())) {
            flash('error', "Accès refusé. Vous devez être connecté en tant qu'organisateur ou administrateur.");
            redirect('index.php');
        }

        $organisateurs = Organisateur::getAll($pdo);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = sanitize($_POST['titre'] ?? '');
            $description = sanitize($_POST['description'] ?? '');
            $date = sanitize($_POST['date'] ?? '');
            $lieu = sanitize($_POST['lieu'] ?? '');
            $capacite = (int)($_POST['capacite'] ?? 0);
            $prixBillet = (float)($_POST['prix_billet'] ?? 0);
            $imageUrl = sanitize($_POST['image_url'] ?? '');
            $organisateurId = (int)($_POST['organisateur_id'] ?? 0);

            // Validation
            if (empty($titre) || empty($date) || empty($lieu) || $capacite < 0 || $prixBillet < 0) {
                $error = "Veuillez remplir correctement tous les champs obligatoires.";
            } else {
                // Déterminer l'organisateur
                if (isOrganisateur()) {
                    // Si l'utilisateur connecté est organisateur, on cherche son organisateur_id par email
                    $userEmail = $_SESSION['user']->email;
                    $stmtOrg = $pdo->prepare("SELECT id FROM organisateur WHERE email = ?");
                    $stmtOrg->execute([$userEmail]);
                    $org = $stmtOrg->fetch();
                    if ($org) {
                        $organisateurId = $org->id;
                    } else {
                        // Créer un enregistrement organisateur correspondant au compte utilisateur
                        $stmtInsOrg = $pdo->prepare("INSERT INTO organisateur (nom, email, telephone, entreprise) VALUES (?, ?, ?, ?)");
                        $stmtInsOrg->execute([
                            $_SESSION['user']->nom . ' ' . $_SESSION['user']->prenom,
                            $userEmail,
                            $_SESSION['user']->telephone,
                            'Indépendant'
                        ]);
                        $organisateurId = $pdo->lastInsertId();
                    }
                }

                $data = [
                    'titre' => $titre,
                    'description' => $description,
                    'date' => $date,
                    'lieu' => $lieu,
                    'capacite' => $capacite,
                    'prix_billet' => $prixBillet,
                    'organisateur_id' => $organisateurId ?: null,
                    'image_url' => $imageUrl ?: 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?q=80&w=800'
                ];

                if (Evenement::create($pdo, $data)) {
                    flash('success', "L'événement a été créé avec succès.");
                    redirect('index.php?page=evenements');
                } else {
                    $error = "Une erreur est survenue lors de la création de l'événement.";
                }
            }
        }

        $pageTitle = "Créer un Événement - EventGabon";
        require_once __DIR__ . '/../views/evenements/creer.php';
        break;

    case 'modifier':
        $id = (int)($_GET['id'] ?? 0);
        $evenement = Evenement::getById($pdo, $id);
        if (!$evenement) {
            flash('error', "L'événement demandé n'existe pas.");
            redirect('index.php');
        }

        // Vérification des droits d'accès
        if (!isLoggedIn() || (!isAdmin() && !isOrganisateur())) {
            flash('error', "Accès refusé.");
            redirect('index.php');
        }

        // Si organisateur, vérifier qu'il modifie son propre événement
        if (isOrganisateur()) {
            $userEmail = $_SESSION['user']->email;
            if ($evenement->organisateur_email !== $userEmail) {
                flash('error', "Vous ne pouvez modifier que vos propres événements.");
                redirect('index.php?page=evenements&action=detail&id=' . $id);
            }
        }

        $organisateurs = Organisateur::getAll($pdo);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = sanitize($_POST['titre'] ?? '');
            $description = sanitize($_POST['description'] ?? '');
            $date = sanitize($_POST['date'] ?? '');
            $lieu = sanitize($_POST['lieu'] ?? '');
            $capacite = (int)($_POST['capacite'] ?? 0);
            $prixBillet = (float)($_POST['prix_billet'] ?? 0);
            $imageUrl = sanitize($_POST['image_url'] ?? '');
            $organisateurId = (int)($_POST['organisateur_id'] ?? 0);

            // Validation
            if (empty($titre) || empty($date) || empty($lieu) || $capacite < 0 || $prixBillet < 0) {
                $error = "Veuillez remplir correctement tous les champs obligatoires.";
            } else {
                $data = [
                    'titre' => $titre,
                    'description' => $description,
                    'date' => $date,
                    'lieu' => $lieu,
                    'capacite' => $capacite,
                    'prix_billet' => $prixBillet,
                    'organisateur_id' => $organisateurId ?: ($evenement->organisateur_id ?: null),
                    'image_url' => $imageUrl ?: $evenement->image_url
                ];

                if (Evenement::update($pdo, $id, $data)) {
                    flash('success', "L'événement a été modifié avec succès.");
                    redirect('index.php?page=evenements&action=detail&id=' . $id);
                } else {
                    $error = "Une erreur est survenue lors de la modification de l'événement.";
                }
            }
        }

        $pageTitle = "Modifier l'Événement - EventGabon";
        require_once __DIR__ . '/../views/evenements/modifier.php';
        break;

    case 'supprimer':
        $id = (int)($_GET['id'] ?? 0);
        $evenement = Evenement::getById($pdo, $id);
        if (!$evenement) {
            flash('error', "L'événement n'existe pas.");
            redirect('index.php');
        }

        // Vérification des droits d'accès
        if (!isLoggedIn() || (!isAdmin() && !isOrganisateur())) {
            flash('error', "Accès refusé.");
            redirect('index.php');
        }

        // Si organisateur, vérifier la propriété
        if (isOrganisateur()) {
            $userEmail = $_SESSION['user']->email;
            if ($evenement->organisateur_email !== $userEmail) {
                flash('error', "Accès refusé. Ce n'est pas votre événement.");
                redirect('index.php');
            }
        }

        if (Evenement::delete($pdo, $id)) {
            flash('success', "L'événement a été supprimé avec succès.");
        } else {
            flash('error', "Impossible de supprimer l'événement.");
        }
        redirect('index.php?page=evenements');
        break;

    default:
        redirect('index.php?page=evenements&action=liste');
}
