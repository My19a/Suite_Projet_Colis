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
                    <label class="form-label required">Numéro du bon de commande (BC)</label>
                    <input type="text" name="numero_bc" class="form-input" placeholder="placeholder="placeholder=""Ex: BC2024-001"" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Numéro de suivi</label>
                    <input type="text" name="numero_suivi" class="form-input" placeholder="placeholder="placeholder=""Ex: FR123456789"">
                </div>

                <div class="form-group">
                    <label class="form-label">Commentaire</label>
                    <textarea name="commentaire" class="form-input" rows="3" placeholder="placeholder="placeholder=""Notes additionnelles...""></textarea>
                </div>

                <input type="hidden" id="photo_etiquette" name="photo_etiquette">

                <div class="form-actions" style="border-top: none; padding-top: 0;">
                    <button type="submit" class="btn btn-primary">Ajouter le colis</button>
                </div>
            </form>
        </div>

        <div class="section">
            <div class="section-header">
                <h2 class="section-title">Scanner / Photographier l'Étiquette</h2>
            </div>

            <div id="cameraContainer" style="position: relative; background: var(--bg); border: 2px dashed var(--blue); border-radius: var(--radius); padding: 20px; text-align: center; margin-bottom: 16px; min-height: 280px; display: flex; align-items: center; justify-content: center;">
                <video id="video" autoplay playsinline style="width: 100%; max-width: 100%; border-radius: var(--radius-sm); display: none;"></video>
                <canvas id="canvas" style="display: none;"></canvas>
                <img id="preview" style="max-width: 100%; max-height: 350px; border-radius: var(--radius-sm); display: none;">

                <div id="placeholder" style="text-align: center;">
                    <p style="color: var(--text-secondary); margin: 20px 0; font-size: 15px;">Cliquez pour activer la caméra</p>
                    <p style="color: var(--text-muted); font-size: 13px;">ou importez une photo existante</p>
                </div>
            </div>

            <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; margin-bottom: 16px;">
                <button type="button" id="btnStartCamera" class="btn btn-primary">Activer la caméra</button>
                <button type="button" id="btnCapture" class="btn btn-success" style="display: none;">Prendre la photo</button>
                <button type="button" id="btnRetake" class="btn btn-danger" style="display: none;">Reprendre</button>
            </div>

            <div style="padding: 16px; background: var(--blue-bg); border-radius: var(--radius); border: 1px solid var(--blue-border);">
                <label class="form-label" style="color: var(--blue-dark);">Ou importer une photo</label>
                <input type="file" id="fileUpload" accept="image/*" capture="environment" class="form-input" style="background: white;">
            </div>

            <div class="alert alert-warning">
                <span class="alert-icon-text">&#9888;</span>
                <div class="alert-content">La photo de l'étiquette aide a identifier automatiquement le bon de commande associe.</div>
            </div>
        </div>

    </div>

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
            alert('Impossible d\'accéder a la caméra : ' + err.message);
        }
    });

    btnCapture.addEventListener('click', () => {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);
        const imageData = canvas.toDataURL('image/jpeg', 0.7);
        photoInput.value = imageData;
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

<?php require __DIR__ . '/../partials/footer.php'; ?>
