<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un colis – Service Postal IUT</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>

<body class="tableau-bord">

<aside class="barre-laterale">
    <div class="entete-barre">
        <img src="/assets/img/logo-iutv.png" class="logo" alt="Logo IUT">
        <h2>Postal IUT</h2>
        <p>Service Postal</p>
    </div>

    <nav class="menu">
        <a href="/postal/dashboard">Tableau de bord</a>
        <a href="/postal/colis/recus">Colis recus</a>
        <a href="/postal/colis/remis">Colis remis</a>
        <a href="/postal/colis/recherche">Recherche colis</a>
        <a href="/postal/colis/non-identifies">Colis non identifies</a>
        <a class="actif" href="/postal/colis/ajouter">Ajouter un colis</a>
    </nav>

    <div class="deconnexion">
        <a href="/logout">Deconnexion</a>
    </div>
</aside>

<main class="contenu">

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Ajouter un colis</h1>
            <p class="page-subtitle">Enregistrer l'arrivee d'un nouveau colis avec scan/photo de l'etiquette</p>
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

            <form method="POST" enctype="multipart/form-data" id="colisForm">
                <div class="form-group">
                    <label class="form-label required">Numero du bon de commande (BC)</label>
                    <input type="text" id="numero_bc" name="numero_bc" class="form-input" placeholder="Ex: BC2024-001" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Numero de suivi</label>
                    <input type="text" id="numero_suivi" name="numero_suivi" class="form-input" placeholder="Ex: FR123456789">
                </div>

                <div class="form-group">
                <label>Destinataire identifié</label>

                <input type="text" id="nom_destinataire" name="nom_destinataire" class="form-control" placeholder="Nom détecté par OCR ou saisie manuelle">

                <button type="button" id="btnRechercheDest" class="btn btn-secondary" style="margin-top:10px;">
                    Rechercher
                </button>

                <div id="resultatDestinataire" style="margin-top:10px;"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Commentaire</label>
                    <textarea name="commentaire" class="form-input" rows="3" placeholder="Notes additionnelles..."></textarea>
                </div>

                <input type="hidden" id="photo_etiquette" name="photo_etiquette">
                <input type="hidden" id="ocr_texte_brut" name="ocr_texte_brut">
                <input type="hidden" id="ocr_confiance" name="ocr_confiance">

                <div class="form-actions" style="border-top: none; padding-top: 0;">
                    <button type="submit" class="btn btn-primary">Ajouter le colis</button>
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
                <div id="ocr-resultat" style="display:none; margin-top:12px; padding:12px; background: var(--blue-bg); border-radius: var(--radius); border: 1px solid var(--blue-border);">
                    <p style="margin:0 0 6px 0;"><strong>Destinataire détecté :</strong> <span id="ocr-nom"></span></p>
                    <p style="margin:0;"><strong>Département :</strong> <span id="ocr-departement">Recherche en cours...</span></p>
                </div>

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
        lancerOCR(imageData, function(resultat)
        {
            if(resultat.numeroBC)
            {
                document.getElementById('numero_bc').value =
                    resultat.numeroBC;

                document.getElementById('numero_bc')
                    .style.backgroundColor = '#d4edda';
            }

            if(resultat.numeroSuivi)
            {
                document.getElementById('numero_suivi').value =
                    resultat.numeroSuivi;

                document.getElementById('numero_suivi')
                    .style.backgroundColor = '#d4edda';
            }

            const champNom =
            document.getElementById('nom_destinataire');

            champNom.value =
                resultat.nomDestinataire || '';

            if(resultat.nomDestinataire)
            {
                champNom.style.backgroundColor = '#d4edda';
            }
            else
            {
                champNom.style.backgroundColor = '';
            }

            document.getElementById('ocr_texte_brut').value =
                resultat.texteBrut;

            document.getElementById('ocr_confiance').value =
                resultat.confiance;
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

        // permet reinitialisation des données pour chaque nouvelle étiquette

        document.getElementById('numero_suivi').value = '';
        document.getElementById('nom_destinataire').value = '';
        document.getElementById('ocr_texte_brut').value = '';
        document.getElementById('ocr_confiance').value = '';

        document.getElementById('numero_suivi').style.backgroundColor = '';
        document.getElementById('nom_destinataire').style.backgroundColor = '';
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
                    lancerOCR(imageData, function(resultat)
                    {
                        if(resultat.numeroBC)
                        {
                            document.getElementById('numero_bc').value =
                                resultat.numeroBC;

                            document.getElementById('numero_bc')
                                .style.backgroundColor = '#d4edda';
                        }

                        if(resultat.numeroSuivi)
                        {
                            document.getElementById('numero_suivi').value =
                                resultat.numeroSuivi;

                            document.getElementById('numero_suivi')
                                .style.backgroundColor = '#d4edda';
                        }

                        const champNom =
                        document.getElementById('nom_destinataire');

                        champNom.value =
                            resultat.nomDestinataire || '';

                        if(resultat.nomDestinataire)
                        {
                            champNom.style.backgroundColor = '#d4edda';
                        }
                        else
                        {
                            champNom.style.backgroundColor = '';
                        }

                        document.getElementById('ocr_texte_brut').value =
                            resultat.texteBrut;

                        document.getElementById('ocr_confiance').value =
                            resultat.confiance;
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
<script>

    document
    .getElementById('btnRechercheDest')
    .addEventListener('click', async () => {

        const nom =
            document.getElementById(
                'nom_destinataire'
            ).value;

        const response = await fetch(
            '/postal/rechercher-destinataire?nom='
            + encodeURIComponent(nom)
        );

        const data = await response.json();

        const zone =
            document.getElementById(
                'resultatDestinataire'
            );

        if (data.length > 0) {

            zone.innerHTML =
                "✔ Destinataire trouvé : "
                + data[0].fullName;

        } else {

            zone.innerHTML =
                "❌ Aucun destinataire trouvé";
        }
    });
</script>

</body>
</html>
