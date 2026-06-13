// assets/js/ocr-etiquette.js

async function lancerOCR(imageBase64, callback) {
    console.log("lancerOCR appelé !");

    const loader = document.getElementById("ocr-loader");
    const message = document.getElementById("ocr-message");

    try {
        if (loader) {
            loader.style.display = "block";
        }

        if (message) {
            message.textContent = "Analyse OCR en cours...";
            message.className = "";
        }

        const resultat = await Tesseract.recognize(
            imageBase64,
            "fra"
        );

        const texte = resultat.data.text;
        console.log("TEXTE EXTRAIT :", texte);
        console.log("CONFIANCE :", resultat.data.confidence);

        const confiance = resultat.data.confidence / 100;

        const matchUPS = texte.match(/TRACKING\s*#:\s*([^\n]+)/);
        console.log("MATCH UPS :", matchUPS);
        const matchChronopost = texte.replace(/\s/g, '').match(/[A-Z]{2}\d{9}[A-Z]{2}/);

        const numeroBC = "";
        const numeroSuivi = matchUPS ? matchUPS[1].replace(/\s/g, '') : (matchChronopost ? matchChronopost[0] : "");

        // Extraire le nom 
        const matchNom = texte.match(/SHIP\s*T[O0]\s*:\s*\n?\s*(.+)/i);
        const nomDestinataire = matchNom ? matchNom[1].trim() : "";
        console.log("NUMÉRO SUIVI :", numeroSuivi);
        console.log("NOM DESTINATAIRE :", nomDestinataire);

        if (!numeroBC && !numeroSuivi) {
            if (message) {
                message.textContent = "Aucune référence détectée. Veuillez compléter les champs manuellement.";
                message.className = "text-danger";
            }
        } else {
            if (message) {
                if (confiance < 0.5) {
                    message.textContent = "Références détectées mais confiance OCR faible.";
                    message.className = "text-warning";
                } else {
                    message.textContent = "Références détectées automatiquement.";
                    message.className = "text-success";
                }
            }
        }

        callback({
            numeroBC: numeroBC,
            numeroSuivi: numeroSuivi,
            nomDestinataire: nomDestinataire,
            texteBrut: texte,
            confiance: confiance
        });

    } catch (erreur) {
        console.error(erreur);

        if (message) {
            message.textContent = "Erreur lors de l'analyse OCR.";
            message.className = "text-danger";
        }

        callback({
            numeroBC: "",
            numeroSuivi: "",
            texteBrut: "",
            confiance: 0
        });

    } finally {
        if (loader) {
            loader.style.display = "none";
        }
    }
}