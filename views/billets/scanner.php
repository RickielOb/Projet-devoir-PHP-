<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="scanner-container">
    <div style="margin-bottom: 25px;">
        <span style="color: var(--primary); font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Entrées</span>
        <h1 style="font-size: 2.2rem; font-weight: 800; margin-top: 5px;"><i class="fa-solid fa-expand"></i> Scanner les Billets</h1>
        <p style="color: var(--text-muted); margin-top: 5px;">Utilisez l'appareil photo pour scanner et valider les billets des participants en temps réel.</p>
    </div>

    <!-- Scanner Preview Area -->
    <div class="card-glass" style="padding: 20px;">
        <div id="reader" style="width: 100%;"></div>
        
        <!-- Manual entry fallback -->
        <div style="margin-top: 25px; border-top: 1px solid var(--border-glass); padding-top: 20px;">
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 15px;">Saisie manuelle si la caméra ne fonctionne pas :</p>
            <form id="manual-scan-form" class="filter-grid" style="grid-template-columns: 1fr auto; gap: 10px;">
                <input type="text" id="code_qr_manual" class="form-control" placeholder="Entrez le code billet (ex: BILLET-XXXXXXXX)" required>
                <button type="submit" class="btn btn-primary" style="padding: 12px 24px;"><i class="fa-solid fa-check"></i> Vérifier</button>
            </form>
        </div>
    </div>

    <!-- Scan Validation Results Rendered Dynamically by AJAX -->
    <div id="scan-result" style="margin-top: 30px;">
        <!-- Initial guidance helper -->
        <div class="card-glass text-center py-4" style="color: var(--text-muted); width: 100%;">
            <i class="fa-solid fa-circle-info fa-2x mb-3" style="color: var(--primary);"></i>
            <p>En attente d'un scan ou d'une saisie manuelle.</p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
