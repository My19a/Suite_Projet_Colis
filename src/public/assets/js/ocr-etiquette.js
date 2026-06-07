// assets/js/ocr-etiquette.js

async function lancerOCR(imageBase64, callback) {
    const loader = document.getElementById("ocr-loader");
    const message = document.getElementById("ocr-message");

    try {
        // Affichage du loader

        if (loader) {
            loader.style.display = "block";
        }

        if (message) {
            message.textContent = "Analyse OCR en cours...";
            message.className = "";
        }

        // OCR avec Tesseract

        const resultat = await Tesseract.recognize(
            imageBase64,
            "fra"
        );

        // Texte extrait

        const texte = resultat.data.text;

        // Score de confiance entre 0 et 1

        const confiance = resultat.data.confidence / 100;

        // Recherche du numéro BC

        const matchBC = texte.match(
            /BC-\d{4}-\d{3}/
        );

        // Recherche du numéro de suivi

        const matchSuivi = texte.match(
            /LP\d{9}FR/
        );

        // Résultats

        const numeroBC =
            matchBC ? matchBC[0] : "";

        const numeroSuivi =
            matchSuivi ? matchSuivi[0] : "";

        // Message utilisateur

        if (!numeroBC && !numeroSuivi) {
            if (message) {
                message.textContent =
                    "Aucune référence détectée. Veuillez compléter les champs manuellement.";

                message.className =
                    "text-danger";
            }
        }
        else {
            if (message) {
                if (confiance < 0.5) {
                    message.textContent =
                        "Références détectées mais confiance OCR faible.";

                    message.className =
                        "text-warning";
                }
                else {
                    message.textContent =
                        "Références détectées automatiquement.";

                    message.className =
                        "text-success";
                }
            }
        }

        // Envoi du résultat à la page

        callback({
            numeroBC: numeroBC,
            numeroSuivi: numeroSuivi,
            texteBrut: texte,
            confiance: confiance
        });
    }
    catch (erreur) {
        console.error(erreur);

        if (message) {
            message.textContent =
                "Erreur lors de l'analyse OCR.";

            message.className =
                "text-danger";
        }

        callback({
            numeroBC: "",
            numeroSuivi: "",
            texteBrut: "",
            confiance: 0
        });
    }
    finally {
        if (loader) {
            loader.style.display = "none";
        }
    }
}