<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div style="max-width: 450px; margin: 60px auto;">
    <div class="card-glass">
        <div class="text-center" style="text-align: center; margin-bottom: 30px;">
            <i class="fa-solid fa-user-lock fa-3x" style="color: var(--primary); margin-bottom: 15px;"></i>
            <h2 style="font-size: 1.8rem; font-weight: 800;">Connexion</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 5px;">Accédez à votre espace EventGabon</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" style="margin-bottom: 20px;">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <div><?= htmlspecialchars($error) ?></div>
            </div>
        <?php endif; ?>

        <form action="index.php?page=auth&action=login" method="POST">
            <div class="form-group">
                <label for="email" class="form-label">Adresse Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="exemple@domaine.com" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
            </div>

            <div class="form-group">
                <label for="mot_de_passe" class="form-label">Mot de passe</label>
                <input type="password" name="mot_de_passe" id="mot_de_passe" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Se connecter</button>
        </form>

        <div style="text-align: center; margin-top: 25px; font-size: 0.9rem; color: var(--text-muted);">
            Pas encore de compte ? <a href="index.php?page=auth&action=register" style="font-weight: 600; color: var(--primary);">Créer un compte</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
