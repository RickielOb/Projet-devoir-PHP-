<?php
// C:\xampp\htdocs\gestion-evenements\models\Billet.php

class Billet {
    public static function create($pdo, $reservationId, $codeQr) {
        $sql = "INSERT INTO billet (reservation_id, code_qr, statut) VALUES (?, ?, 'valide')";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$reservationId, $codeQr]);
    }

    public static function getByReservation($pdo, $reservationId) {
        $sql = "SELECT b.*, e.titre AS evenement_titre, e.date AS evenement_date, e.lieu AS evenement_lieu, 
                       p.nom AS participant_nom, p.prenom AS participant_prenom
                FROM billet b 
                JOIN reservation r ON b.reservation_id = r.id 
                JOIN evenement e ON r.evenement_id = e.id 
                JOIN participant p ON r.participant_id = p.id 
                WHERE b.reservation_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$reservationId]);
        return $stmt->fetchAll();
    }

    public static function getByCode($pdo, $codeQr) {
        $sql = "SELECT b.*, e.titre AS evenement_titre, e.date AS evenement_date, e.lieu AS evenement_lieu, e.prix_billet,
                       p.nom AS participant_nom, p.prenom AS participant_prenom, p.email AS participant_email, p.telephone AS participant_tel,
                       r.id AS reservation_id
                FROM billet b 
                JOIN reservation r ON b.reservation_id = r.id 
                JOIN evenement e ON r.evenement_id = e.id 
                JOIN participant p ON r.participant_id = p.id 
                WHERE b.code_qr = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$codeQr]);
        return $stmt->fetch();
    }

    public static function valider($pdo, $codeQr) {
        $billet = self::getByCode($pdo, $codeQr);
        if (!$billet) {
            return [
                'success' => false,
                'message' => 'Ce billet n\'existe pas (QR code inconnu).'
            ];
        }

        if ($billet->statut === 'utilise') {
            return [
                'success' => false,
                'message' => 'Ce billet a déjà été validé à l\'entrée.',
                'billet' => $billet
            ];
        }

        if ($billet->statut === 'invalide') {
            return [
                'success' => false,
                'message' => 'Ce billet est invalide ou annulé.',
                'billet' => $billet
            ];
        }

        // Si valide, on le passe à utilise
        $sql = "UPDATE billet SET statut = 'utilise' WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$billet->id]);
        
        $billet->statut = 'utilise'; // Mettre à jour l'objet retourné

        return [
            'success' => true,
            'message' => 'Billet validé avec succès ! Bienvenue à l\'événement.',
            'billet' => $billet
        ];
    }

    public static function countAll($pdo) {
        $sql = "SELECT COUNT(*) FROM billet";
        return (int)$pdo->query($sql)->fetchColumn();
    }

    public static function countByStatut($pdo, $statut) {
        $sql = "SELECT COUNT(*) FROM billet WHERE statut = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$statut]);
        return (int)$stmt->fetchColumn();
    }
}
