<?php
// C:\xampp\htdocs\gestion-evenements\models\Reservation.php

class Reservation {
    public static function create($pdo, $evenementId, $participantId, $nbPlaces, $montant) {
        $sql = "INSERT INTO reservation (evenement_id, participant_id, nb_places, montant, statut, date) 
                VALUES (?, ?, ?, ?, 'confirme', NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$evenementId, $participantId, $nbPlaces, $montant]);
        return $pdo->lastInsertId();
    }

    public static function getById($pdo, $id) {
        $sql = "SELECT r.*, e.titre AS evenement_titre, e.date AS evenement_date, e.lieu AS evenement_lieu, e.prix_billet,
                       p.nom AS participant_nom, p.prenom AS participant_prenom, p.email AS participant_email, p.telephone AS participant_tel
                FROM reservation r 
                JOIN evenement e ON r.evenement_id = e.id 
                JOIN participant p ON r.participant_id = p.id 
                WHERE r.id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function getByParticipant($pdo, $participantId) {
        $sql = "SELECT r.*, e.titre AS evenement_titre, e.date AS evenement_date, e.lieu AS evenement_lieu 
                FROM reservation r 
                JOIN evenement e ON r.evenement_id = e.id 
                WHERE r.participant_id = ? 
                ORDER BY r.date DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$participantId]);
        return $stmt->fetchAll();
    }

    public static function getByEvenement($pdo, $evenementId) {
        $sql = "SELECT r.*, p.nom AS participant_nom, p.prenom AS participant_prenom, p.email AS participant_email 
                FROM reservation r 
                JOIN participant p ON r.participant_id = p.id 
                WHERE r.evenement_id = ? 
                ORDER BY r.date DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$evenementId]);
        return $stmt->fetchAll();
    }

    public static function getAll($pdo, $limit = null) {
        $sql = "SELECT r.*, e.titre AS evenement_titre, p.nom AS participant_nom, p.prenom AS participant_prenom, p.email AS participant_email 
                FROM reservation r 
                JOIN evenement e ON r.evenement_id = e.id 
                JOIN participant p ON r.participant_id = p.id 
                ORDER BY r.date DESC";
        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit;
        }
        return $pdo->query($sql)->fetchAll();
    }

    public static function updateStatut($pdo, $id, $statut) {
        $sql = "UPDATE reservation SET statut = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$statut, $id]);
    }

    public static function countAll($pdo) {
        $sql = "SELECT COUNT(*) FROM reservation";
        return (int)$pdo->query($sql)->fetchColumn();
    }

    public static function getTotalRevenus($pdo) {
        $sql = "SELECT SUM(montant) FROM reservation WHERE statut = 'confirme'";
        return (float)$pdo->query($sql)->fetchColumn();
    }
}
