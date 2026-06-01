<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div style="max-width: 700px; margin: 40px auto;">
    <div style="margin-bottom: 20px;">
        <a href="index.php?page=evenements&action=detail&id=<?= $evenement->id ?>" style="color: var(--text-muted);"><i class="fa-solid fa-arrow-left"></i> Annuler</a>
    </div>

    <div class="card-glass">
        <h2 style="font-size: 1.8rem; font-weight: 800; margin-bottom: 20px; border-bottom: 1px solid var(--border-glass); padding-bottom: 10px; color: var(--accent);"><i class="fa-solid fa-calendar-days"></i> Modifier l'Événement</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" style="margin-bottom: 20px;">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <div><?= htmlspecialchars($error) ?></div>
            </div>
        <?php endif; ?>

        <form action="index.php?page=evenements&action=modifier&id=<?= $evenement->id ?>" method="POST">
            <div class="form-group">
                <label for="titre" class="form-label">Titre de l'événement *</label>
                <input type="text" name="titre" id="titre" class="form-control" required value="<?= htmlspecialchars($evenement->titre) ?>">
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description / Détails</label>
                <textarea name="description" id="description" class="form-control"><?= htmlspecialchars($evenement->description) ?></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="date" class="form-label">Date et Heure *</label>
                    <input type="datetime-local" name="date" id="date" class="form-control" required value="<?= date('Y-m-d\TH:i', strtotime($evenement->date)) ?>">
                </div>

                <div class="form-group">
                    <label for="lieu" class="form-label">Lieu et Ville *</label>
                    <input type="text" name="lieu" id="lieu" class="form-control" required value="<?= htmlspecialchars($evenement->lieu) ?>">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="capacite" class="form-label">Capacité maximale (places) *</label>
                    <input type="number" name="capacite" id="capacite" class="form-control" min="1" required value="<?= (int)$evenement->capacite ?>">
                </div>

                <div class="form-group">
                    <label for="prix_billet" class="form-label">Prix du billet (FCFA) *</label>
                    <input type="number" name="prix_billet" id="prix_billet" class="form-control" min="0" step="1" required value="<?= (float)$evenement->prix_billet ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="image_url" class="form-label">Lien d'image de couverture (URL)</label>
                <input type="url" name="image_url" id="image_url" class="form-control" value="<?= htmlspecialchars($evenement->image_url) ?>">
            </div>

            <?php if (isAdmin()): ?>
                <div class="form-group">
                    <label for="organisateur_id" class="form-label">Organisateur (Admin uniquement)</label>
                    <select name="organisateur_id" id="organisateur_id" class="form-control">
                        <?php foreach ($organisateurs as $org): ?>
                            <option value="<?= $org->id ?>" <?= $evenement->organisateur_id == $org->id ? 'selected' : '' ?>><?= htmlspecialchars($org->nom) ?> (<?= htmlspecialchars($org->entreprise) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 15px; padding: 14px;"><i class="fa-solid fa-floppy-disk"></i> Enregistrer les Modifications</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
