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
// Memorisation PAR COMPTE, cote base de donnees (colonne utilisateur.tuto_vu).
// Avantage : le tuto se montre une seule fois par compte sur n'importe quel
// navigateur, et "docker compose down -v" (base recreee) le remet a zero.
$tutoRole = isset($_SESSION['user']) ? (string) $_SESSION['user']->getRole() : '';
$tutoVu = 0;
if (isset($_SESSION['user'])) {
    try {
        require_once __DIR__ . '/../../models/Model.php';
        $req = Model::getModel()->bd->prepare("SELECT tuto_vu FROM utilisateur WHERE id_utilisateur = ?");
        $req->execute([$_SESSION['user']->getId()]);
        $tutoVu = (int) $req->fetchColumn();
    } catch (\Throwable $e) {
        $tutoVu = 0; // en cas de souci, on montre le tuto plutot que de le cacher
    }
}
?>
<script>window.TUTO_ROLE = <?= json_encode($tutoRole) ?>; window.TUTO_VU = <?= $tutoVu ?>;</script>
<script src="/assets/js/tutoriel.js" defer></script>
