<?php
$titre = 'Utilisateurs connectés – Admin';
$actif = '/presence';
$feuillesDeStyle = ['/assets/css/style-presence.css'];
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
    <div class="page-header-info">
        <h1 class="page-title">Utilisateurs connectés</h1>
        <p class="page-subtitle"><?= (int) $nbEnLigne ?> utilisateur(s) en ligne (activité &lt; <?= (int) $seuil ?> min)</p>
    </div>
</div>

<?php if (empty($utilisateurs)): ?>
    <?= etatVide('utilisateurs', 'Aucun utilisateur', 'Personne pour le moment.') ?>
<?php else: ?>
    <div class="bloc">
        <div class="tableau-cadre">
            <table class="tableau tableau-aere">
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Rôle</th>
                        <th>État</th>
                        <th>Dernière activité</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($utilisateurs as $u): ?>
                        <?php $enLigne = (int) $u['en_ligne'] === 1; ?>
                        <tr>
                            <td>
                                <span class="pastille <?= $enLigne ? 'pastille-on' : 'pastille-off' ?>"></span>
                                <?= e($u['fullName']) ?>
                            </td>
                            <td><?= e(libelleRole($u['role'])) ?></td>
                            <td>
                                <span class="badge <?= $enLigne ? 'badge-resolu' : 'badge-hors' ?>">
                                    <?= $enLigne ? 'En ligne' : 'Hors ligne' ?>
                                </span>
                            </td>
                            <td>
                                <?php if (empty($u['derniere_activite'])): ?>
                                    Jamais connecté
                                <?php elseif ($enLigne): ?>
                                    À l'instant
                                <?php else: ?>
                                    <?= date('d/m/Y H:i', strtotime($u['derniere_activite'])) ?>
                                    (il y a <?= (int) $u['minutes_inactif'] ?> min)
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
