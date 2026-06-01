<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div style="max-width: 500px; margin: 40px auto;">
    <div class="card-glass">
        <div class="text-center" style="text-align: center; margin-bottom: 25px;">
            <i class="fa-solid fa-user-plus fa-3x" style="color: var(--primary); margin-bottom: 12px;"></i>
            <h2 style="font-size: 1.8rem; font-weight: 800;">Inscription</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 5px;">Rejoignez la communauté EventGabon</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" style="margin-bottom: 20px;">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <div><?= htmlspecialchars($error) ?></div>
            </div>
        <?php endif; ?>

        <form action="index.php?page=auth&action=register" method="POST">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label for="nom" class="form-label">Nom *</label>
                    <input type="text" name="nom" id="nom" class="form-control" placeholder="Mba" required value="<?= isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '' ?>">
                </div>

                <div class="form-group">
                    <label for="prenom" class="form-label">Prénom *</label>
                    <input type="text" name="prenom" id="prenom" class="form-control" placeholder="Jean" required value="<?= isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : '' ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Adresse Email *</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="jean.mba@gmail.com" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
            </div>

            <div class="form-group">
                <label for="telephone" class="form-label">Téléphone</label>
                <input type="tel" name="telephone" id="telephone" class="form-control" placeholder="+241 07 12 34 56" value="<?= isset($_POST['telephone']) ? htmlspecialchars($_POST['telephone']) : '' ?>">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label for="mot_de_passe" class="form-label">Mot de passe *</label>
                    <input type="password" name="mot_de_passe" id="mot_de_passe" class="form-control" placeholder="Min. 6 car." required>
                </div>

                <div class="form-group">
                    <label for="confirmer_mot_de_passe" class="form-label">Confirmation *</label>
                    <input type="password" name="confirmer_mot_de_passe" id="confirmer_mot_de_passe" class="form-control" placeholder="Confirmer" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Je souhaite créer un compte : *</label>
                <div class="role-toggle-group">
                    <input type="radio" name="role" id="role_participant" value="participant" class="role-radio" checked>
                    <label for="role_participant" class="role-label">Participant (Réserver)</label>
                    
                    <input type="radio" name="role" id="role_organisateur" value="organisateur" class="role-radio" <?= isset($_POST['role']) && $_POST['role'] === 'organisateur' ? 'checked' : '' ?>>
                    <label for="role_organisateur" class="role-label">Organisateur (Créer)</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">S'inscrire</button>
        </form>

        <div style="text-align: center; margin-top: 25px; font-size: 0.9rem; color: var(--text-muted);">
            Vous avez déjà un compte ? <a href="index.php?page=auth&action=login" style="font-weight: 600; color: var(--primary);">Se connecter</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
