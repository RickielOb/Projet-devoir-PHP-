<?php require_once __DIR__ . '/../layout/header.php'; ?>

<section style="margin-bottom: 30px;">
    <span style="color: var(--primary); font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Administration</span>
    <h1 style="font-size: 2.2rem; font-weight: 800; margin-top: 5px;"><i class="fa-solid fa-chart-line"></i> Tableau de bord</h1>
</section>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-label">Total Événements</span>
            <span class="stat-value"><?= $totalEvenements ?></span>
        </div>
        <div class="stat-icon">
            <i class="fa-solid fa-calendar-check"></i>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-label">Total Réservations</span>
            <span class="stat-value"><?= $totalReservations ?></span>
        </div>
        <div class="stat-icon">
            <i class="fa-solid fa-chair"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-label">Billets Validés</span>
            <span class="stat-value"><?= $billetsUtilises ?> / <?= $totalBillets ?></span>
        </div>
        <div class="stat-icon">
            <i class="fa-solid fa-circle-check"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-label">Revenus Totaux</span>
            <span class="stat-value" style="color: var(--success);"><?= formatPrix($totalRevenus) ?></span>
        </div>
        <div class="stat-icon">
            <i class="fa-solid fa-sack-dollar"></i>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px; margin-top: 40px;">
    <!-- Recent Bookings Table -->
    <div>
        <h2 style="font-size: 1.4rem; font-weight: 700; margin-bottom: 20px;"><i class="fa-solid fa-receipt"></i> Réservations Récentes</h2>
        <div class="card-glass" style="padding: 0; overflow: hidden;">
            <div class="table-responsive">
                <table class="table-glass">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Événement</th>
                            <th>Participant</th>
                            <th>Places</th>
                            <th>Montant</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reservations)): ?>
                            <tr>
                                <td colspan="6" class="text-center" style="text-align: center; color: var(--text-muted);">Aucune réservation pour le moment.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($reservations as $res): ?>
                                <tr>
                                    <td><strong>#<?= $res->id ?></strong></td>
                                    <td><?= htmlspecialchars($res->evenement_titre) ?></td>
                                    <td><?= htmlspecialchars($res->participant_prenom) ?> <?= htmlspecialchars($res->participant_nom) ?></td>
                                    <td><?= $res->nb_places ?></td>
                                    <td style="color: var(--accent); font-weight: 600;"><?= formatPrix($res->montant) ?></td>
                                    <td style="font-size: 0.85rem; color: var(--text-muted);"><?= date('d/m/Y H:i', strtotime($res->date)) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Platform Users Account Catalogs -->
    <div>
        <h2 style="font-size: 1.4rem; font-weight: 700; margin-bottom: 20px;"><i class="fa-solid fa-users"></i> Utilisateurs</h2>
        <div class="card-glass" style="padding: 20px; display: flex; flex-direction: column; gap: 15px;">
            <?php if (empty($users)): ?>
                <p style="color: var(--text-muted); text-align: center;">Aucun utilisateur inscrit.</p>
            <?php else: ?>
                <?php foreach ($users as $user): ?>
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-glass); padding-bottom: 10px;">
                        <div>
                            <strong style="display: block; font-size: 0.95rem;"><?= htmlspecialchars($user->prenom) ?> <?= htmlspecialchars($user->nom) ?></strong>
                            <span style="font-size: 0.8rem; color: var(--text-muted);"><?= htmlspecialchars($user->email) ?></span>
                        </div>
                        <?php 
                            $badgeStyle = 'background: rgba(255,255,255,0.05); color: white;';
                            if ($user->role === 'admin') {
                                $badgeStyle = 'background: var(--error-bg); color: var(--error);';
                            } elseif ($user->role === 'organisateur') {
                                $badgeStyle = 'background: var(--primary-glow); color: #9bc5ff;';
                            }
                        ?>
                        <span style="font-size: 0.75rem; padding: 4px 10px; border-radius: 20px; font-weight: 600; text-transform: uppercase; <?= $badgeStyle ?>">
                            <?= $user->role ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
