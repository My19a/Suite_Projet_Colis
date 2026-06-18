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
</body>
</html>

<?php 
// Sécurité pour cibler le dossier partials qui est dans views/
if (file_exists(__DIR__ . '/partials/chatbot.php')) {
    include __DIR__ . '/partials/chatbot.php';
} else {
    // Si votre footer est dans un sous-dossier (ex: views/admin/footer.php), on remonte d'un cran
    include __DIR__ . '/../partials/chatbot.php';
}
?>