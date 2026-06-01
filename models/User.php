<?php
// C:\xampp\htdocs\gestion-evenements\models\User.php

class User {
    public static function register($pdo, $nom, $prenom, $email, $telephone, $motDePasse, $role = 'participant') {
        $hash = password_hash($motDePasse, PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (nom, prenom, email, telephone, mot_de_passe, role) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$nom, $prenom, $email, $telephone, $hash, $role]);
    }

    public static function login($pdo, $email, $motDePasse) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($motDePasse, $user->mot_de_passe)) {
            return $user;
        }
        return false;
    }

    public static function getById($pdo, $id) {
        $sql = "SELECT id, nom, prenom, email, telephone, role, created_at FROM users WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function getByEmail($pdo, $email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public static function getAll($pdo) {
        $sql = "SELECT id, nom, prenom, email, telephone, role, created_at FROM users ORDER BY nom ASC, prenom ASC";
        return $pdo->query($sql)->fetchAll();
    }
}
