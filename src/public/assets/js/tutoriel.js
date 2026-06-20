/* =====================================================================
   Tutoriel de prise en main : tour guide qui surligne chaque section
   et place une bulle d'explication a cote.
   - Memorise par compte cote serveur (colonne utilisateur.tuto_vu).
   - Force pour une demo en ajoutant ?tuto=1 a l'URL.
   ===================================================================== */
(function () {
    "use strict";

    // Le tuto est memorise par compte cote serveur (window.TUTO_VU).
    // ?tuto=1 force l'affichage (pour une demo).
    var forcer = window.location.search.indexOf("tuto=1") !== -1;
    if (!forcer && window.TUTO_VU) return;

    var svg = function (p) {
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" ' +
               'stroke-linecap="round" stroke-linejoin="round">' + p + '</svg>';
    };

    // Etape "Votre espace" : nom du role + chaque onglet du menu explique.
    var role = window.TUTO_ROLE || "";
    var roleNom = {
        admin: "administrateur",
        demandeur: "demandeur",
        responsable_colis: "responsable colis",
        editeur_bc: "éditeur de bons de commande"
    };
    // A quoi sert chaque onglet (par URL) : sert a expliquer le menu du role.
    var descOnglet = {
        "/admin/dashboard": "vue d'ensemble de l'activité",
        "/admin/utilisateurs": "créer, modifier et supprimer les comptes",
        "/admin/departements": "gérer les départements de l'IUT",
        "/admin/fournisseurs": "gérer la liste des fournisseurs",
        "/admin/devis": "consulter tous les devis",
        "/admin/colis": "consulter tous les colis",
        "/presence": "voir les utilisateurs connectés en ce moment",
        "/postal/dashboard": "vue d'ensemble de l'activité",
        "/postal/commandes": "les commandes en attente de réception",
        "/postal/reception": "déclarer la réception d'un colis (n° de suivi + demandeur)",
        "/postal/colis": "les colis à transférer vers l'IUT",
        "/postal/historique": "l'historique des colis traités",
        "/departement/dashboard": "vue d'ensemble de votre activité",
        "/departement/creer-devis": "faire une nouvelle demande de devis",
        "/departement/mes-devis": "suivre vos devis et leur statut",
        "/departement/bons-commande": "vos bons de commande",
        "/departement/mes-colis": "suivre l'arrivée de vos colis",
        "/departement/budget": "consulter votre budget",
        "/departement/fournisseurs": "consulter les fournisseurs disponibles",
        "/finance/dashboard": "vue d'ensemble de l'activité",
        "/finance/devis": "vérifier et valider les devis",
        "/directeur/devis": "signer les devis validés et déclarer les colis",
        "/finance/bons-commande": "les bons de commande",
        "/finance/budgets": "suivre les budgets des départements",
        "/tickets": "contacter le support ; la pastille rouge signale de nouvelles réponses"
    };
    // Liste reelle des onglets du menu (lue dans la navbar) : [nom, explication, href].
    var ongletsDetail = Array.prototype.slice.call(document.querySelectorAll(".navbar-menu a"))
        .map(function (a) {
            var nom = a.textContent.trim().replace(/\s*\d+$/, "");
            return [nom, descOnglet[a.getAttribute("href")] || "", a.getAttribute("href")];
        })
        .filter(function (it) { return it[0]; });
    // Premiere lettre en majuscule (pour les phrases d'explication).
    var cap = function (s) { return s ? s.charAt(0).toUpperCase() + s.slice(1) : s; };
    // Une etape par onglet : on surligne le lien et on explique a quoi il sert.
    var icoOnglet = svg('<circle cx="12" cy="12" r="8.5"/><path d="M10.5 8.5l3.5 3.5-3.5 3.5"/>');
    var etapesOnglets = ongletsDetail.map(function (it) {
        return {
            cible: '.navbar-menu a[href="' + it[2] + '"]',
            icone: icoOnglet,
            titre: it[0],
            texte: it[1] ? cap(it[1]) + "." : "Accédez à la section " + it[0] + "."
        };
    });
    var roleIntro = "En tant que " + (roleNom[role] || "utilisateur") +
        ", naviguez entre vos sections depuis cette barre. On les passe en revue une par une.";

    // Chaque etape : cible (selecteur CSS, optionnel) + titre + texte.
    // Sans cible -> bulle centree (intro / fin).
    var etapes = [
        { icone: svg('<path d="M9.8 15.9 9 18.75l-.8-2.85a4.5 4.5 0 0 0-3.1-3.1L2.25 12l2.85-.8a4.5 4.5 0 0 0 3.1-3.1L9 5.25l.8 2.85a4.5 4.5 0 0 0 3.1 3.1L15.75 12l-2.85.8a4.5 4.5 0 0 0-3.1 3.1Z"/>'),
          titre: "Bienvenue !",
          texte: "Voici l'application de suivi des colis de l'IUT. Petit tour des sections en quelques étapes." },
        { cible: ".navbar-menu",
          icone: svg('<path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>'),
          titre: "Votre menu",
          texte: roleIntro }
    ].concat(etapesOnglets).concat([
        { cible: ".navbar-utilisateur",
          icone: svg('<circle cx="12" cy="8" r="3.5"/><path d="M5 20a7 7 0 0 1 14 0"/>'),
          titre: "Votre compte",
          texte: "Votre nom, votre rôle, et le bouton de déconnexion se trouvent ici." },
        { cible: ".contenu",
          icone: svg('<rect x="3.5" y="4.5" width="17" height="15" rx="2"/><path d="M3.5 9h17"/>'),
          titre: "Le contenu",
          texte: "C'est ici, dans la zone principale, que s'affiche tout le contenu de chaque page : informations, listes et formulaires." },
        { icone: svg('<path d="m5 13 4 4L19 7"/>'),
          titre: "C'est parti !",
          texte: "Vous êtes prêt. Bonne navigation sur l'application !" }
    ]);

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
                '<button type="button" class="tuto-btn tuto-secondaire tuto-prec" style="display:none">Précédent</button> ' +
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

        var bh = bulle.offsetHeight || 200;
        var bw = bulle.offsetWidth || 360;

        // Cible tres grande (ex. zone de contenu) -> bulle centree par-dessus.
        if (rect.height > window.innerHeight * 0.55) {
            bulle.style.left = (window.innerWidth / 2 - bw / 2) + "px";
            bulle.style.top  = (window.innerHeight / 2 - bh / 2) + "px";
            return;
        }

        // Sinon : bulle sous la cible si la place le permet, sinon au-dessus.
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
        // Memorise cote serveur (par compte) que le tuto a ete vu.
        if (window.fetch) {
            fetch("/tuto/vu", { method: "POST", headers: { "X-Requested-With": "fetch" } });
        }
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
