<?php
// C:\xampp\htdocs\gestion-evenements\models\Evenement.php

class Evenement {
    public static function getAll($pdo) {
        $sql = "SELECT e.*, o.nom AS organisateur_nom 
                FROM evenement e 
                LEFT JOIN organisateur o ON e.organisateur_id = o.id 
                ORDER BY e.date ASC";
        return $pdo->query($sql)->fetchAll();
    }

    public static function getById($pdo, $id) {
        $sql = "SELECT e.*, o.nom AS organisateur_nom, o.email AS organisateur_email, o.telephone AS organisateur_tel 
                FROM evenement e 
                LEFT JOIN organisateur o ON e.organisateur_id = o.id 
                WHERE e.id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function getUpcoming($pdo, $limit = 6) {
        $sql = "SELECT e.*, o.nom AS organisateur_nom 
                FROM evenement e 
                LEFT JOIN organisateur o ON e.organisateur_id = o.id 
                WHERE e.date >= NOW() 
                ORDER BY e.date ASC 
                LIMIT " . (int)$limit;
        return $pdo->query($sql)->fetchAll();
    }

    public static function create($pdo, $data) {
        $sql = "INSERT INTO evenement (titre, description, date, lieu, capacite, prix_billet, organisateur_id, image_url) 
                VALUES (:titre, :description, :date, :lieu, :capacite, :prix_billet, :organisateur_id, :image_url)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public static function update($pdo, $id, $data) {
        $sql = "UPDATE evenement 
                SET titre = :titre, description = :description, date = :date, lieu = :lieu, 
                    capacite = :capacite, prix_billet = :prix_billet, organisateur_id = :organisateur_id, image_url = :image_url 
                WHERE id = :id";
        $data['id'] = $id;
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public static function delete($pdo, $id) {
        $sql = "DELETE FROM evenement WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    public static function search($pdo, $q = '', $lieu = '', $dateDebut = '', $dateFin = '') {
        $sql = "SELECT e.*, o.nom AS organisateur_nom 
                FROM evenement e 
                LEFT JOIN organisateur o ON e.organisateur_id = o.id 
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($q)) {
            $sql .= " AND (e.titre LIKE :q OR e.description LIKE :q)";
            $params['q'] = '%' . $q . '%';
        }
        if (!empty($lieu)) {
            $sql .= " AND e.lieu LIKE :lieu";
            $params['lieu'] = '%' . $lieu . '%';
        }
        if (!empty($dateDebut)) {
            $sql .= " AND e.date >= :dateDebut";
            $params['dateDebut'] = $dateDebut . ' 00:00:00';
        }
        if (!empty($dateFin)) {
            $sql .= " AND e.date <= :dateFin";
            $params['dateFin'] = $dateFin . ' 23:59:59';
        }
        
        $sql .= " ORDER BY e.date ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function getPlacesDisponibles($pdo, $id) {
        // Obtenir la capacité totale de l'événement
        $sqlCap = "SELECT capacite FROM evenement WHERE id = ?";
        $stmtCap = $pdo->prepare($sqlCap);
        $stmtCap->execute([$id]);
        $event = $stmtCap->fetch();
        
        if (!$event) return 0;
        
        // Calculer la somme des places réservées
        $sqlRes = "SELECT SUM(nb_places) AS total_reserve 
                   FROM reservation 
                   WHERE evenement_id = ? AND statut = 'confirme'";
        $stmtRes = $pdo->prepare($sqlRes);
        $stmtRes->execute([$id]);
        $res = $stmtRes->fetch();
        
        $totalReserve = $res ? (int)$res->total_reserve : 0;
        
        return max(0, (int)$event->capacite - $totalReserve);
    }

    public static function getPaginated($pdo, $offset, $limit, $q = '', $lieu = '', $dateDebut = '', $dateFin = '') {
        $sql = "SELECT e.*, o.nom AS organisateur_nom 
                FROM evenement e 
                LEFT JOIN organisateur o ON e.organisateur_id = o.id 
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($q)) {
            $sql .= " AND (e.titre LIKE :q OR e.description LIKE :q)";
            $params['q'] = '%' . $q . '%';
        }
        if (!empty($lieu)) {
            $sql .= " AND e.lieu LIKE :lieu";
            $params['lieu'] = '%' . $lieu . '%';
        }
        if (!empty($dateDebut)) {
            $sql .= " AND e.date >= :dateDebut";
            $params['dateDebut'] = $dateDebut . ' 00:00:00';
        }
        if (!empty($dateFin)) {
            $sql .= " AND e.date <= :dateFin";
            $params['dateFin'] = $dateFin . ' 23:59:59';
        }
        
        $sql .= " ORDER BY e.date ASC LIMIT :offset, :limit";
        
        $stmt = $pdo->prepare($sql);
        
        // Associer les paramètres normaux
        foreach ($params as $key => $val) {
            $stmt->bindValue(':' . $key, $val);
        }
        // Associer offset et limit explicitement en tant qu'entiers pour PDO
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function countWithFilters($pdo, $q = '', $lieu = '', $dateDebut = '', $dateFin = '') {
        $sql = "SELECT COUNT(*) FROM evenement e WHERE 1=1";
        $params = [];
        
        if (!empty($q)) {
            $sql .= " AND (e.titre LIKE :q OR e.description LIKE :q)";
            $params['q'] = '%' . $q . '%';
        }
        if (!empty($lieu)) {
            $sql .= " AND e.lieu LIKE :lieu";
            $params['lieu'] = '%' . $lieu . '%';
        }
        if (!empty($dateDebut)) {
            $sql .= " AND e.date >= :dateDebut";
            $params['dateDebut'] = $dateDebut . ' 00:00:00';
        }
        if (!empty($dateFin)) {
            $sql .= " AND e.date <= :dateFin";
            $params['dateFin'] = $dateFin . ' 23:59:59';
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }
}
