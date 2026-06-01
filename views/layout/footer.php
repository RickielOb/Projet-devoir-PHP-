    </main>

    <!-- Footer Section -->
    <footer class="no-print" style="border-top: 1px solid var(--border-glass); margin-top: 80px; padding: 40px 0; background: rgba(0,0,0,0.2);">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div>
                <h4 style="color: var(--text-primary); margin-bottom: 8px;">EventGabon</h4>
                <p style="color: var(--text-muted); font-size: 0.85rem;">La billetterie événementielle de référence au Gabon.</p>
            </div>
            <div style="display: flex; gap: 20px; font-size: 0.9rem;">
                <a href="index.php" style="color: var(--text-muted);">Accueil</a>
                <a href="index.php?page=evenements" style="color: var(--text-muted);">Événements</a>
                <a href="index.php?page=billets&action=scanner" style="color: var(--text-muted);">Scanner</a>
            </div>
            <div style="color: var(--text-muted); font-size: 0.85rem;">
                &copy; <?= date('Y') ?> EventGabon. Tous droits réservés.
            </div>
        </div>
    </footer>

    <!-- QR Code Generator Library (qrcodejs) -->
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

    <!-- QR Code Scanner Library (html5-qrcode) -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <!-- Custom app.js -->
    <script src="assets/js/app.js"></script>
</body>
</html>
