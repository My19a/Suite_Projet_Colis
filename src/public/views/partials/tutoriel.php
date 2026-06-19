<?php
/**
 * Partial du tutoriel de prise en main (pop-up premiere visite).
 * A inclure juste avant </body> dans les pages d'accueil (dashboards) :
 *
 *   <?php require __DIR__ . '/../partials/tutoriel.php'; ?>
 *
 */
?>
<link rel="stylesheet" href="/assets/css/style-tutoriel.css">
<?php
// Identifiant de l'utilisateur courant : le tutoriel est memorise PAR utilisateur,
// donc chaque compte le revoit a sa premiere connexion (meme navigateur partage).
$tutoUser = isset($_SESSION['user']) ? (string) $_SESSION['user']->getId() : 'anon';
$tutoRole = isset($_SESSION['user']) ? (string) $_SESSION['user']->getRole() : '';
?>
<script>window.TUTO_USER = <?= json_encode($tutoUser) ?>; window.TUTO_ROLE = <?= json_encode($tutoRole) ?>;</script>
<script src="/assets/js/tutoriel.js" defer></script>
