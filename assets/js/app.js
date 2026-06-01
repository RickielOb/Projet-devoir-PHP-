// C:\xampp\htdocs\gestion-evenements\assets\js\app.js

document.addEventListener('DOMContentLoaded', () => {

    /* ----------------------------------------------------
       1. AUTO-DISMISS FLASH ALERTS
    ---------------------------------------------------- */
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.6s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 600);
        }, 5000);
    });

    /* ----------------------------------------------------
       2. RESPONSIVE MENU TOGGLE
    ---------------------------------------------------- */
    const navToggle = document.querySelector('.nav-toggle');
    const navLinks = document.querySelector('.nav-links');

    if (navToggle && navLinks) {
        navToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            // Toggle hamburger icon between bars and times (close)
            const icon = navToggle.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-bars');
                icon.classList.toggle('fa-xmark');
            }
        });
    }

    /* ----------------------------------------------------
       3. DYNAMIC BOOKING PRICE CALCULATION
    ---------------------------------------------------- */
    const nbPlacesInput = document.getElementById('nb_places');
    const totalPriceDisplay = document.getElementById('total-price-display');

    if (nbPlacesInput && totalPriceDisplay) {
        const calculateTotal = () => {
            const price = parseFloat(nbPlacesInput.getAttribute('data-price') || 0);
            const qty = parseInt(nbPlacesInput.value || 1);
            const total = price * qty;
            
            if (price === 0) {
                totalPriceDisplay.textContent = 'Gratuit';
            } else {
                // Formater le prix
                const formatted = new Intl.NumberFormat('fr-FR').format(total);
                totalPriceDisplay.textContent = `${formatted} FCFA`;
            }
        };

        nbPlacesInput.addEventListener('input', calculateTotal);
        nbPlacesInput.addEventListener('change', calculateTotal);
        // Calcul initial
        calculateTotal();
    }

    /* ----------------------------------------------------
       4. CLIENT-SIDE QR CODE GENERATION
    ---------------------------------------------------- */
    // Génère les QR codes pour les conteneurs avec data-qr-code
    const qrContainers = document.querySelectorAll('[data-qr-code]');
    qrContainers.forEach(container => {
        const text = container.getAttribute('data-qr-code');
        if (text && typeof QRCode !== 'undefined') {
            container.innerHTML = ''; // Nettoyer le contenu
            new QRCode(container, {
                text: text,
                width: 150,
                height: 150,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
        }
    });

    /* ----------------------------------------------------
       5. QR CODE SCANNER (CAMERA)
    ---------------------------------------------------- */
    const scannerElement = document.getElementById('reader');
    const resultElement = document.getElementById('scan-result');

    if (scannerElement && typeof Html5QrcodeScanner !== 'undefined') {
        const onScanSuccess = (decodedText, decodedResult) => {
            // Arrêter le scan après un succès pour éviter les requêtes en boucle
            html5QrcodeScanner.clear();
            
            // Envoyer la requête de validation
            validateScannedBillet(decodedText);
        };

        const onScanFailure = (error) => {
            // Échec du scan ou attente d'une frame (très fréquent au chargement, on ignore)
        };

        const html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", 
            { fps: 10, qrbox: { width: 250, height: 250 } },
            /* verbose= */ false
        );
        
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    }

    // Gestion du formulaire de scan manuel
    const manualScanForm = document.getElementById('manual-scan-form');
    if (manualScanForm) {
        manualScanForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const codeInput = document.getElementById('code_qr_manual');
            const code = codeInput.value.trim();
            if (code) {
                validateScannedBillet(code);
            }
        });
    }

    // Fonction d'envoi AJAX pour valider le billet
    function validateScannedBillet(code) {
        if (!resultElement) return;

        resultElement.innerHTML = `
            <div class="card-glass text-center py-4" style="width: 100%;">
                <i class="fa-solid fa-spinner fa-spin fa-2xl mb-3" style="color: var(--primary);"></i>
                <p>Validation du billet <strong>${code}</strong> en cours...</p>
            </div>
        `;

        const formData = new FormData();
        formData.append('code_qr', code);

        fetch('index.php?page=billets&action=valider', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resultElement.innerHTML = `
                    <div class="scan-result-card success">
                        <i class="fa-solid fa-circle-check"></i>
                        <h3>${data.message}</h3>
                        <p><strong>Événement :</strong> ${data.billet.evenement_titre}</p>
                        <p><strong>Participant :</strong> ${data.billet.participant_prenom} ${data.billet.participant_nom}</p>
                        <p><strong>Billet ID :</strong> ${data.billet.code_qr}</p>
                        <button onclick="window.location.reload()" class="btn btn-outline mt-3 no-print">Scanner un autre billet</button>
                    </div>
                `;
            } else {
                let details = '';
                if (data.billet) {
                    details = `
                        <p class="mt-2"><strong>Événement :</strong> ${data.billet.evenement_titre}</p>
                        <p><strong>Participant :</strong> ${data.billet.participant_prenom} ${data.billet.participant_nom}</p>
                        <p><strong>Statut du billet :</strong> <span class="badge-seats full">${data.billet.statut}</span></p>
                    `;
                }
                resultElement.innerHTML = `
                    <div class="scan-result-card error">
                        <i class="fa-solid fa-circle-xmark"></i>
                        <h3>Erreur de validation</h3>
                        <p>${data.message}</p>
                        ${details}
                        <button onclick="window.location.reload()" class="btn btn-outline mt-3 no-print">Réessayer</button>
                    </div>
                `;
            }
        })
        .catch(err => {
            resultElement.innerHTML = `
                <div class="scan-result-card error">
                    <i class="fa-solid fa-circle-xmark"></i>
                    <h3>Erreur Réseau</h3>
                    <p>Impossible de se connecter au serveur de validation.</p>
                    <button onclick="window.location.reload()" class="btn btn-outline mt-3 no-print">Réessayer</button>
                </div>
            `;
            console.error(err);
        });
    }

});
