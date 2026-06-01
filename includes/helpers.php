<?php
// C:\xampp\htdocs\gestion-evenements\includes\helpers.php

if (!function_exists('redirect')) {
    function redirect($url) {
        header("Location: $url");
        exit;
    }
}

if (!function_exists('sanitize')) {
    function sanitize($data) {
        return trim($data);
    }
}

if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        return isset($_SESSION['user']);
    }
}

if (!function_exists('getUserRole')) {
    function getUserRole() {
        if (!isLoggedIn()) return null;
        $user = $_SESSION['user'];
        if (is_object($user)) {
            return $user->role ?? null;
        } elseif (is_array($user)) {
            return $user['role'] ?? null;
        }
        return null;
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin() {
        return getUserRole() === 'admin';
    }
}

if (!function_exists('isOrganisateur')) {
    function isOrganisateur() {
        return getUserRole() === 'organisateur';
    }
}

if (!function_exists('isParticipant')) {
    function isParticipant() {
        return getUserRole() === 'participant';
    }
}

if (!function_exists('generateCodeQR')) {
    function generateCodeQR() {
        return 'BILLET-' . strtoupper(bin2hex(random_bytes(4))) . '-' . rand(100, 999);
    }
}

if (!function_exists('flash')) {
    function flash($key, $message = null) {
        if ($message !== null) {
            $_SESSION['flash'][$key] = $message;
        } else {
            if (isset($_SESSION['flash'][$key])) {
                $msg = $_SESSION['flash'][$key];
                unset($_SESSION['flash'][$key]);
                return $msg;
            }
        }
        return null;
    }
}

if (!function_exists('formatPrix')) {
    function formatPrix($montant) {
        if ($montant == 0) {
            return "Gratuit";
        }
        return number_format($montant, 0, ',', ' ') . ' FCFA';
    }
}

if (!function_exists('formatDate')) {
    function formatDate($dateStr) {
        $timestamp = strtotime($dateStr);
        $jours = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        $mois = ['', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        
        $j = $jours[date('w', $timestamp)];
        $d = date('d', $timestamp);
        $m = $mois[(int)date('m', $timestamp)];
        $y = date('Y', $timestamp);
        $time = date('H\hi', $timestamp);
        
        return "$j $d $m $y à $time";
    }
}

if (!function_exists('envoyerEmailMock')) {
    function envoyerEmailMock($to, $subject, $body) {
        $dir = __DIR__ . '/../logs/emails';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        
        $filename = $dir . '/email_' . date('Ymd_His') . '_' . uniqid() . '.txt';
        $content = "=== SIMULATION ENVOI EMAIL ===\n";
        $content .= "Date: " . date('d-m-Y H:i:s') . "\n";
        $content .= "Destinataire: $to\n";
        $content .= "Objet: $subject\n";
        $content .= "-------------------------------------------------------\n";
        $content .= $body . "\n";
        $content .= "=======================================================\n";
        
        file_put_contents($filename, $content);
        return true;
    }
}
