<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div style="max-width: 600px; margin: 40px auto;">
    <div style="margin-bottom: 20px;">
        <a href="index.php?page=evenements&action=detail&id=<?= $evenement->id ?>" style="color: var(--text-muted);"><i class="fa-solid fa-arrow-left"></i> Annuler</a>
    </div>

    <!-- Event Summary Summary Card -->
    <div class="card-glass" style="margin-bottom: 25px; border-left: 5px solid var(--primary); padding: 20px;">
        <span style="color: var(--text-muted); font-size: 0.8rem; font-weight: 600; display: block; text-transform: uppercase;">Événement sélectionné</span>
        <h3 style="font-size: 1.3rem; font-weight: 700; color: white; margin-top: 5px;"><?= htmlspecialchars($evenement->titre) ?></h3>
        <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 5px;">
            <i class="fa-solid fa-calendar-day" style="color: var(--primary); margin-right: 5px;"></i> <?= formatDate($evenement->date) ?> 
            <span style="margin: 0 10px;">|</span>
            <i class="fa-solid fa-location-dot" style="color: var(--secondary); margin-right: 5px;"></i> <?= htmlspecialchars($evenement->lieu) ?>
        </p>
    </div>

    <!-- Booking Form Card -->
    <div class="card-glass">
        <h2 style="font-size: 1.6rem; font-weight: 800; margin-bottom: 20px; border-bottom: 1px solid var(--border-glass); padding-bottom: 10px; color: var(--accent);"><i class="fa-solid fa-chair"></i> Formulaire de Réservation</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" style="margin-bottom: 20px;">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <div><?= htmlspecialchars($error) ?></div>
            </div>
        <?php endif; ?>

        <form action="index.php?page=reservations&action=creer" method="POST">
            <input type="hidden" name="evenement_id" value="<?= $evenement->id ?>">

            <!-- Participant Information -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label for="nom" class="form-label">Nom *</label>
                    <input type="text" name="nom" id="nom" class="form-control" required 
                           value="<?= isLoggedIn() ? htmlspecialchars($_SESSION['user']->nom) : (isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '') ?>">
                </div>

                <div class="form-group">
                    <label for="prenom" class="form-label">Prénom *</label>
                    <input type="text" name="prenom" id="prenom" class="form-control" required 
                           value="<?= isLoggedIn() ? htmlspecialchars($_SESSION['user']->prenom) : (isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Adresse Email *</label>
                <input type="email" name="email" id="email" class="form-control" required 
                       value="<?= isLoggedIn() ? htmlspecialchars($_SESSION['user']->email) : (isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '') ?>">
            </div>

            <div class="form-group">
                <label for="telephone" class="form-label">Téléphone</label>
                <input type="tel" name="telephone" id="telephone" class="form-control" placeholder="+241 07 ..." 
                       value="<?= isLoggedIn() ? htmlspecialchars($_SESSION['user']->telephone) : (isset($_POST['telephone']) ? htmlspecialchars($_POST['telephone']) : '') ?>">
            </div>

            <!-- Ticket count with maximum constraint -->
            <div class="form-group">
                <label for="nb_places" class="form-label">Nombre de places à réserver (Max. <?= $placesDisponibles ?>) *</label>
                <input type="number" name="nb_places" id="nb_places" class="form-control" min="1" max="<?= $placesDisponibles ?>" 
                       value="<?= isset($_POST['nb_places']) ? (int)$_POST['nb_places'] : 1 ?>" 
                       data-price="<?= (float)$evenement->prix_billet ?>" required>
            </div>

            <!-- Dynamic Price Calculation Panel -->
            <div class="card-glass" style="background: rgba(255, 255, 255, 0.01); border: 1px solid var(--border-glass); padding: 15px; margin-bottom: 25px; text-align: center;">
                <span style="font-size: 0.85rem; color: var(--text-muted); display: block; text-transform: uppercase;">Montant total de votre commande</span>
                <span id="total-price-display" style="font-size: 1.8rem; font-weight: 800; color: var(--accent); margin-top: 5px; display: block;">0 FCFA</span>
                <span style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-top: 4px;">Tarif unitaire : <?= formatPrix($evenement->prix_billet) ?></span>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px;"><i class="fa-solid fa-circle-check"></i> Confirmer et Réserver</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
