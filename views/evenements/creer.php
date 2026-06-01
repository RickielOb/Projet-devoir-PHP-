<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div style="max-width: 700px; margin: 40px auto;">
    <div style="margin-bottom: 20px;">
        <a href="index.php?page=evenements" style="color: var(--text-muted);"><i class="fa-solid fa-arrow-left"></i> Annuler</a>
    </div>

    <div class="card-glass">
        <h2 style="font-size: 1.8rem; font-weight: 800; margin-bottom: 20px; border-bottom: 1px solid var(--border-glass); padding-bottom: 10px; color: var(--accent);"><i class="fa-solid fa-calendar-plus"></i> Créer un Événement</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" style="margin-bottom: 20px;">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <div><?= htmlspecialchars($error) ?></div>
            </div>
        <?php endif; ?>

        <form action="index.php?page=evenements&action=creer" method="POST">
            <div class="form-group">
                <label for="titre" class="form-label">Titre de l'événement *</label>
                <input type="text" name="titre" id="titre" class="form-control" placeholder="Concert Live Akene..." required value="<?= isset($_POST['titre']) ? htmlspecialchars($_POST['titre']) : '' ?>">
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description / Détails</label>
                <textarea name="description" id="description" class="form-control" placeholder="Entrez le programme, les artistes invités, et autres informations importantes..."><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' ?></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="date" class="form-label">Date et Heure *</label>
                    <input type="datetime-local" name="date" id="date" class="form-control" required value="<?= isset($_POST['date']) ? htmlspecialchars($_POST['date']) : '' ?>">
                </div>

                <div class="form-group">
                    <label for="lieu" class="form-label">Lieu et Ville *</label>
                    <input type="text" name="lieu" id="lieu" class="form-control" placeholder="Salle des fêtes, Libreville" required value="<?= isset($_POST['lieu']) ? htmlspecialchars($_POST['lieu']) : '' ?>">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="capacite" class="form-label">Capacité maximale (places) *</label>
                    <input type="number" name="capacite" id="capacite" class="form-control" min="1" placeholder="150" required value="<?= isset($_POST['capacite']) ? (int)$_POST['capacite'] : '' ?>">
                </div>

                <div class="form-group">
                    <label for="prix_billet" class="form-label">Prix du billet (FCFA) *</label>
                    <input type="number" name="prix_billet" id="prix_billet" class="form-control" min="0" step="1" placeholder="0 pour Gratuit" required value="<?= isset($_POST['prix_billet']) ? (float)$_POST['prix_billet'] : '' ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="image_url" class="form-label">Lien d'image de couverture (URL)</label>
                <input type="url" name="image_url" id="image_url" class="form-control" placeholder="https://images.unsplash.com/..." value="<?= isset($_POST['image_url']) ? htmlspecialchars($_POST['image_url']) : '' ?>">
                <span style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-top: 5px;">Laissez vide pour utiliser une superbe image par défaut.</span>
            </div>

            <?php if (isAdmin()): ?>
                <div class="form-group">
                    <label for="organisateur_id" class="form-label">Organisateur (Admin uniquement)</label>
                    <select name="organisateur_id" id="organisateur_id" class="form-control">
                        <option value="">-- Sélectionner un Organisateur --</option>
                        <?php foreach ($organisateurs as $org): ?>
                            <option value="<?= $org->id ?>" <?= isset($_POST['organisateur_id']) && $_POST['organisateur_id'] == $org->id ? 'selected' : '' ?>><?= htmlspecialchars($org->nom) ?> (<?= htmlspecialchars($org->entreprise) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 15px; padding: 14px;"><i class="fa-solid fa-calendar-check"></i> Publier l'Événement</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
