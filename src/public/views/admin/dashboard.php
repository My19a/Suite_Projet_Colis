<?php
$titre = 'Dashboard – Administrateur';
$actif = '/admin/dashboard';
$avecTutoriel = true;
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Tableau de bord</h1>
            <p class="page-subtitle">Vue globale du système</p>
        </div>
        <form method="post" action="/admin/test-mail" style="display:flex; gap:0.5rem; align-items:center;">
            <input type="email" name="to" class="saisie" placeholder="destinataire@exemple.com"
                   value="<?= htmlspecialchars(getenv('MAIL_TEST_TO') ?: '') ?>" required
                   style="min-width:240px;">
            <button type="submit" class="bouton bouton-secondaire">Envoyer mail de test</button>
        </form>
    </div>

    <?php if (isset($_GET['mail'])): ?>
        <?php if ($_GET['mail'] === 'ok'): ?>
            <div class="message message-ok">
                Mail de test envoyé avec succès vers <strong><?= htmlspecialchars($_GET['to'] ?? '') ?></strong>.
            </div>
        <?php else: ?>
            <div class="message message-err">
                Erreur lors de l'envoi : <?= htmlspecialchars($_GET['msg'] ?? 'inconnue') ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="chiffres">
        <div class="chiffre">
            <span class="chiffre-titre">Utilisateurs</span>
            <div class="chiffre-valeur"><?= $stats["utilisateurs"] ?></div>
            <div class="chiffre-info">Total</div>
        </div>

        <div class="chiffre chiffre-info-c">
            <span class="chiffre-titre">Devis</span>
            <div class="chiffre-valeur"><?= $stats["devis"] ?></div>
            <div class="chiffre-info">Total</div>
        </div>

        <div class="chiffre chiffre-attn">
            <span class="chiffre-titre">Bons de commande</span>
            <div class="chiffre-valeur"><?= $stats["bons"] ?></div>
            <div class="chiffre-info">Total</div>
        </div>

        <div class="chiffre chiffre-ok">
            <span class="chiffre-titre">Colis</span>
            <div class="chiffre-valeur"><?= $stats["colis"] ?></div>
            <div class="chiffre-info">Total</div>
        </div>
    </div>

    <div class="bloc-entete">
        <h2 class="bloc-titre">Répartition des utilisateurs par rôle</h2>
    </div>

    <?php if (empty($roles)): ?>
        <div class="vide-cadre">Aucun rôle trouvé</div>
    <?php else: ?>
        <div class="chiffres">
            <?php foreach ($roles as $r): ?>
                <div class="chiffre">
                    <span class="chiffre-titre"><?= htmlspecialchars(libelleRole($r["libelle"])) ?></span>
                    <div class="chiffre-valeur"><?= $r["total"] ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
