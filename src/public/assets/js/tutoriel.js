/* =====================================================================
   Tutoriel de prise en main, affiche automatiquement a la PREMIERE visite.
   - Memorise dans localStorage qu'il a deja ete vu (ne reapparait plus).
   - Peut etre force pour une demo en ajoutant ?tuto=1 a l'URL.
   ===================================================================== */
(function () {
    "use strict";

    // Cle propre a chaque utilisateur : chaque compte revoit le tuto a sa
    // premiere connexion, meme si un autre utilisateur l'a deja vu sur ce navigateur.
    var CLE = "tuto_iut_vu_" + (window.TUTO_USER || "anon");
    var forcer = window.location.search.indexOf("tuto=1") !== -1;

    // Deja vu et pas force -> on ne fait rien.
    if (!forcer && localStorage.getItem(CLE)) return;

    // Icones SVG (style trait, heritent de la couleur via currentColor)
    var svg = function (paths) {
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" ' +
               'stroke-linecap="round" stroke-linejoin="round">' + paths + '</svg>';
    };

    var etapes = [
        { icone: svg('<path d="M9.8 15.9 9 18.75l-.8-2.85a4.5 4.5 0 0 0-3.1-3.1L2.25 12l2.85-.8a4.5 4.5 0 0 0 3.1-3.1L9 5.25l.8 2.85a4.5 4.5 0 0 0 3.1 3.1L15.75 12l-2.85.8a4.5 4.5 0 0 0-3.1 3.1Z"/><path d="M18 8.7 17.8 9.75 17.5 8.7a3.4 3.4 0 0 0-2.45-2.45L14.25 6l1.05-.26A3.4 3.4 0 0 0 17.75 3.3L18 2.25l.26 1.05A3.4 3.4 0 0 0 20.7 5.74L21.75 6l-1.05.26A3.4 3.4 0 0 0 18 8.7Z"/>'),
          titre: "Bienvenue !",
          texte: "Voici l'application de suivi des colis de l'IUT. Laissez-vous guider en quelques étapes." },
        { icone: svg('<path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>'),
          titre: "Le menu",
          texte: "Utilisez le menu à gauche pour naviguer entre les différentes sections de l'application." },
        { icone: svg('<path d="m20.25 7.5-.63 10.63a2.25 2.25 0 0 1-2.24 2.12H6.62a2.25 2.25 0 0 1-2.24-2.12L3.75 7.5M10 11.25h4M3.38 7.5h17.24c.62 0 1.13-.5 1.13-1.13v-1.5c0-.62-.5-1.12-1.13-1.12H3.38c-.62 0-1.13.5-1.13 1.12v1.5c0 .63.5 1.13 1.13 1.13Z"/>'),
          titre: "Vos colis",
          texte: "Suivez l'état de vos colis et de vos commandes, mis à jour en temps réel." },
        { icone: svg('<circle cx="12" cy="12" r="8.25"/><circle cx="12" cy="12" r="3.75"/><path d="m14.65 9.35 3.6-3.6M5.75 18.25l3.6-3.6m0-5.3-3.6-3.6m12.5 12.5-3.6-3.6"/>'),
          titre: "Besoin d'aide ?",
          texte: "Cliquez sur \"Assistance\" dans le menu pour signaler un problème et suivre vos tickets." },
        { icone: svg('<path d="M4.5 16.5c-1.5 1.26-2 5.25-2 5.25s3.99-.5 5.25-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-3.16-.34ZM15 5.25A14.95 14.95 0 0 0 9.6 8.4 14.98 14.98 0 0 0 6.16 14.4l3.44 3.44a14.98 14.98 0 0 0 6-3.44A14.95 14.95 0 0 0 18.75 9M15 5.25c1.5-1.5 3.75-2.25 6-2.25 0 2.25-.75 4.5-2.25 6M15 5.25 18.75 9m-9.36-.6a6 6 0 0 0-2.91-.09M15.6 14.6a6 6 0 0 1-.09 2.91"/>'),
          titre: "C'est parti !",
          texte: "Vous êtes prêt. Bonne navigation sur l'application !" }
    ];

    var index = 0;

    var overlay = document.createElement("div");
    overlay.id = "tuto-overlay";
    overlay.innerHTML =
        '<div class="tuto-modale" role="dialog" aria-modal="true">' +
            '<div class="tuto-icone"></div>' +
            '<h2 class="tuto-titre"></h2>' +
            '<p class="tuto-texte"></p>' +
            '<div class="tuto-points"></div>' +
            '<div class="tuto-actions">' +
                '<button type="button" class="tuto-passer">Passer</button>' +
                '<div>' +
                    '<button type="button" class="tuto-btn tuto-btn-secondaire tuto-prec" style="display:none">Précédent</button> ' +
                    '<button type="button" class="tuto-btn tuto-btn-primaire tuto-suiv"></button>' +
                '</div>' +
            '</div>' +
        '</div>';
    document.body.appendChild(overlay);

    var elIcone  = overlay.querySelector(".tuto-icone");
    var elTitre  = overlay.querySelector(".tuto-titre");
    var elTexte  = overlay.querySelector(".tuto-texte");
    var elPoints = overlay.querySelector(".tuto-points");
    var btnPrec  = overlay.querySelector(".tuto-prec");
    var btnSuiv  = overlay.querySelector(".tuto-suiv");
    var btnPasser = overlay.querySelector(".tuto-passer");

    // Construction des points de progression
    etapes.forEach(function () {
        var p = document.createElement("span");
        p.className = "tuto-point";
        elPoints.appendChild(p);
    });
    var points = elPoints.querySelectorAll(".tuto-point");

    function afficher() {
        var e = etapes[index];
        elIcone.innerHTML = e.icone;
        elTitre.textContent = e.titre;
        elTexte.textContent = e.texte;
        btnPrec.style.display = index === 0 ? "none" : "inline-block";
        btnSuiv.textContent = index === etapes.length - 1 ? "Terminer" : "Suivant";
        points.forEach(function (p, i) {
            p.classList.toggle("actif", i === index);
        });
    }

    function terminer() {
        localStorage.setItem(CLE, "1");
        overlay.remove();
    }

    btnSuiv.addEventListener("click", function () {
        if (index === etapes.length - 1) { terminer(); }
        else { index++; afficher(); }
    });
    btnPrec.addEventListener("click", function () {
        if (index > 0) { index--; afficher(); }
    });
    btnPasser.addEventListener("click", terminer);

    afficher();
})();
