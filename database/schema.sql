-- Schéma DDL pour la base de données de gestion d'événements (MySQL)

CREATE DATABASE IF NOT EXISTS gestion_evenements CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gestion_evenements;

-- 1. Table des Organisateurs
CREATE TABLE IF NOT EXISTS organisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    telephone VARCHAR(20),
    entreprise VARCHAR(150)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Table des Participants
CREATE TABLE IF NOT EXISTS participant (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    telephone VARCHAR(20)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Table des Événements
CREATE TABLE IF NOT EXISTS evenement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(200) NOT NULL,
    description TEXT,
    date DATETIME NOT NULL,
    lieu VARCHAR(150) NOT NULL,
    capacite INT NOT NULL CHECK (capacite >= 0),
    prix_billet DECIMAL(10, 2) NOT NULL CHECK (prix_billet >= 0),
    organisateur_id INT,
    image_url VARCHAR(255),
    FOREIGN KEY (organisateur_id) REFERENCES organisateur(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Table des Réservations
CREATE TABLE IF NOT EXISTS reservation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evenement_id INT NOT NULL,
    participant_id INT NOT NULL,
    nb_places INT NOT NULL CHECK (nb_places > 0),
    montant DECIMAL(10, 2) NOT NULL CHECK (montant >= 0),
    date_reservation DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('en_attente', 'confirme', 'annule') NOT NULL DEFAULT 'confirme',
    FOREIGN KEY (evenement_id) REFERENCES evenement(id) ON DELETE CASCADE,
    FOREIGN KEY (participant_id) REFERENCES participant(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Table des Billets
CREATE TABLE IF NOT EXISTS billet (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    code_qr VARCHAR(255) UNIQUE NOT NULL,
    statut ENUM('valide', 'utilise', 'invalide') NOT NULL DEFAULT 'valide',
    FOREIGN KEY (reservation_id) REFERENCES reservation(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Table des Utilisateurs (Nouveau pour l'authentification)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    telephone VARCHAR(20),
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('admin', 'organisateur', 'participant') NOT NULL DEFAULT 'participant',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
