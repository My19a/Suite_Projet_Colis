<?php
$titre = "Réception d'un colis – Responsable colis";
$actif = '/postal/reception';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
    <div class="page-header-info">
        <h1 class="page-title">Réception d'un colis</h1>
        <p class="page-subtitle">Déclarer l'arrivée à l'université d'un colis lié à un bon de commande existant</p>
    </div>
</div>

<?php if (!empty($message)): $ok = (($messageType ?? '') === 'ok'); ?>
    <div class="message <?= $ok ? 'message-ok' : 'message-err' ?>">
        <span class="message-icone"><?= $ok ? '&#10003;' : '&#10007;' ?></span>
        <div class="message-corps"><?= htmlspecialchars($message) ?></div>
    </div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 24px;">

    <div style="display: flex; flex-direction: column; gap: 24px;">

        <div class="bloc">
            <div class="formulaire">
                <form method="post" action="/postal/reception" enctype="multipart/form-data" id="colisForm">

                    <div class="champ">
                        <label class="etiquette requis">Numéro de suivi</label>
                        <input type="text" id="numero_suivi" name="numero_suivi" class="saisie" placeholder="Ex: LP123456789FR" required>
                    </div>

                    <div class="champ">
                        <label class="etiquette">Numéro du bon de commande (BC) — optionnel</label>
                        <input type="text" id="numero_bc" name="numero_commande" class="saisie" placeholder="Ex: BC-2026-001" value="<?= htmlspecialchars($_GET['bc'] ?? '') ?>">
                    </div>

                    <div class="champ">
                        <label class="etiquette">Commentaire</label>
                        <textarea name="commentaire" class="saisie" rows="3" placeholder="Notes additionnelles..."></textarea>
                    </div>

                    <input type="hidden" id="photo_etiquette" name="photo_etiquette">
                    <input type="hidden" id="ocr_texte_brut" name="ocr_texte_brut">
                    <input type="hidden" id="ocr_confiance" name="ocr_confiance">
                    <input type="hidden" id="ocr_nom_destinataire" name="ocr_nom_destinataire">
                    <input type="hidden" id="demandeur_nom" name="demandeur_nom">

                    <div class="formulaire-info">
                        <p>La validation n'est possible que si le <strong>numéro de suivi</strong> et le <strong>nom du demandeur</strong> correspondent à une commande <strong>en attente</strong> déjà déclarée par l'éditeur de bons de commande.</p>
                        <p>Une fois reconnue, tous les colis de cette commande passent à « Livré à l'université » et apparaissent dans « Colis à transférer ».</p>
                    </div>

                    <button type="submit" class="bouton bouton-principal">Valider la réception</button>

                </form>
            </div>
        </div>

        <div class="bloc" id="section-destinataire">
            <div class="formulaire">
                <h2 class="bloc-titre" style="margin-bottom: 12px;">Demandeur</h2>

                <div class="champ" style="margin-bottom: 12px;">
                    <label class="etiquette requis">Nom du demandeur</label>
                    <div style="display: flex; gap: 8px;">
                        <input type="text" id="nom-manuel" class="saisie" placeholder="Ex: Valerie Touzet" style="flex: 1;" oninput="document.getElementById('demandeur_nom').value=this.value;document.getElementById('ocr_nom_destinataire').value=this.value;">
                        <button type="button" id="btnRechercherDestinataire" class="bouton bouton-principal">Rechercher</button>
                    </div>
                    <p class="aide-champ">Rempli automatiquement par l'OCR ou saisissez manuellement. Doit correspondre au demandeur de la commande.</p>
                </div>

                <div id="ocr-nom-message" class="message message-attn" style="display:none;">
                    <span class="message-icone">&#9888;</span>
                    <div class="message-corps">L'OCR n'a pas détecté de nom sur l'étiquette. Veuillez saisir le nom manuellement.</div>
                </div>

                <div class="champ" style="margin-bottom: 0;">
                    <label class="etiquette">Résultat</label>
                    <div style="padding: 7px 10px; background: var(--princ-doux); border-radius: var(--r); border: 1px solid var(--bord); font-weight: 500; color: var(--princ);">
                        <span id="resultatDestinataire">—</span>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <div class="bloc">
        <div class="formulaire">
            <h2 class="bloc-titre" style="margin-bottom: 12px;">Scanner / Photographier l'étiquette</h2>

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
                <label class="etiquette" style="margin-bottom: 10px; display: block;">Ou importer une photo</label>

                <label for="fileUpload" style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 10px 16px; background: var(--surface); border: 2px dashed var(--princ); border-radius: var(--r);">
                    <span style="font-size: 18px; color: var(--princ);">&#8679;</span>
                    <span id="fileUploadText" style="color: var(--texte-doux); font-size: 14px;">Cliquez pour choisir une image...</span>
                </label>
                <input type="file" id="fileUpload" accept="image/*" capture="environment" style="display: none;">

                <div id="ocr-loader" style="display:none; margin-top:12px; padding: 8px 12px; background: var(--surface); border-radius: var(--r); color: var(--princ); font-size: 14px;">
                    ⏳ Analyse OCR en cours...
                </div>
                <div id="ocr-message" style="margin-top:10px; font-size: 14px;"></div>
            </div>

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
    const resultatDestinataire = document.getElementById('resultatDestinataire');

    let stream = null;

    setTimeout(() => {
        const alertSuccess = document.querySelector('.message-ok');
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
            document.getElementById('demandeur_nom').value = resultat.nomDestinataire;
            document.getElementById('ocr-nom-message').style.display = 'none';

            fetch('/postal/rechercher-destinataire?nom=' + encodeURIComponent(resultat.nomDestinataire))
                .then(res => res.json())
                .then(data => {
                    afficherDestinataire(data);
                });
        } else {
            document.getElementById('ocr-nom-message').style.display = 'flex';
            resultatDestinataire.textContent = '—';
        }
    }

    function afficherDestinataire(data) {
        if (data.fullName) {
            document.getElementById('nom-manuel').value = data.fullName;
            document.getElementById('demandeur_nom').value = data.fullName;
            document.getElementById('ocr_nom_destinataire').value = data.fullName;
            resultatDestinataire.textContent = data.fullName + (data.departement ? ' — ' + data.departement : '');
        } else {
            resultatDestinataire.textContent = 'Aucun utilisateur trouvé';
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
            document.getElementById('demandeur_nom').value = '';
            resultatDestinataire.textContent = '—';
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
        document.getElementById('fileUploadText').textContent = 'Cliquez pour choisir une image...';
        document.getElementById('numero_bc').style.backgroundColor = '';
        document.getElementById('numero_suivi').style.backgroundColor = '';
        document.getElementById('numero_bc').value = '';
        document.getElementById('numero_suivi').value = '';
        document.getElementById('nom-manuel').value = '';
        document.getElementById('demandeur_nom').value = '';
        resultatDestinataire.textContent = '—';
        document.getElementById('ocr-message').textContent = '';
        document.getElementById('ocr-nom-message').style.display = 'none';
    });

    document.getElementById('btnRechercherDestinataire').addEventListener('click', () => {
        const nom = document.getElementById('nom-manuel').value.trim();
        if (!nom) return;

        document.getElementById('demandeur_nom').value = nom;
        document.getElementById('ocr_nom_destinataire').value = nom;
        resultatDestinataire.textContent = 'Recherche en cours...';

        fetch('/postal/rechercher-destinataire?nom=' + encodeURIComponent(nom))
            .then(res => res.json())
            .then(data => {
                afficherDestinataire(data);
            });
    });

    window.addEventListener('beforeunload', () => {
        if (stream) stream.getTracks().forEach(track => track.stop());
    });
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
