<?php
$titre = 'Déclaration d\'accessibilité – Suivi Colis';
require __DIR__ . '/../partials/header.php';
?>

<article class="largeur-moyenne">
    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Déclaration d'accessibilité</h1>
            <p class="page-subtitle">Conformité au Référentiel Général d'Amélioration de l'Accessibilité (RGAA 4.1)</p>
        </div>
    </div>

    <div class="bloc">
        <h2 class="bloc-titre">État de conformité</h2>
        <p class="mt-1">L'application <strong>Suivi Colis</strong> de l'IUT de Villetaneuse est
        <strong>partiellement conforme</strong> avec le RGAA 4.1, en raison des non-conformités
        et dérogations énumérées ci-dessous.</p>
    </div>

    <div class="bloc">
        <h2 class="bloc-titre">Mesures d'accessibilité mises en œuvre</h2>
        <ul style="margin: 8px 0 0 20px; line-height: 1.7;">
            <li>Contraste des textes du pied de page et des liens revu pour respecter le ratio WCAG&nbsp;AA (4,5:1 minimum).</li>
            <li>Lien d'évitement clavier permettant d'accéder directement au contenu principal.</li>
            <li>Indicateurs de focus visibles sur tous les éléments interactifs (RGAA&nbsp;10.7).</li>
            <li>Structure sémantique HTML5 (<code>&lt;header&gt;</code>, <code>&lt;main&gt;</code>, <code>&lt;nav&gt;</code>, <code>&lt;footer&gt;</code>) et rôles ARIA explicites.</li>
            <li>Libellés explicites pour les boutons et liens (attributs <code>aria-label</code> sur les actions iconiques).</li>
            <li>Langue de la page déclarée via <code>lang="fr"</code>.</li>
            <li>Mise en page responsive adaptée aux écrans mobiles et tablettes.</li>
            <li>Préservation des accents et de l'orthographe française dans toute l'interface.</li>
        </ul>
    </div>

    <div class="bloc">
        <h2 class="bloc-titre">Contenus non accessibles</h2>
        <p>Certains éléments restent à améliorer&nbsp;:</p>
        <ul style="margin: 8px 0 0 20px; line-height: 1.7;">
            <li>Les tableaux de données les plus longs ne disposent pas encore de balisage <code>scope</code> complet pour la navigation lecteur d'écran.</li>
            <li>Le pop-up tutoriel des tableaux de bord ne capture pas systématiquement le focus.</li>
            <li>Les images décoratives ne portent pas toutes un attribut <code>alt=""</code> explicite.</li>
        </ul>
    </div>

    <div class="bloc">
        <h2 class="bloc-titre">Voies de recours et contact</h2>
        <p>Si vous constatez un défaut d'accessibilité vous empêchant d'accéder à un contenu
        ou une fonctionnalité, contactez l'équipe via la page <a class="lien-action" href="/tickets">Assistance</a>
        ou par e-mail à <a class="lien-action" href="mailto:contact@iutv.univ-paris13.fr">contact@iutv.univ-paris13.fr</a>.</p>
        <p class="mt-1">Si vous n'obtenez pas de réponse rapide, vous pouvez saisir le
        <a class="lien-action" href="https://www.defenseurdesdroits.fr/" rel="noopener" target="_blank">Défenseur des droits</a>.</p>
    </div>

    <p class="sous-titre mt-2">Déclaration établie dans le cadre du projet SAÉ S4.01 — IUT de Villetaneuse.</p>
</article>

<?php require __DIR__ . '/../partials/footer.php'; ?>
