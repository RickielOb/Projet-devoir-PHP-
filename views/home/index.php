<?php require_once __DIR__ . '/../layout/header.php'; ?>

<!-- Hero Section -->
<section class="hero">
    <h1>Plateforme de Réservation d'Événements au Gabon</h1>
    <p>Planifiez votre participation aux concerts, conférences, séminaires et salons professionnels. Réservez vos billets officiels en ligne et obtenez instantanément vos codes d'accès sécurisés.</p>
    <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
        <a href="index.php?page=evenements" class="btn btn-primary">Consulter le Catalogue <i class="fa-solid fa-arrow-right"></i></a>
        <?php if (!isLoggedIn()): ?>
            <a href="index.php?page=auth&action=register" class="btn btn-outline">Créer un compte professionnel</a>
        <?php endif; ?>
    </div>
</section>

<!-- Stats counters section -->
<section class="stats-grid" style="margin: 40px 0;">
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-label">Événements répertoriés</span>
            <span class="stat-value">Actifs</span>
        </div>
        <div class="stat-icon">
            <i class="fa-solid fa-calendar-days"></i>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-label">Billetterie officielle</span>
            <span class="stat-value">Certifiée</span>
        </div>
        <div class="stat-icon">
            <i class="fa-solid fa-qrcode"></i>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-label">Contrôle des entrées</span>
            <span class="stat-value">Sécurisé</span>
        </div>
        <div class="stat-icon">
            <i class="fa-solid fa-shield-halved"></i>
        </div>
    </div>
</section>

<!-- Upcoming Events Section -->
<section style="margin-top: 60px;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px;">
        <div>
            <span style="color: var(--primary); font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Sélection</span>
            <h2 style="font-size: 2rem; font-weight: 800; margin-top: 5px;">Événements à Venir</h2>
        </div>
        <a href="index.php?page=evenements" style="font-weight: 600;">Voir tout <i class="fa-solid fa-chevron-right" style="font-size: 0.8rem;"></i></a>
    </div>

    <?php if (empty($evenements)): ?>
        <div class="card-glass text-center py-5">
            <i class="fa-regular fa-calendar fa-3x mb-3" style="color: var(--text-muted);"></i>
            <h3>Aucun événement à venir</h3>
            <p style="color: var(--text-muted);">Revenez plus tard pour découvrir de nouveaux événements au Gabon.</p>
        </div>
    <?php else: ?>
        <div class="grid-3">
            <?php foreach ($evenements as $event): ?>
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
</section>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
