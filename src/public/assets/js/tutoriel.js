/* =====================================================================
   Tutoriel de prise en main : tour guide qui surligne chaque section
   et place une bulle d'explication a cote.
   - Memorise dans localStorage (par utilisateur) -> ne revient plus.
   - Force pour une demo en ajoutant ?tuto=1 a l'URL.
   ===================================================================== */
(function () {
    "use strict";

    var CLE = "tuto_iut_vu_" + (window.TUTO_USER || "anon");
    var forcer = window.location.search.indexOf("tuto=1") !== -1;
    if (!forcer && localStorage.getItem(CLE)) return;

    var svg = function (p) {
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" ' +
               'stroke-linecap="round" stroke-linejoin="round">' + p + '</svg>';
    };

    // Chaque etape : cible (selecteur CSS, optionnel) + titre + texte.
    // Sans cible -> bulle centree (intro / fin).
    var etapes = [
        { icone: svg('<path d="M9.8 15.9 9 18.75l-.8-2.85a4.5 4.5 0 0 0-3.1-3.1L2.25 12l2.85-.8a4.5 4.5 0 0 0 3.1-3.1L9 5.25l.8 2.85a4.5 4.5 0 0 0 3.1 3.1L15.75 12l-2.85.8a4.5 4.5 0 0 0-3.1 3.1Z"/>'),
          titre: "Bienvenue !",
          texte: "Voici l'application de suivi des colis de l'IUT. Petit tour des sections en quelques etapes." },
        { cible: ".navbar-menu",
          icone: svg('<path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>'),
          titre: "Le menu",
          texte: "Naviguez entre les differentes sections depuis cette barre en haut." },
        { cible: 'a[href="/tickets"]',
          icone: svg('<circle cx="12" cy="12" r="8.25"/><circle cx="12" cy="12" r="3.75"/>'),
          titre: "Besoin d'aide ?",
          texte: "Signalez un probleme via \"Assistance\". La pastille rouge indique de nouvelles reponses." },
        { cible: ".navbar-utilisateur",
          icone: svg('<circle cx="12" cy="8" r="3.5"/><path d="M5 20a7 7 0 0 1 14 0"/>'),
          titre: "Votre compte",
          texte: "Votre nom, votre role, et le bouton de deconnexion se trouvent ici." },
        { cible: ".page-header",
          icone: svg('<rect x="3.5" y="4.5" width="17" height="15" rx="2"/><path d="M3.5 9h17"/>'),
          titre: "Le contenu",
          texte: "Le contenu de chaque page s'affiche dans cette zone principale." },
        { icone: svg('<path d="m5 13 4 4L19 7"/>'),
          titre: "C'est parti !",
          texte: "Vous etes pret. Bonne navigation sur l'application !" }
    ];

    var i = 0;

    var fond  = document.createElement("div"); fond.id  = "tuto-fond";
    var spot  = document.createElement("div"); spot.id  = "tuto-spot";
    var bulle = document.createElement("div"); bulle.id = "tuto-bulle";
    bulle.innerHTML =
        '<div class="tuto-icone"></div>' +
        '<h2 class="tuto-titre"></h2>' +
        '<p class="tuto-texte"></p>' +
        '<div class="tuto-points"></div>' +
        '<div class="tuto-actions">' +
            '<button type="button" class="tuto-passer">Passer</button>' +
            '<div>' +
                '<button type="button" class="tuto-btn tuto-secondaire tuto-prec" style="display:none">Precedent</button> ' +
                '<button type="button" class="tuto-btn tuto-principal tuto-suiv"></button>' +
            '</div>' +
        '</div>';
    document.body.appendChild(fond);
    document.body.appendChild(spot);
    document.body.appendChild(bulle);

    var elIcone = bulle.querySelector(".tuto-icone");
    var elTitre = bulle.querySelector(".tuto-titre");
    var elTexte = bulle.querySelector(".tuto-texte");
    var elPts   = bulle.querySelector(".tuto-points");
    var btnPrec = bulle.querySelector(".tuto-prec");
    var btnSuiv = bulle.querySelector(".tuto-suiv");
    var btnPass = bulle.querySelector(".tuto-passer");

    etapes.forEach(function () {
        var p = document.createElement("span"); p.className = "tuto-point"; elPts.appendChild(p);
    });
    var points = elPts.querySelectorAll(".tuto-point");

    function positionner() {
        var e = etapes[i];
        var cible = e.cible ? document.querySelector(e.cible) : null;
        var rect = cible ? cible.getBoundingClientRect() : null;

        // Cible absente ou invisible -> bulle centree, fond plein.
        if (!rect || rect.width === 0 || rect.height === 0) {
            spot.style.display = "none";
            fond.style.display = "block";
            bulle.classList.add("centre");
            bulle.style.left = "50%";
            bulle.style.top = "50%";
            return;
        }

        fond.style.display = "none";
        spot.style.display = "block";
        bulle.classList.remove("centre");

        var pad = 6;
        spot.style.left   = (rect.left - pad) + "px";
        spot.style.top    = (rect.top - pad) + "px";
        spot.style.width  = (rect.width + pad * 2) + "px";
        spot.style.height = (rect.height + pad * 2) + "px";

        // Bulle sous la cible si la place le permet, sinon au-dessus.
        var bh = bulle.offsetHeight || 200;
        var bw = bulle.offsetWidth || 360;
        var top = rect.bottom + 14;
        if (top + bh > window.innerHeight - 10) top = Math.max(10, rect.top - bh - 14);
        var left = rect.left + rect.width / 2 - bw / 2;
        left = Math.max(10, Math.min(left, window.innerWidth - bw - 10));
        bulle.style.left = left + "px";
        bulle.style.top  = top + "px";
    }

    function afficher() {
        var e = etapes[i];
        elIcone.innerHTML = e.icone || "";
        elTitre.textContent = e.titre;
        elTexte.textContent = e.texte;
        btnPrec.style.display = i === 0 ? "none" : "inline-block";
        btnSuiv.textContent = i === etapes.length - 1 ? "Terminer" : "Suivant";
        points.forEach(function (p, k) { p.classList.toggle("actif", k === i); });

        var cible = e.cible ? document.querySelector(e.cible) : null;
        if (cible && cible.scrollIntoView) cible.scrollIntoView({ block: "center", behavior: "smooth" });
        setTimeout(positionner, cible ? 250 : 0);
    }

    function terminer() {
        localStorage.setItem(CLE, "1");
        fond.remove(); spot.remove(); bulle.remove();
        window.removeEventListener("resize", positionner);
    }

    btnSuiv.addEventListener("click", function () {
        if (i === etapes.length - 1) terminer(); else { i++; afficher(); }
    });
    btnPrec.addEventListener("click", function () { if (i > 0) { i--; afficher(); } });
    btnPass.addEventListener("click", terminer);
    window.addEventListener("resize", positionner);

    afficher();
})();
