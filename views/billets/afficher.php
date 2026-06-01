<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="no-print" style="margin-bottom: 20px;">
    <a href="javascript:history.back()" style="color: var(--text-muted); font-weight: 500;"><i class="fa-solid fa-arrow-left"></i> Retour</a>
</div>

<div class="ticket-container">
    <div class="ticket">
        <!-- Left Section (Infos) -->
        <div class="ticket-left">
            <div class="ticket-header">
                <span class="ticket-logo"><i class="fa-solid fa-ticket-simple"></i> EventGabon Billet</span>
                <?php 
                    $statutClass = 'valide';
                    $statutLabel = 'Valide';
                    if ($billet->statut === 'utilise') {
                        $statutClass = 'utilise';
                        $statutLabel = 'Déjà utilisé';
                    } elseif ($billet->statut === 'invalide') {
                        $statutClass = 'invalide';
                        $statutLabel = 'Annulé';
                    }
                ?>
                <span class="ticket-statut <?= $statutClass ?>"><?= $statutLabel ?></span>
            </div>

            <h1 class="ticket-title" style="margin-bottom: 25px;"><?= htmlspecialchars($billet->evenement_titre) ?></h1>

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
                    TÉLÉPHONE
                    <strong><?= htmlspecialchars($billet->participant_tel ?: 'Non renseigné') ?></strong>
                </div>
                <div class="ticket-info-item">
                    PRIX
                    <strong><?= formatPrix($billet->prix_billet) ?></strong>
                </div>
                <div class="ticket-info-item">
                    RÉSERVATION
                    <strong>#RES-<?= $billet->reservation_id ?></strong>
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
            <span class="ticket-code-str" style="margin-bottom: 10px;"><?= htmlspecialchars($billet->code_qr) ?></span>
            <button onclick="window.print()" class="btn btn-primary no-print" style="width: 100%; padding: 8px 16px; font-size: 0.85rem;"><i class="fa-solid fa-print"></i> Imprimer</button>
        </div>
    </div>
</div>

<div class="no-print" style="text-align: center; margin-top: 30px;">
    <p style="color: var(--text-muted); font-size: 0.9rem;"><i class="fa-solid fa-circle-info"></i> Présentez ce code QR sur votre smartphone ou sur papier imprimé à l'entrée de l'événement.</p>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
