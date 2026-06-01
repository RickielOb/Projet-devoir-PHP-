<?php
// C:\xampp\htdocs\gestion-evenements\models\Organisateur.php

class Organisateur {
    public static function getAll($pdo) {
        $sql = "SELECT * FROM organisateur ORDER BY nom ASC";
        return $pdo->query($sql)->fetchAll();
    }

    public static function getById($pdo, $id) {
        $sql = "SELECT * FROM organisateur WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
