<?php
// C:\xampp\htdocs\gestion-evenements\controllers\AuthController.php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../includes/helpers.php';

$action = $_GET['action'] ?? 'login';
$error = '';

switch ($action) {
    case 'login':
        if (isLoggedIn()) {
            redirect('index.php');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = sanitize($_POST['email'] ?? '');
            $password = $_POST['mot_de_passe'] ?? '';

            if (empty($email) || empty($password)) {
                $error = "Veuillez remplir tous les champs.";
            } else {
                $user = User::login($pdo, $email, $password);
                if ($user) {
                    $_SESSION['user'] = $user;
                    flash('success', "Bienvenue, " . htmlspecialchars($user->prenom) . " ! Connexion réussie.");
                    
                    if ($user->role === 'admin') {
                        redirect('index.php?page=admin');
                    } else {
                        redirect('index.php');
                    }
                } else {
                    $error = "Adresse email ou mot de passe incorrect.";
                }
            }
        }
        
        $pageTitle = "Connexion - EventGabon";
        require_once __DIR__ . '/../views/auth/login.php';
        break;

    case 'register':
        if (isLoggedIn()) {
            redirect('index.php');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = sanitize($_POST['nom'] ?? '');
            $prenom = sanitize($_POST['prenom'] ?? '');
            $email = sanitize($_POST['email'] ?? '');
            $telephone = sanitize($_POST['telephone'] ?? '');
            $password = $_POST['mot_de_passe'] ?? '';
            $passwordConfirm = $_POST['confirmer_mot_de_passe'] ?? '';
            $role = sanitize($_POST['role'] ?? 'participant');

            if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
                $error = "Veuillez remplir tous les champs obligatoires.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Format d'adresse email invalide.";
            } elseif ($password !== $passwordConfirm) {
                $error = "Les mots de passe ne correspondent pas.";
            } elseif (strlen($password) < 4 || !preg_match('/[A-Z]/', $password)) {
                $error = "Le mot de passe doit faire au moins 4 caractères et contenir au moins une lettre majuscule.";
            } elseif (!in_array($role, ['participant', 'organisateur'])) {
                $error = "Rôle invalide.";
            } else {
                // Vérifier si l'email existe déjà
                if (User::getByEmail($pdo, $email)) {
                    $error = "Cette adresse email est déjà utilisée.";
                } else {
                    $success = User::register($pdo, $nom, $prenom, $email, $telephone, $password, $role);
                    if ($success) {
                        // Connexion automatique après inscription
                        $user = User::login($pdo, $email, $password);
                        if ($user) {
                            $_SESSION['user'] = $user;
                            flash('success', "Votre compte a été créé avec succès, " . htmlspecialchars($user->prenom) . " !");
                            redirect('index.php');
                        }
                    } else {
                        $error = "Une erreur est survenue lors de l'inscription.";
                    }
                }
            }
        }

        $pageTitle = "Inscription - EventGabon";
        require_once __DIR__ . '/../views/auth/register.php';
        break;

    case 'forgot_password':
        if (isLoggedIn()) {
            redirect('index.php');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = sanitize($_POST['email'] ?? '');

            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Veuillez saisir une adresse email valide.";
            } else {
                $user = User::getByEmail($pdo, $email);
                if ($user) {
                    // Générer un token temporaire stocké en session pour la simulation
                    $token = bin2hex(random_bytes(16));
                    $_SESSION['reset_token'][$email] = $token;

                    // Créer l'email simulé
                    $link = "http://localhost/gestion-evenements/index.php?page=auth&action=reset_password&email=" . urlencode($email) . "&token=" . $token;
                    $body = "Bonjour " . htmlspecialchars($user->prenom) . ",\n\n";
                    $body .= "Nous avons reçu une demande de réinitialisation de mot de passe pour votre compte.\n";
                    $body .= "Veuillez cliquer sur le lien ci-dessous pour définir un nouveau mot de passe :\n";
                    $body .= "$link\n\n";
                    $body .= "Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer cet email.\n\n";
                    $body .= "L'équipe EventGabon";

                    envoyerEmailMock($email, "Reinitialisation de votre mot de passe", $body);
                    flash('success', "Un email de réinitialisation a été envoyé (simulé dans logs/emails/). Consultez vos fichiers pour y accéder.");
                    redirect('index.php?page=auth&action=login');
                } else {
                    $error = "Aucun compte n'est associé à cette adresse email.";
                }
            }
        }

        $pageTitle = "Mot de passe oublié - EventGabon";
        require_once __DIR__ . '/../views/auth/forgot_password.php';
        break;

    case 'reset_password':
        if (isLoggedIn()) {
            redirect('index.php');
        }

        $email = sanitize($_GET['email'] ?? '');
        $token = sanitize($_GET['token'] ?? '');

        // Vérifier le token
        if (empty($email) || empty($token) || !isset($_SESSION['reset_token'][$email]) || $_SESSION['reset_token'][$email] !== $token) {
            flash('error', "Lien de réinitialisation invalide ou expiré.");
            redirect('index.php?page=auth&action=login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['mot_de_passe'] ?? '';
            $passwordConfirm = $_POST['confirmer_mot_de_passe'] ?? '';

            if (empty($password)) {
                $error = "Veuillez saisir votre nouveau mot de passe.";
            } elseif ($password !== $passwordConfirm) {
                $error = "Les mots de passe ne correspondent pas.";
            } elseif (strlen($password) < 4 || !preg_match('/[A-Z]/', $password)) {
                $error = "Le mot de passe doit faire au moins 4 caractères et contenir au moins une lettre majuscule.";
            } else {
                // Mettre à jour le mot de passe dans la BDD
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("UPDATE users SET mot_de_passe = ? WHERE email = ?");
                if ($stmt->execute([$hash, $email])) {
                    unset($_SESSION['reset_token'][$email]); // Invalider le token
                    flash('success', "Votre mot de passe a été modifié avec succès. Vous pouvez maintenant vous connecter.");
                    redirect('index.php?page=auth&action=login');
                } else {
                    $error = "Une erreur est survenue lors de la mise à jour du mot de passe.";
                }
            }
        }

        $pageTitle = "Réinitialiser mon mot de passe - EventGabon";
        require_once __DIR__ . '/../views/auth/reset_password.php';
        break;

    case 'logout':
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
            session_destroy();
            session_start(); // Rétablir une session pour les messages flash
            flash('success', "Vous avez été déconnecté avec succès.");
        }
        redirect('index.php');
        break;

    default:
        redirect('index.php?page=auth&action=login');
}
