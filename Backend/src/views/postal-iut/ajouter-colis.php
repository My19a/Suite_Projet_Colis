<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un colis – Service Postal IUT</title>
    <link rel="stylesheet" href="/COLIS_SAE/assets/css/style-dashboard.css">
    <link rel="stylesheet" href="/COLIS_SAE/assets/css/style-ajouter-colis.css?v=1">
</head>

<body class="tableau-bord">

    <!-- BARRE LATERALE -->
    <aside class="barre-laterale">
        <div class="entete-barre">
            <img src="/COLIS_SAE/assets/img/logo-iutv.png" class="logo">
            <h2>IUT Colis</h2>
            <p>Service Postal</p>
        </div>

        <nav class="menu">
            <a href="/COLIS_SAE/public/postal_iut/postal-iut.php">📦 Tableau de bord</a>
            <a href="/COLIS_SAE/public/postal_iut/colis-recus.php">📥 Colis reçus</a>
            <a href="/COLIS_SAE/public/postal_iut/colis-remis.php">📤 Colis remis</a>
            <a href="/COLIS_SAE/public/postal_iut/recherche-colis.php">🔍 Recherche colis</a>
            <a href="/COLIS_SAE/public/postal_iut/non-identifies.php">❓ Colis non identifiés</a>
            <a href="/COLIS_SAE/public/postal_iut/historique.php">📜 Historique global</a>
        </nav>

        <div class="deconnexion">
            <a href="/COLIS_SAE/logout.php">🚪 Déconnexion</a>
        </div>
    </aside>

    <!-- CONTENU PRINCIPAL -->
    <main class="contenu">

        <h1>📦 Ajouter un colis</h1>
        <p class="sous-titre">Enregistrer l'arrivée d'un nouveau colis avec scan/photo de l'étiquette</p>

        <?php if (!empty($message)): ?>
            <div class="message" style="<?= strpos($message, '✅') !== false ? 'background: #d4edda; color: #155724;' : 'background: #f8d7da; color: #721c24;' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div style="display: flex; gap: 30px; flex-wrap: wrap;">

            <!-- FORMULAIRE -->
            <form method="POST" enctype="multipart/form-data" class="form-colis" style="flex: 1; min-width: 400px;">

                <label>Numéro du bon de commande (BC) *</label>
                <input type="text" name="numero_bc" placeholder="Ex: BC2024-001" required>

                <label>Numéro de suivi</label>
                <input type="text" name="numero_suivi" placeholder="Ex: FR123456789">

                <label>Commentaire</label>
                <textarea name="commentaire" rows="3" placeholder="Notes additionnelles..."></textarea>

                <!-- Champ caché pour la photo -->
                <input type="hidden" id="photo_etiquette" name="photo_etiquette">

                <button type="submit" class="btn-valider">➕ Ajouter le colis</button>

            </form>

            <!-- SECTION SCAN / PHOTO -->
            <div style="flex: 1; min-width: 400px; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 3px 12px rgba(0,0,0,0.06);">
                <h3 style="margin: 0 0 15px 0; color: #0d47a1; font-size: 18px;">📸 Scanner / Photographier l'Étiquette</h3>

                <div id="cameraContainer" style="position: relative; background: #f5f7fb; border: 2px dashed #0d47a1; border-radius: 8px; padding: 20px; text-align: center; margin-bottom: 15px; min-height: 300px; display: flex; align-items: center; justify-content: center;">
                    <video id="video" autoplay playsinline style="width: 100%; max-width: 100%; border-radius: 8px; display: none;"></video>
                    <canvas id="canvas" style="display: none;"></canvas>
                    <img id="preview" style="max-width: 100%; max-height: 400px; border-radius: 8px; display: none;">
                    
                    <div id="placeholder" style="text-align: center;">
                        <p style="color: #666; margin: 20px 0; font-size: 16px;">📷 Cliquez pour activer la caméra</p>
                        <p style="color: #999; font-size: 14px;">ou importez une photo existante</p>
                    </div>
                </div>

                <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                    <button type="button" id="btnStartCamera" style="background: #2563eb; color: white; padding: 12px 20px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 14px;">
                        📷 Activer la caméra
                    </button>
                    <button type="button" id="btnCapture" style="background: #28a745; color: white; padding: 12px 20px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 14px; display: none;">
                        📸 Prendre la photo
                    </button>
                    <button type="button" id="btnRetake" style="background: #dc3545; color: white; padding: 12px 20px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 14px; display: none;">
                        🔄 Reprendre
                    </button>
                </div>

                <div style="margin-top: 20px; padding: 15px; background: #e3f2fd; border-radius: 8px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #0d47a1;">
                        📎 Ou importer une photo
                    </label>
                    <input type="file" 
                           id="fileUpload" 
                           accept="image/*" 
                           capture="environment"
                           style="width: 100%; padding: 10px; border: 1px solid #0d47a1; border-radius: 6px; background: white; cursor: pointer;">
                </div>

                <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 12px; border-radius: 6px; margin-top: 15px;">
                    <p style="margin: 0; color: #856404; font-size: 13px;">
                      La photo de l'étiquette aide à identifier automatiquement le bon de commande associé.
                    </p>
                </div>
            </div>

        </div>

    </main>

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
                    video: { 
                        facingMode: 'environment',
                        width: { ideal: 1920 },
                        height: { ideal: 1080 }
                    } 
                });
                video.srcObject = stream;
                video.style.display = 'block';
                placeholder.style.display = 'none';
                btnStartCamera.style.display = 'none';
                btnCapture.style.display = 'inline-block';
            } catch (err) {
                alert('❌ Impossible d\'accéder à la caméra : ' + err.message);
                console.error(err);
            }
        });

        // Prendre la photo
        btnCapture.addEventListener('click', () => {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0);

            const imageData = canvas.toDataURL('image/jpeg', 0.7);
            photoInput.value = imageData;

            preview.src = imageData;
            preview.style.display = 'block';
            video.style.display = 'none';

            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }

            btnCapture.style.display = 'none';
            btnRetake.style.display = 'inline-block';
        });

        // Reprendre une photo
        btnRetake.addEventListener('click', () => {
            preview.style.display = 'none';
            placeholder.style.display = 'block';
            btnStartCamera.style.display = 'inline-block';
            btnRetake.style.display = 'none';
            photoInput.value = '';
            fileUpload.value = '';
        });

        // Import fichier
        fileUpload.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    const img = new Image();
                    img.onload = () => {
                        const maxWidth = 1920;
                        const maxHeight = 1080;
                        let width = img.width;
                        let height = img.height;

                        if (width > height && width > maxWidth) {
                            height *= maxWidth / width;
                            width = maxWidth;
                        } else if (height > maxHeight) {
                            width *= maxHeight / height;
                            height = maxHeight;
                        }

                        canvas.width = width;
                        canvas.height = height;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0, width, height);

                        const imageData = canvas.toDataURL('image/jpeg', 0.7);
                        photoInput.value = imageData;
                        preview.src = imageData;
                        preview.style.display = 'block';
                        placeholder.style.display = 'none';
                        video.style.display = 'none';

                        if (stream) {
                            stream.getTracks().forEach(track => track.stop());
                        }

                        btnStartCamera.style.display = 'none';
                        btnCapture.style.display = 'none';
                        btnRetake.style.display = 'inline-block';
                    };
                    img.src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        window.addEventListener('beforeunload', () => {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
        });

        const buttons = [btnStartCamera, btnCapture, btnRetake];
        buttons.forEach(btn => {
            btn.addEventListener('mouseenter', () => {
                btn.style.opacity = '0.9';
                btn.style.transform = 'translateY(-2px)';
            });
            btn.addEventListener('mouseleave', () => {
                btn.style.opacity = '1';
                btn.style.transform = 'translateY(0)';
            });
        });
    </script>

</body>
</html>
