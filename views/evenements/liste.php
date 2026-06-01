<?php require_once __DIR__ . '/../layout/header.php'; ?>

<section style="margin-bottom: 20px;">
    <span style="color: var(--primary); font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Billetterie</span>
    <h1 style="font-size: 2.2rem; font-weight: 800; margin-top: 5px;">Tous les Événements au Gabon</h1>
</section>

<!-- Search and Filter Panel -->
<div class="search-filter-box">
    <form action="index.php" method="GET" class="filter-grid">
        <input type="hidden" name="page" value="evenements">
        <input type="hidden" name="action" value="liste">
        
        <div class="form-group" style="margin-bottom: 0;">
            <label for="q" class="form-label" style="font-size: 0.8rem;">Rechercher un mot-clé</label>
            <input type="text" name="q" id="q" class="form-control" placeholder="Concert, Jazz, Tech..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label for="lieu" class="form-label" style="font-size: 0.8rem;">Lieu / Ville</label>
            <input type="text" name="lieu" id="lieu" class="form-control" placeholder="Libreville, Port-Gentil..." value="<?= htmlspecialchars($_GET['lieu'] ?? '') ?>">
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label for="date_debut" class="form-label" style="font-size: 0.8rem;">Date début</label>
            <input type="date" name="date_debut" id="date_debut" class="form-control" value="<?= htmlspecialchars($_GET['date_debut'] ?? '') ?>">
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label for="date_fin" class="form-label" style="font-size: 0.8rem;">Date fin</label>
            <input type="date" name="date_fin" id="date_fin" class="form-control" value="<?= htmlspecialchars($_GET['date_fin'] ?? '') ?>">
        </div>

        <button type="submit" class="btn btn-primary" style="padding: 12px 20px;"><i class="fa-solid fa-magnifying-glass"></i> Filtrer</button>
    </form>
</div>

<!-- Events Grid -->
<?php if (empty($evenements)): ?>
    <div class="card-glass text-center py-5">
        <i class="fa-regular fa-folder-open fa-3x mb-3" style="color: var(--text-muted);"></i>
        <h3>Aucun événement trouvé</h3>
        <p style="color: var(--text-muted); margin-bottom: 20px;">Désolé, aucun événement ne correspond à vos critères de recherche.</p>
        <a href="index.php?page=evenements" class="btn btn-outline">Réinitialiser les filtres</a>
    </div>
<?php else: ?>
    <div class="grid-3">
        <?php foreach ($evenements as $event): ?>
            <?php 
                $dispo = $placesDisponibles[$event->id] ?? 0;
                $isFull = $dispo <= 0;
            ?>
            <div class="event-card">
                <div class="event-card-img">
                    <img src="<?= htmlspecialchars($event->image_url) ?>" alt="<?= htmlspecialchars($event->titre) ?>">
                    <span class="event-card-badge <?= $event->prix_billet == 0 ? 'gratuit' : 'payant' ?>">
                        <?= $event->prix_billet == 0 ? 'Gratuit' : 'Premium' ?>
                    </span>
                </div>
                
                <div class="event-card-content">
                    <h3 class="event-card-title"><?= htmlspecialchars($event->titre) ?></h3>
                    
                    <ul class="event-card-meta">
                        <li><i class="fa-solid fa-calendar-day"></i> <?= formatDate($event->date) ?></li>
                        <li><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($event->lieu) ?></li>
                        <li>
                            <i class="fa-solid fa-chair"></i>
                            <?php if ($isFull): ?>
                                <span class="badge-seats full">Complet</span>
                            <?php else: ?>
                                <span class="badge-seats available"><?= $dispo ?> places restantes</span>
                            <?php endif; ?>
                        </li>
                    </ul>
                    
                    <div class="event-card-footer">
                        <div class="event-price"><?= formatPrix($event->prix_billet) ?></div>
                        <a href="index.php?page=evenements&action=detail&id=<?= $event->id ?>" class="btn btn-outline" style="padding: 8px 16px; font-size: 0.85rem;">Détails <i class="fa-solid fa-arrow-right-long" style="margin-left: 5px;"></i></a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
