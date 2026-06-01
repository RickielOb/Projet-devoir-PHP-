<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div style="margin-bottom: 20px;">
    <a href="index.php?page=evenements" style="color: var(--text-muted); font-weight: 500;"><i class="fa-solid fa-arrow-left"></i> Retour aux événements</a>
</div>

<div class="card-glass" style="padding: 0; overflow: hidden; border-radius: 24px; margin-bottom: 40px;">
    <!-- Large event cover image -->
    <div style="height: 380px; width: 100%; position: relative;">
        <img src="<?= htmlspecialchars($evenement->image_url) ?>" alt="<?= htmlspecialchars($evenement->titre) ?>" style="width: 100%; height: 100%; object-fit: cover;">
        <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(6, 9, 25, 1) 0%, rgba(6, 9, 25, 0.4) 60%, transparent 100%); height: 200px;"></div>
        
        <div style="position: absolute; bottom: 30px; left: 30px; right: 30px;">
            <span style="background: var(--primary); padding: 5px 12px; border-radius: 30px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;"><?= htmlspecialchars($evenement->organisateur_nom ?? 'Événement Spécial') ?></span>
            <h1 style="font-size: 2.8rem; font-weight: 800; line-height: 1.2; margin-top: 10px; text-shadow: 0 2px 10px rgba(0,0,0,0.8);"><?= htmlspecialchars($evenement->titre) ?></h1>
        </div>
    </div>

    <!-- Details Grid -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px; padding: 40px; background: rgba(6, 9, 25, 0.4);">
        <!-- Left Side: Description & Info -->
        <div>
            <h2 style="font-size: 1.4rem; font-weight: 700; margin-bottom: 15px; border-bottom: 1px solid var(--border-glass); padding-bottom: 10px; color: var(--accent);">À propos de l'événement</h2>
            <p style="color: var(--text-primary); font-size: 1.05rem; line-height: 1.8; white-space: pre-line; margin-bottom: 30px;">
                <?= htmlspecialchars($evenement->description ?: "Aucune description fournie pour cet événement.") ?>
            </p>
            
            <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 15px;">Informations Pratiques</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="card-glass" style="padding: 20px; display: flex; gap: 15px; align-items: center;">
                    <i class="fa-solid fa-calendar-days fa-2x" style="color: var(--primary);"></i>
                    <div>
                        <span style="font-size: 0.8rem; color: var(--text-muted); display: block;">DATE ET HEURE</span>
                        <strong><?= formatDate($evenement->date) ?></strong>
                    </div>
                </div>

                <div class="card-glass" style="padding: 20px; display: flex; gap: 15px; align-items: center;">
                    <i class="fa-solid fa-location-dot fa-2x" style="color: var(--secondary);"></i>
                    <div>
                        <span style="font-size: 0.8rem; color: var(--text-muted); display: block;">LIEU</span>
                        <strong><?= htmlspecialchars($evenement->lieu) ?></strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Booking Panel -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <div class="card-glass" style="background: rgba(255, 255, 255, 0.02); border: 2px solid var(--border-glass); position: sticky; top: 100px;">
                <div style="text-align: center; margin-bottom: 25px;">
                    <span style="color: var(--text-muted); font-size: 0.85rem; font-weight: 600; display: block; text-transform: uppercase;">Tarif Unique</span>
                    <span style="font-size: 2rem; font-weight: 800; color: var(--accent); margin-top: 5px; display: block;"><?= formatPrix($evenement->prix_billet) ?></span>
                </div>

                <!-- Places & progress bar -->
                <div style="margin-bottom: 25px;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 8px;">
                        <span style="color: var(--text-muted);">Places disponibles</span>
                        <strong><?= $placesDispo ?> / <?= $evenement->capacite ?></strong>
                    </div>
                    <?php 
                        $percentage = (int)(($placesDispo / $evenement->capacite) * 100);
                        $barClass = '';
                        if ($percentage < 15) {
                            $barClass = 'danger';
                        } elseif ($percentage < 40) {
                            $barClass = 'warning';
                        }
                    ?>
                    <div class="progress-container">
                        <div class="progress-bar <?= $barClass ?>" style="width: <?= $percentage ?>%;"></div>
                    </div>
                </div>

                <?php if ($placesDispo > 0): ?>
                    <a href="index.php?page=reservations&action=creer&evenement_id=<?= $evenement->id ?>" class="btn btn-primary" style="width: 100%; padding: 14px;"><i class="fa-solid fa-chair"></i> Réserver mes places</a>
                <?php else: ?>
                    <button class="btn btn-secondary" style="width: 100%; padding: 14px; opacity: 0.6; cursor: not-allowed;" disabled><i class="fa-solid fa-circle-xmark"></i> Complet</button>
                <?php endif; ?>

                <!-- Owner / Admin Controls -->
                <?php 
                    $canEdit = false;
                    if (isLoggedIn()) {
                        if (isAdmin()) {
                            $canEdit = true;
                        } elseif (isOrganisateur()) {
                            $userEmail = $_SESSION['user']->email;
                            if ($evenement->organisateur_email === $userEmail) {
                                $canEdit = true;
                            }
                        }
                    }
                ?>
                <?php if ($canEdit): ?>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 25px; border-top: 1px solid var(--border-glass); padding-top: 20px;">
                        <a href="index.php?page=evenements&action=modifier&id=<?= $evenement->id ?>" class="btn btn-outline" style="padding: 10px;"><i class="fa-solid fa-pen-to-square"></i> Éditer</a>
                        <a href="index.php?page=evenements&action=supprimer&id=<?= $evenement->id ?>" class="btn btn-secondary" style="padding: 10px; background: linear-gradient(135deg, var(--error) 0%, #aa1a3c 100%);" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement définitivement ?');"><i class="fa-solid fa-trash-can"></i> Supprimer</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
