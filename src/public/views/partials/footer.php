<?php
/**
 * Pied de page commun à toutes les vues.
 *
 * Variables optionnelles (à définir avant le require) :
 *   $avecTutoriel (bool) : inclut le pop-up tutoriel (pages dashboard)
 */
?>
</main>

<footer class="pied-page" role="contentinfo" aria-label="Informations légales et accessibilité">
    <div class="pied-page-contenu">
        <span class="pied-copyright">© <?= date('Y') ?> IUT de Villetaneuse — Suivi Colis</span>
        <nav class="pied-liens" aria-label="Liens de bas de page">
            <a href="/accessibilite">Accessibilité&nbsp;: partiellement conforme</a>
            <a href="/mentions-legales">Mentions légales</a>
        </nav>
    </div>
</footer>

<?php if (!empty($avecTutoriel)) require __DIR__ . '/tutoriel.php'; ?>
</body>
</html>
