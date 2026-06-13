<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reception des colis – Postal Universite</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>

<body class="tableau-bord">

<aside class="barre-laterale">
    <div class="entete-barre">
        <img src="/assets/img/logo-iutv.png" class="logo" alt="Logo IUT">
        <h2>Postal Universite</h2>
        <p>Gestion des colis</p>
    </div>
    <nav class="menu">
        <a href="/postal-univ/dashboard">Tableau de bord</a>
        <a class="actif" href="/postal-univ/reception">Reception colis</a>
        <a href="/postal-univ/colis">Liste colis</a>
        <a href="/postal-univ/non-identifies">Non identifies</a>
        <a href="/postal-univ/historique">Historique</a>
    </nav>
    <div class="deconnexion">
        <a href="/logout">Deconnexion</a>
    </div>
</aside>

<main class="contenu">

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Reception d'un colis</h1>
            <p class="page-subtitle">Enregistrer un colis recu a l'universite avant transfert vers l'IUT</p>
        </div>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert <?= strpos($message, 'succes') !== false ? 'alert-success' : 'alert-danger' ?>">
            <span class="alert-icon-text"><?= strpos($message, 'succes') !== false ? '&#10003;' : '&#10007;' ?></span>
            <div class="alert-content"><?= htmlspecialchars($message) ?></div>
        </div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 24px;">

        <!-- COLONNE GAUCHE : Formulaire + Destinataire identifie -->
        <div style="display: flex; flex-direction: column; gap: 24px;">

            <div class="section">
                <div class="section-header">
                    <h2 class="section-title">Informations du colis</h2>
                </div>

                <form method="post" action="/postal-univ/reception" enctype="multipart/form-data" id="colisForm">

                    <div class="form-group">
                        <label class="form-label">Numero du bon de commande (BC)</label>
                        <input type="text" id="numero_bc" name="numero_commande" class="form-input" placeholder="Ex: BC-2026-001">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Numero de suivi</label>
                        <input type="text" id="numero_suivi" name="numero_suivi" class="form-input" placeholder="Ex: LP123456789FR">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Commentaire</label>
                        <textarea name="commentaire" class="form-input" rows="3" placeholder="Notes additionnelles..."></textarea>
                    </div>

                    <input type="hidden" id="photo_etiquette" name="photo_etiquette">
                    <input type="hidden" id="ocr_texte_brut" name="ocr_texte_brut">
                    <input type="hidden" id="ocr_confiance" name="ocr_confiance">
                    <input type="hidden" id="ocr_nom_destinataire" name="ocr_nom_destinataire">

                    <div class="form-info">
                        <p>Si un bon de commande est renseigné, le colis y sera automatiquement rattaché.</p>
                        <p>Sinon, le colis sera marqué <strong>Non identifié</strong> et pourra être traité ultérieurement.</p>
                    </div>

                    <div class="form-actions" style="border-top: none; padding-top: 0;">
                        <button type="submit" class="btn btn-primary">Enregistrer le colis</button>
                    </div>

                </form>
            </div>

            <!-- SECTION DESTINATAIRE IDENTIFIE -->
            <div class="section" id="section-destinataire">
                <div class="section-header">
                    <h2 class="section-title">Destinataire identifie</h2>
                </div>
                <div style="padding: 8px 0;">

                    <div class="form-group" style="margin-bottom: 12px;">
                        <label class="form-label">Nom du destinataire</label>
                        <div style="display: flex; gap: 8px;">
                            <input type="text" id="nom-manuel" class="form-input" placeholder="Ex: Valerie Touzet" style="flex: 1;">
                            <button type="button" id="btnRechercherDestinataire" class="btn btn-primary">Rechercher</button>
                        </div>
                        <p style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Rempli automatiquement par l'OCR ou saisissez manuellement</p>
                    </div>

                    <!-- MESSAGE SI OCR N'A PAS DETECTE DE NOM -->
                    <div id="ocr-nom-message" style="display:none; margin-bottom: 12px; padding: 8px 12px; background: #fff3cd; border-radius: var(--radius-sm); border: 1px solid #ffc107; font-size: 13px; color: #856404;">
                        ⚠️ L'OCR n'a pas détecté de nom sur l'étiquette. Veuillez saisir le nom manuellement.
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Departement</label>
                        <div style="padding: 10px 14px; background: var(--blue-bg); border-radius: var(--radius-sm); border: 1px solid var(--blue-border); font-weight: 500; color: var(--blue-dark);">
                            <span id="ocr-departement">—</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- COLONNE DROITE : Scanner -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">Scanner / Photographier l'Etiquette</h2>
            </div>

            <div id="cameraContainer" style="position: relative; background: var(--bg); border: 2px dashed var(--blue); border-radius: var(--radius); padding: 20px; text-align: center; margin-bottom: 16px; min-height: 280px; display: flex; align-items: center; justify-content: center;">
                <video id="video" autoplay playsinline style="width: 100%; max-width: 100%; border-radius: var(--radius-sm); display: none;"></video>
                <canvas id="canvas" style="display: none;"></canvas>
                <img id="preview" style="max-width: 100%; max-height: 350px; border-radius: var(--radius-sm); display: none;">

                <div id="placeholder" style="text-align: center;">
                    <p style="color: var(--text-secondary); margin: 20px 0; font-size: 15px;">Cliquez pour activer la camera</p>
                    <p style="color: var(--text-muted); font-size: 13px;">ou importez une photo existante</p>
                </div>
            </div>

            <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; margin-bottom: 16px;">
                <button type="button" id="btnStartCamera" class="btn btn-primary">Activer la camera</button>
                <button type="button" id="btnCapture" class="btn btn-success" style="display: none;">Prendre la photo</button>
                <button type="button" id="btnRetake" class="btn btn-danger" style="display: none;">Reprendre</button>
            </div>

            <!-- IMPORT PHOTO AMELIORE -->
            <div style="padding: 16px; background: var(--blue-bg); border-radius: var(--radius); border: 1px solid var(--blue-border);">
                <label class="form-label" style="color: var(--blue-dark); margin-bottom: 10px; display: block;">Ou importer une photo</label>

                <label for="fileUpload" style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 10px 16px; background: white; border: 2px dashed var(--blue); border-radius: var(--radius-sm); transition: all 0.2s;">
                    <span style="font-size: 18px; color: var(--blue);">&#8679;</span>
                    <span id="fileUploadText" style="color: var(--text-secondary); font-size: 14px;">Cliquez pour choisir une image...</span>
                </label>
                <input type="file" id="fileUpload" accept="image/*" capture="environment" style="display: none;">

                <div id="ocr-loader" style="display:none; margin-top:12px; padding: 8px 12px; background: white; border-radius: var(--radius-sm); color: var(--blue-dark); font-size: 14px;">
                    ⏳ Analyse OCR en cours...
                </div>
                <div id="ocr-message" style="margin-top:10px; font-size: 14px;"></div>
            </div>

        </div>

    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>
<script src="/assets/js/ocr-etiquette.js"></script>

<script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const preview = document.getElementById('preview');
    const placeholder = document.getElementById('placeholder');
    const btnStartCamera = document.getElementById('btnStartCamera');
    const btnCapture = document.getElementById('btnCapture');
    const btnRetake = document.getElementById('btnRetake');
    const photoInput = document.getElementById('photo_etiquette');
    const fileUpload = document.getElementById('fileUpload');

    let stream = null;

    // Timer pour cacher le message de succes apres 10 secondes
    setTimeout(() => {
        const alertSuccess = document.querySelector('.alert-success');
        if (alertSuccess) alertSuccess.style.display = 'none';
    }, 10000);

    function afficherResultatOCR(resultat) {
        if (resultat.numeroBC) {
            document.getElementById('numero_bc').value = resultat.numeroBC;
            document.getElementById('numero_bc').style.backgroundColor = '#d4edda';
        }
        if (resultat.numeroSuivi) {
            document.getElementById('numero_suivi').value = resultat.numeroSuivi;
            document.getElementById('numero_suivi').style.backgroundColor = '#d4edda';
        }
        document.getElementById('ocr_texte_brut').value = resultat.texteBrut;
        document.getElementById('ocr_nom_destinataire').value = resultat.nomDestinataire;
        document.getElementById('ocr_confiance').value = resultat.confiance;

        if (resultat.nomDestinataire) {
            document.getElementById('nom-manuel').value = resultat.nomDestinataire;
            document.getElementById('ocr-nom-message').style.display = 'none';

            fetch('/postal-univ/rechercher-destinataire?nom=' + encodeURIComponent(resultat.nomDestinataire))
                .then(res => res.json())
                .then(data => {
                    document.getElementById('ocr-departement').textContent = data.departement ?? 'Non identifie en BDD';
                });
        } else {
            // OCR n'a pas detecte de nom
            document.getElementById('ocr-nom-message').style.display = 'block';
            document.getElementById('ocr-departement').textContent = '—';
        }
    }

    fileUpload.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            document.getElementById('fileUploadText').textContent = file.name;
            document.getElementById('numero_bc').value = '';
            document.getElementById('numero_suivi').value = '';
            document.getElementById('numero_bc').style.backgroundColor = '';
            document.getElementById('numero_suivi').style.backgroundColor = '';
            document.getElementById('nom-manuel').value = '';
            document.getElementById('ocr-departement').textContent = '—';
            document.getElementById('ocr-message').textContent = '';
            document.getElementById('ocr-nom-message').style.display = 'none';

            const reader = new FileReader();
            reader.onload = (event) => {
                const img = new Image();
                img.onload = () => {
                    let width = img.width, height = img.height;
                    if (width > 1920) { height *= 1920 / width; width = 1920; }
                    if (height > 1080) { width *= 1080 / height; height = 1080; }
                    canvas.width = width;
                    canvas.height = height;
                    canvas.getContext('2d').drawImage(img, 0, 0, width, height);
                    const imageData = canvas.toDataURL('image/jpeg', 0.7);
                    photoInput.value = imageData;

                    lancerOCR(imageData, afficherResultatOCR);

                    preview.src = imageData;
                    preview.style.display = 'block';
                    placeholder.style.display = 'none';
                    video.style.display = 'none';
                    if (stream) stream.getTracks().forEach(track => track.stop());
                    btnStartCamera.style.display = 'none';
                    btnCapture.style.display = 'none';
                    btnRetake.style.display = 'inline-flex';
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    btnStartCamera.addEventListener('click', async () => {
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'environment', width: { ideal: 1920 }, height: { ideal: 1080 } }
            });
            video.srcObject = stream;
            video.style.display = 'block';
            placeholder.style.display = 'none';
            btnStartCamera.style.display = 'none';
            btnCapture.style.display = 'inline-flex';
        } catch (err) {
            alert('Impossible d\'acceder a la camera : ' + err.message);
        }
    });

    btnCapture.addEventListener('click', () => {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);
        const imageData = canvas.toDataURL('image/jpeg', 0.7);
        photoInput.value = imageData;

        lancerOCR(imageData, afficherResultatOCR);

        preview.src = imageData;
        preview.style.display = 'block';
        video.style.display = 'none';
        if (stream) stream.getTracks().forEach(track => track.stop());
        btnCapture.style.display = 'none';
        btnRetake.style.display = 'inline-flex';
    });

    btnRetake.addEventListener('click', () => {
        preview.style.display = 'none';
        placeholder.style.display = 'block';
        btnStartCamera.style.display = 'inline-flex';
        btnRetake.style.display = 'none';
        photoInput.value = '';
        fileUpload.value = '';
        document.getElementById('fileUploadText').textContent = 'Cliquez pour choisir une image...';
        document.getElementById('numero_bc').style.backgroundColor = '';
        document.getElementById('numero_suivi').style.backgroundColor = '';
        document.getElementById('numero_bc').value = '';
        document.getElementById('numero_suivi').value = '';
        document.getElementById('nom-manuel').value = '';
        document.getElementById('ocr-departement').textContent = '—';
        document.getElementById('ocr-message').textContent = '';
        document.getElementById('ocr-nom-message').style.display = 'none';
    });

    document.getElementById('btnRechercherDestinataire').addEventListener('click', () => {
        const nom = document.getElementById('nom-manuel').value.trim();
        if (!nom) return;

        document.getElementById('ocr-departement').textContent = 'Recherche en cours...';

        fetch('/postal-univ/rechercher-destinataire?nom=' + encodeURIComponent(nom))
            .then(res => res.json())
            .then(data => {
                document.getElementById('ocr-departement').textContent = data.departement ?? 'Non identifie en BDD';
            });
    });

    window.addEventListener('beforeunload', () => {
        if (stream) stream.getTracks().forEach(track => track.stop());
    });
</script>

</body>
</html>