<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="row justify-content-center my-5">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm border-light">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <i class="fa-solid fa-envelope-open-text fa-3x text-primary mb-3"></i>
                    <h2 class="h4 fw-bold">Mot de passe oublié</h2>
                    <p class="text-muted small">Saisissez l'adresse email de votre compte. Nous vous enverrons un lien de réinitialisation.</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger mb-4">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i>
                        <div><?= htmlspecialchars($error) ?></div>
                    </div>
                <?php endif; ?>

                <form action="index.php?page=auth&action=forgot_password" method="POST">
                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold">Adresse Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="exemple@domaine.com" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2">Envoyer le lien de réinitialisation</button>
                </form>

                <div class="text-center mt-4 pt-2 border-top">
                    <a href="index.php?page=auth&action=login" class="text-muted small"><i class="fa-solid fa-arrow-left me-1"></i> Retour à la connexion</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
