<?php
/**
 * Pied de page commun à toutes les vues.
 *
 * Variables optionnelles (à définir avant le require) :
 * $avecTutoriel (bool) : inclut le pop-up tutoriel (pages dashboard)
 */
?>
</main>

<footer class="pied-page">
    <div class="pied-page-contenu">
        <span>© <?= date('Y') ?> IUT de Villetaneuse — Suivi Colis</span>
    </div>
</footer>

<?php if (!empty($avecTutoriel)) require __DIR__ . '/tutoriel.php'; ?>

<?php require __DIR__ . '/chatbot.php'; ?>

</body>
</html>