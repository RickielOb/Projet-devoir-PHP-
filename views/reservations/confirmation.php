<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div style="max-width: 800px; margin: 40px auto; text-align: center;">
    <!-- Success Badge -->
    <div style="margin-bottom: 30px; animation: slideUp 0.5s ease-out;">
        <i class="fa-solid fa-circle-check fa-5x" style="color: var(--success); filter: drop-shadow(0 0 15px rgba(6, 214, 160, 0.4));"></i>
        <h1 style="font-size: 2.5rem; font-weight: 800; margin-top: 15px;">Réservation Confirmée !</h1>
        <p style="color: var(--text-muted); font-size: 1.1rem; margin-top: 5px;">Votre commande a été traitée avec succès. Vous trouverez vos billets ci-dessous.</p>
    </div>

    <!-- Booking Summary Card -->
    <div class="card-glass" style="max-width: 600px; margin: 0 auto 40px auto; text-align: left; border-left: 5px solid var(--success);">
        <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 15px; border-bottom: 1px solid var(--border-glass); padding-bottom: 8px; color: var(--accent);">Récapitulatif de la commande</h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; font-size: 0.95rem;">
            <div>
                <span style="color: var(--text-muted); font-size: 0.8rem; display: block;">ÉVÉNEMENT</span>
                <strong><?= htmlspecialchars($reservation->evenement_titre) ?></strong>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.8rem; display: block;">PARTICIPANT</span>
                <strong><?= htmlspecialchars($reservation->participant_prenom) ?> <?= htmlspecialchars($reservation->participant_nom) ?></strong>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.8rem; display: block;">PLACES RÉSERVÉES</span>
                <strong><?= $reservation->nb_places ?> place(s)</strong>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.8rem; display: block;">MONTANT TOTAL</span>
                <strong style="color: var(--accent);"><?= formatPrix($reservation->montant) ?></strong>
            </div>
        </div>
    </div>

    <!-- Generated Tickets List -->
    <h2 style="font-size: 1.8rem; font-weight: 800; margin-bottom: 25px;">Vos Billets Numériques</h2>
    
    <div style="display: flex; flex-direction: column; gap: 40px;">
        <?php foreach ($billets as $index => $billet): ?>
            <div class="ticket">
                <!-- Left Section (Infos) -->
                <div class="ticket-left" style="text-align: left;">
                    <div class="ticket-header">
                        <span class="ticket-logo"><i class="fa-solid fa-ticket-simple"></i> Billet #<?= $index + 1 ?></span>
                        <span class="ticket-statut valide">Valide</span>
                    </div>

                    <h3 class="ticket-title"><?= htmlspecialchars($billet->evenement_titre) ?></h3>

                    <div class="ticket-info-grid">
                        <div class="ticket-info-item">
                            DATE ET HEURE
                            <strong><?= formatDate($billet->evenement_date) ?></strong>
                        </div>
                        <div class="ticket-info-item">
                            LIEU
                            <strong><?= htmlspecialchars($billet->evenement_lieu) ?></strong>
                        </div>
                        <div class="ticket-info-item">
                            PARTICIPANT
                            <strong><?= htmlspecialchars($billet->participant_prenom) ?> <?= htmlspecialchars($billet->participant_nom) ?></strong>
                        </div>
                        <div class="ticket-info-item">
                            CODE RÉSERVATION
                            <strong>#RES-<?= $reservation->id ?></strong>
                        </div>
                    </div>
                </div>

                <!-- Right Section (QR) -->
                <div class="ticket-right">
                    <!-- JS app.js injects QRCode into this container -->
                    <div class="ticket-qrcode" data-qr-code="<?= htmlspecialchars($billet->code_qr) ?>">
                        <!-- Fallback placeholder during load -->
                        <i class="fa-solid fa-spinner fa-spin fa-xl" style="color: black;"></i>
                    </div>
                    <span class="ticket-code-str"><?= htmlspecialchars($billet->code_qr) ?></span>
                    <a href="index.php?page=billets&action=afficher&code=<?= urlencode($billet->code_qr) ?>" class="btn btn-outline mt-3 no-print" style="padding: 6px 12px; font-size: 0.75rem; width: 100%;">Imprimer / Télécharger</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Actions -->
    <div class="no-print" style="margin-top: 50px; display: flex; gap: 15px; justify-content: center;">
        <a href="index.php" class="btn btn-primary"><i class="fa-solid fa-house"></i> Retour à l'accueil</a>
        <button onclick="window.print()" class="btn btn-outline"><i class="fa-solid fa-print"></i> Tout imprimer</button>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
