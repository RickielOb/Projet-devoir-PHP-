-- Données de test (Seed) pour la base de données de gestion d'événements
USE gestion_evenements;

-- Suppression des anciennes données dans l'ordre des dépendances (enfants d'abord)
DELETE FROM billet;
DELETE FROM reservation;
DELETE FROM evenement;
DELETE FROM participant;
DELETE FROM organisateur;
DELETE FROM users;

-- Réinitialisation des compteurs d'auto-incrément à 1
ALTER TABLE billet AUTO_INCREMENT = 1;
ALTER TABLE reservation AUTO_INCREMENT = 1;
ALTER TABLE evenement AUTO_INCREMENT = 1;
ALTER TABLE participant AUTO_INCREMENT = 1;
ALTER TABLE organisateur AUTO_INCREMENT = 1;
ALTER TABLE users AUTO_INCREMENT = 1;

-- Insertion des Utilisateurs (pour Authentification)
INSERT INTO users (nom, prenom, email, telephone, mot_de_passe, role) VALUES
('Admin', 'Super', 'admin@events.ga', '+241 01 00 00 00', '$2y$10$go0iQorMKg.Q4QVjaRWtIesp9GH4dNyM1smSxvQY/NpXjqzlmEZOa', 'admin'),
('Institut Français', 'IFG', 'contact@ifgabon.com', '+241 11 76 13 40', '$2y$10$Pi.rUx5oZym4RNlIyPpFA.gFt4D9LmQUvYaAPVMeODqBgHwK3Yvx.', 'organisateur'),
('Mba', 'Jean-Pierre', 'jp.mba@gmail.com', '+241 07 12 34 56', '$2y$10$Qdo/tBSxfHJJ5XTDdZdQ1uXyCjX3Gr7ly/mm4XJ0eTmYcchUTZ.P.', 'participant');

-- Insertion des Organisateurs
INSERT INTO organisateur (nom, email, telephone, entreprise) VALUES
('Institut Français du Gabon', 'contact@ifgabon.com', '+241 11 76 13 40', 'IFG Libreville'),
('Gabon Tech Hub', 'event@gabontech.ga', '+241 07 45 92 10', 'Gabon Tech Hub Co.'),
('Akewa Accélérateur', 'info@akewa.org', '+241 06 12 34 56', 'Akewa Accel GIE'),
('Direct Prod Gabon', 'billetterie@directprod.net', '+241 07 88 99 00', 'Direct Prod');

-- Insertion des Événements
INSERT INTO evenement (titre, description, date, lieu, capacite, prix_billet, organisateur_id, image_url) VALUES
(
    'Concert de Jazz de l''Estuaire',
    'Rejoignez-nous pour une soirée inoubliable de jazz et d''afro-beat au bord de la mer. Avec des artistes locaux et internationaux de renom.',
    '2026-06-15 20:00:00',
    'Salle de Spectacles, Institut Français du Gabon, Libreville',
    150,
    15000.00,
    1,
    'https://images.unsplash.com/photo-1511192336575-5a79af67a629?q=80&w=800'
),
(
    'Gabon Digital Forum 2026',
    'Le rendez-vous inconrtournable de la tech en Afrique Centrale. Conférences, ateliers de programmation, et réseautage sur l''intelligence artificielle et l''inclusion financière.',
    '2026-07-10 09:00:00',
    'Hôtel Nomade, Libreville',
    300,
    25000.00,
    2,
    'https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=800'
),
(
    'Showcase Show Live au Port-Gentil',
    'Le grand retour de la musique urbaine gabonaise. Artistes invités exceptionnels pour célébrer la culture locale.',
    '2026-06-28 21:00:00',
    'Complexe Sportif de Port-Gentil',
    500,
    5000.00,
    4,
    'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?q=80&w=800'
),
(
    'Atelier Entreprenariat Vert et Durable',
    'Une journée de formation pratique pour les jeunes créateurs d''entreprises à Libreville. Apprenez à monter un business plan écologique.',
    '2026-08-05 10:00:00',
    'Espace PME Gabon, Awendjé, Libreville',
    50,
    0.00,
    3,
    'https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=800'
);

-- Insertion des Participants de Test
INSERT INTO participant (nom, prenom, email, telephone) VALUES
('Mba', 'Jean-Pierre', 'jp.mba@gmail.com', '+241 07 12 34 56'),
('Ndong', 'Sarah', 'sarah.ndong@outlook.com', '+241 06 98 76 54'),
('Boussougou', 'Marc', 'm.boussougou@gmail.com', '+241 07 45 45 45'),
('Obame', 'Christian', 'c.obame@yahoo.fr', '+241 05 33 22 11');

-- Insertion des Réservations
INSERT INTO reservation (evenement_id, participant_id, nb_places, montant, statut) VALUES
(1, 1, 2, 30000.00, 'confirme'),
(1, 2, 1, 15000.00, 'confirme'),
(2, 3, 1, 25000.00, 'confirme'),
(4, 4, 1, 0.00, 'confirme');

-- Insertion des Billets
INSERT INTO billet (reservation_id, code_qr, statut) VALUES
(1, 'BILLET-15A8D-E92', 'valide'),
(1, 'BILLET-15A8D-E93', 'valide'),
(2, 'BILLET-92C1D-F81', 'valide'),
(3, 'BILLET-33B2B-B92', 'utilise'),
(4, 'BILLET-FREE-001', 'valide');
