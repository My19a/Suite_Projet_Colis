<?php
$titre = 'Ajouter un colis – Service Postal IUT';
$actif = '/postal/colis/ajouter';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
    <div class="page-header-info">
        <h1 class="page-title">Ajouter un colis</h1>
        <p class="page-subtitle">Enregistrer l'arrivée d'un nouveau colis avec scan/photo de l'étiquette</p>
    </div>
</div>

<?php if (!empty($message)): ?>
    <div class="message <?= strpos($message, 'succes') !== false ? 'message-ok' : 'message-err' ?>">
        <span class="message-icone"><?= strpos($message, 'succes') !== false ? icone('valide', 16) : icone('croix', 16) ?></span>
        <div class="message-corps"><?= htmlspecialchars($message) ?></div>
    </div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 24px;">

    <div class="bloc">
        <div class="bloc-entete">
            <h2 class="bloc-titre">Informations du colis</h2>
        </div>

        <form method="POST" enctype="multipart/form-data" id="colisForm">
            <div class="champ">
                <label class="etiquette requis">Numéro du bon de commande (BC)</label>
                <input type="text" id="numero_bc" name="numero_bc" class="saisie" placeholder="Ex: BC2024-001" required>
            </div>

            <div class="champ">
                <label class="etiquette">Numéro de suivi</label>
                <input type="text" id="numero_suivi" name="numero_suivi" class="saisie" placeholder="Ex: FR123456789">
            </div>

            <div class="champ">
                <label class="etiquette">Destinataire identifié</label>
                <div style="display:flex; gap:8px;">
                    <input type="text" id="nom_destinataire" class="saisie" placeholder="Ex: Valerie Touzet" style="flex:1;" name="nom_destinataire">
                    <button type="button" id="btnRechercheDest" class="bouton bouton-secondaire">Rechercher</button>
                </div>
                <p class="aide-champ">Rempli automatiquement par l'OCR ou saisissez manuellement</p>
            </div>

            <div class="champ" style="margin-bottom:0;">
                <label class="etiquette">Résultat</label>
                <div id="resultatDestinataire" style="padding:7px 10px; background:var(--princ-doux); border-radius:var(--r); border:1px solid var(--bord); font-weight:500; color:var(--princ);">
                    —
                </div>
            </div>

            <div class="champ">
                <label class="etiquette">Commentaire</label>
                <textarea name="commentaire" class="saisie" rows="3" placeholder="Notes additionnelles..."></textarea>
            </div>

            <input type="hidden" id="photo_etiquette" name="photo_etiquette">
            <input type="hidden" id="ocr_texte_brut" name="ocr_texte_brut">
            <input type="hidden" id="ocr_confiance" name="ocr_confiance">

            <div class="formulaire-boutons" style="border-top: none; padding-top: 0;">
                <button type="submit" class="bouton bouton-principal">Ajouter le colis</button>
            </div>
        </form>
    </div>

    <div class="bloc">
        <div class="bloc-entete">
            <h2 class="bloc-titre">Scanner / Photographier l'Étiquette</h2>
        </div>

        <div id="cameraContainer" style="position: relative; background: var(--fond); border: 2px dashed var(--princ); border-radius: var(--r); padding: 20px; text-align: center; margin-bottom: 16px; min-height: 280px; display: flex; align-items: center; justify-content: center;">
            <video id="video" autoplay playsinline style="width: 100%; max-width: 100%; border-radius: var(--r); display: none;"></video>
            <canvas id="canvas" style="display: none;"></canvas>
            <img id="preview" style="max-width: 100%; max-height: 350px; border-radius: var(--r); display: none;">

            <div id="placeholder" style="text-align: center;">
                <p style="color: var(--texte-doux); margin: 20px 0; font-size: 15px;">Cliquez pour activer la caméra</p>
                <p style="color: var(--texte-leger); font-size: 13px;">ou importez une photo existante</p>
            </div>
        </div>

        <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; margin-bottom: 16px;">
            <button type="button" id="btnStartCamera" class="bouton bouton-principal">Activer la caméra</button>
            <button type="button" id="btnCapture" class="bouton bouton-valider" style="display: none;">Prendre la photo</button>
            <button type="button" id="btnRetake" class="bouton bouton-danger" style="display: none;">Reprendre</button>
        </div>

        <div style="padding: 16px; background: var(--princ-doux); border-radius: var(--r); border: 1px solid var(--bord);">
            <label class="etiquette">Ou importer une photo</label>
            <input type="file" id="fileUpload" accept="image/*" capture="environment" class="saisie" style="background: var(--surface);">
            <div id="ocr-loader" style="display:none; margin-top:10px;">Analyse OCR en cours...</div>
            <div id="ocr-message" style="margin-top:10px;"></div>
            <div id="ocr-resultat" style="display:none; margin-top:12px; padding:12px; background: var(--princ-doux); border-radius: var(--r); border: 1px solid var(--bord);">
                <p style="margin:0 0 6px 0;"><strong>Destinataire détecté :</strong> <span id="ocr-nom"></span></p>
                <p style="margin:0;"><strong>Département :</strong> <span id="ocr-departement">Recherche en cours...</span></p>
            </div>
        </div>

        <div class="message message-attn">
            <span class="message-icone"><?= icone('alerte', 16) ?></span>
            <div class="message-corps">La photo de l'étiquette aide à identifier automatiquement le bon de commande associé.</div>
        </div>
    </div>

</div>

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

    function afficherResultatOCR(resultat) {
        if (resultat.numeroBC) {
            document.getElementById('numero_bc').value = resultat.numeroBC;
            document.getElementById('numero_bc').style.backgroundColor = '#d4edda';
        }
        if (resultat.numeroSuivi) {
            document.getElementById('numero_suivi').value = resultat.numeroSuivi;
            document.getElementById('numero_suivi').style.backgroundColor = '#d4edda';
        }

        const champNom = document.getElementById('nom_destinataire');
        champNom.value = resultat.nomDestinataire || '';
        champNom.style.backgroundColor = resultat.nomDestinataire ? '#d4edda' : '';

        document.getElementById('ocr_texte_brut').value = resultat.texteBrut;
        document.getElementById('ocr_confiance').value = resultat.confiance;
    }

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
            alert('Impossible d\'accéder à la caméra : ' + err.message);
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

        // permet reinitialisation des données pour chaque nouvelle étiquette
        document.getElementById('numero_suivi').value = '';
        document.getElementById('nom_destinataire').value = '';
        document.getElementById('ocr_texte_brut').value = '';
        document.getElementById('ocr_confiance').value = '';
        document.getElementById('numero_suivi').style.backgroundColor = '';
        document.getElementById('nom_destinataire').style.backgroundColor = '';
        document.getElementById('resultatDestinataire').textContent = '—';
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

    window.addEventListener('beforeunload', () => {
        if (stream) stream.getTracks().forEach(track => track.stop());
    });
</script>

<script>
    document.getElementById('btnRechercheDest').addEventListener('click', async () => {
        const nom = document.getElementById('nom_destinataire').value;
        const response = await fetch('/postal/rechercher-destinataire?nom=' + encodeURIComponent(nom));
        const data = await response.json();
        const zone = document.getElementById('resultatDestinataire');

        if (data.length > 0) {
            zone.textContent = "✔ " + data[0].fullName;
        } else {
            zone.textContent = "Aucun destinataire trouvé";
        }
    });
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>