<?php
// C:\xampp\htdocs\gestion-evenements\models\Participant.php

class Participant {
    public static function getOrCreate($pdo, $nom, $prenom, $email, $telephone) {
        // Rechercher par email
        $sqlSelect = "SELECT * FROM participant WHERE email = ?";
        $stmtSelect = $pdo->prepare($sqlSelect);
        $stmtSelect->execute([$email]);
        $participant = $stmtSelect->fetch();

        if ($participant) {
            // Mettre à jour le téléphone si fourni et vide
            if (empty($participant->telephone) && !empty($telephone)) {
                $sqlUpdate = "UPDATE participant SET telephone = ? WHERE id = ?";
                $stmtUpdate = $pdo->prepare($sqlUpdate);
                $stmtUpdate->execute([$telephone, $participant->id]);
                $participant->telephone = $telephone;
            }
            return $participant->id;
        }

        // Créer un nouveau participant
        $sqlInsert = "INSERT INTO participant (nom, prenom, email, telephone) VALUES (?, ?, ?, ?)";
        $stmtInsert = $pdo->prepare($sqlInsert);
        $stmtInsert->execute([$nom, $prenom, $email, $telephone]);
        return $pdo->lastInsertId();
    }

    public static function getById($pdo, $id) {
        $sql = "SELECT * FROM participant WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function getAll($pdo) {
        $sql = "SELECT * FROM participant ORDER BY nom ASC, prenom ASC";
        return $pdo->query($sql)->fetchAll();
    }
}
