<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="row justify-content-center my-5">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm border-light">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <i class="fa-solid fa-key fa-3x text-primary mb-3"></i>
                    <h2 class="h4 fw-bold">Définir un nouveau mot de passe</h2>
                    <p class="text-muted small">Veuillez saisir votre nouveau mot de passe répondant aux exigences de sécurité.</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger mb-4">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i>
                        <div><?= htmlspecialchars($error) ?></div>
                    </div>
                <?php endif; ?>

                <form action="index.php?page=auth&action=reset_password&email=<?= urlencode($email) ?>&token=<?= htmlspecialchars($token) ?>" method="POST">
                    <div class="mb-3">
                        <label for="mot_de_passe" class="form-label fw-semibold">Nouveau mot de passe *</label>
                        <input type="password" name="mot_de_passe" id="mot_de_passe" class="form-control" placeholder="Min. 4 car. + 1 majuscule" required>
                    </div>

                    <div class="mb-4">
                        <label for="confirmer_mot_de_passe" class="form-label fw-semibold">Confirmer le mot de passe *</label>
                        <input type="password" name="confirmer_mot_de_passe" id="confirmer_mot_de_passe" class="form-control" placeholder="Confirmer" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2">Mettre à jour le mot de passe</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
