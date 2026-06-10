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

        <div class="section">
            <div class="section-header">
                <h2 class="section-title">Informations du colis</h2>
            </div>

            <form method="post" action="/postal-univ/reception" enctype="multipart/form-data" id="colisForm">

                <div class="form-group">
                    <label class="form-label required">Numero du bon de commande (BC)</label>
                    <input type="text" id="numero_bc" name="numero_commande" class="form-input" placeholder="Ex: BC-2026-001" >
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
                    <p>Le campus / IUT sera identifie automatiquement via le bon de commande.</p>
                    <p>Si l'identification echoue, le colis sera marque <strong>Non identifie</strong>.</p>
                </div>

                <div class="form-actions" style="border-top: none; padding-top: 0;">
                    <button type="submit" class="btn btn-primary">Enregistrer le colis</button>
                </div>

            </form>
        </div>

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

            <div style="padding: 16px; background: var(--blue-bg); border-radius: var(--radius); border: 1px solid var(--blue-border);">
                <label class="form-label" style="color: var(--blue-dark);">Ou importer une photo</label>
                <input type="file" id="fileUpload" accept="image/*" capture="environment" class="form-input" style="background: white;">
                <div id="ocr-loader" style="display:none; margin-top:10px;">
                    Analyse OCR en cours...
                </div>
                <div id="ocr-message" style="margin-top:10px;"></div>
            </div>

            <div class="alert alert-warning" style="margin-top: 16px; margin-bottom: 0;">
                <span class="alert-icon-text">&#9888;</span>
                <div class="alert-content" style="color: var(--warning-text);">La photo de l'etiquette aide a identifier automatiquement le bon de commande associe.</div>
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
        lancerOCR(imageData, function(resultat) {
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
        });
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
        document.getElementById('numero_bc').style.backgroundColor = '';
        document.getElementById('numero_suivi').style.backgroundColor = '';
    });

    fileUpload.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
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
                    lancerOCR(imageData, function(resultat) {
                        if (resultat.numeroBC) {
                            document.getElementById('numero_bc').value = resultat.numeroBC;
                            document.getElementById('numero_bc').style.backgroundColor = '#d4edda';
                        }
                        if (resultat.numeroSuivi) {
                            document.getElementById('numero_suivi').value = resultat.numeroSuivi;
                            document.getElementById('numero_suivi').style.backgroundColor = '#d4edda';
                        }
                        document.getElementById('ocr_texte_brut').value = resultat.texteBrut;
                        document.getElementById('ocr_confiance').value = resultat.confiance;
                    });
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

    window.addEventListener('beforeunload', () => {
        if (stream) stream.getTracks().forEach(track => track.stop());
    });
</script>

</body>
</html>