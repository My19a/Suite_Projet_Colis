<?php
$titre = 'Dashboard – Administrateur';
$actif = '/admin/dashboard';
$avecTutoriel = true;
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
    <div class="page-header-info">
        <h1 class="page-title">Tableau de bord</h1>
        <p class="page-subtitle">Vue d'ensemble de l'activité du système</p>
    </div>
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

<!-- Indicateurs clés -->
<div class="chiffres">
    <div class="chiffre">
        <span class="chiffre-titre">Utilisateurs</span>
        <div class="chiffre-valeur"><?= $stats['utilisateurs'] ?></div>
    </div>
    <div class="chiffre">
        <span class="chiffre-titre">Départements</span>
        <div class="chiffre-valeur"><?= $stats['departements'] ?></div>
    </div>
    <div class="chiffre <?= $stats['devis_en_cours'] > 0 ? 'chiffre-attn' : '' ?>">
        <span class="chiffre-titre">Devis à traiter</span>
        <div class="chiffre-valeur"><?= $stats['devis_en_cours'] ?></div>
    </div>
    <div class="chiffre">
        <span class="chiffre-titre">Colis</span>
        <div class="chiffre-valeur"><?= $stats['colis'] ?></div>
    </div>
    <div class="chiffre">
        <span class="chiffre-titre">Bons de commande</span>
        <div class="chiffre-valeur"><?= $stats['bons'] ?></div>
    </div>
    <div class="chiffre">
        <span class="chiffre-titre">Fournisseurs</span>
        <div class="chiffre-valeur"><?= $stats['fournisseurs'] ?></div>
    </div>
</div>

<?php
$maxDept = 0;
foreach ($colisParDepartement as $cd) { $maxDept = max($maxDept, (int) $cd['total']); }
?>

<!-- Colis par département -->
<section class="carte-tb carte-tb-pleine">
    <header class="carte-tb-entete">
        <span class="carte-tb-icone"><?= icone('colis', 18) ?></span>
        <div class="carte-tb-titres">
            <div class="carte-tb-titre">Colis par département</div>
            <div class="carte-tb-compte"><?= $stats['colis'] ?> colis au total</div>
        </div>
    </header>
    <div class="carte-tb-corps carte-tb-corps-aere">
        <?php if (empty($colisParDepartement)): ?>
            <div class="apercu-vide">Aucun colis</div>
        <?php else: ?>
            <div class="mini-graph">
                <?php foreach ($colisParDepartement as $cd): ?>
                    <?php $pct = $maxDept > 0 ? round((int) $cd['total'] / $maxDept * 100) : 0; ?>
                    <div class="mini-ligne">
                        <span class="mini-cle"><?= htmlspecialchars($cd['departement']) ?></span>
                        <span class="mini-piste"><span class="mini-jauge" style="width: <?= $pct ?>%;"></span></span>
                        <span class="mini-val"><?= $cd['total'] ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Cartes : une par domaine, deux par ligne -->
<div class="grille-tb">

    <!-- Utilisateurs -->
    <section class="carte-tb">
        <header class="carte-tb-entete">
            <span class="carte-tb-icone"><?= icone('utilisateurs', 18) ?></span>
            <div class="carte-tb-titres">
                <div class="carte-tb-titre">Utilisateurs</div>
                <div class="carte-tb-compte"><?= $stats['utilisateurs'] ?> enregistrés</div>
            </div>
        </header>
        <div class="carte-tb-corps">
            <?php if (empty($apercuUtilisateurs)): ?>
                <div class="apercu-vide">Aucun utilisateur</div>
            <?php else: ?>
                <ul class="apercu">
                    <?php foreach ($apercuUtilisateurs as $u): ?>
                        <li class="apercu-ligne">
                            <span class="apercu-avatar"><?= icone('utilisateur', 15) ?></span>
                            <div class="apercu-corps">
                                <div class="apercu-primaire"><?= htmlspecialchars($u['fullName']) ?></div>
                                <div class="apercu-secondaire"><?= htmlspecialchars($u['departement'] ?? '—') ?></div>
                            </div>
                            <span class="apercu-fin"><span class="badge"><?= htmlspecialchars(libelleRole($u['role'])) ?></span></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <footer class="carte-tb-pied">
            <a href="/admin/ajouter-utilisateur" class="lien-action"><?= icone('plus', 13) ?> Ajouter</a>
            <a href="/admin/utilisateurs" class="bouton bouton-secondaire bouton-petit">Voir plus</a>
        </footer>
    </section>

    <!-- Départements -->
    <section class="carte-tb">
        <header class="carte-tb-entete">
            <span class="carte-tb-icone"><?= icone('departements', 18) ?></span>
            <div class="carte-tb-titres">
                <div class="carte-tb-titre">Départements</div>
                <div class="carte-tb-compte"><?= $stats['departements'] ?> au total</div>
            </div>
        </header>
        <div class="carte-tb-corps">
            <?php if (empty($apercuDepartements)): ?>
                <div class="apercu-vide">Aucun département</div>
            <?php else: ?>
                <ul class="apercu">
                    <?php foreach ($apercuDepartements as $d): ?>
                        <?php $restant = (float) $d['budget_total'] - (float) $d['budget_utilise']; ?>
                        <li class="apercu-ligne">
                            <span class="apercu-icone"><?= icone('departements', 15) ?></span>
                            <div class="apercu-corps">
                                <div class="apercu-primaire"><?= htmlspecialchars($d['nom']) ?></div>
                                <div class="apercu-secondaire">Budget total <?= number_format($d['budget_total'], 0, ',', ' ') ?> EUR</div>
                            </div>
                            <span class="apercu-fin apercu-valeur <?= classeBudget($restant, $d['budget_total']) ?>"><?= number_format($restant, 0, ',', ' ') ?> EUR</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <footer class="carte-tb-pied">
            <a href="/admin/ajouter-departement" class="lien-action"><?= icone('plus', 13) ?> Ajouter</a>
            <a href="/admin/departements" class="bouton bouton-secondaire bouton-petit">Voir plus</a>
        </footer>
    </section>

    <!-- Devis -->
    <section class="carte-tb">
        <header class="carte-tb-entete">
            <span class="carte-tb-icone"><?= icone('devis', 18) ?></span>
            <div class="carte-tb-titres">
                <div class="carte-tb-titre">Devis</div>
                <div class="carte-tb-compte"><?= $stats['devis_en_cours'] ?> à traiter · <?= $stats['devis'] ?> au total</div>
            </div>
        </header>
        <div class="carte-tb-corps">
            <?php if (empty($apercuDevis)): ?>
                <div class="apercu-vide">Aucun devis</div>
            <?php else: ?>
                <ul class="apercu">
                    <?php foreach ($apercuDevis as $d): ?>
                        <li class="apercu-ligne">
                            <span class="apercu-icone"><?= icone('devis', 15) ?></span>
                            <div class="apercu-corps">
                                <div class="apercu-primaire"><?= htmlspecialchars($d['objet'] ?? '—') ?></div>
                                <div class="apercu-secondaire"><?= htmlspecialchars($d['fournisseur'] ?? '—') ?> · <?= number_format($d['montant_estime'], 0, ',', ' ') ?> EUR</div>
                            </div>
                            <span class="apercu-fin"><span class="<?= badgeStatut($d['statut']) ?>"><?= htmlspecialchars(libelleStatut($d['statut'])) ?></span></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <footer class="carte-tb-pied">
            <span class="carte-tb-note"><?= $stats['devis_en_cours'] ?> en attente de traitement</span>
            <a href="/admin/devis" class="bouton bouton-secondaire bouton-petit">Voir plus</a>
        </footer>
    </section>

    <!-- Bons de commande -->
    <section class="carte-tb">
        <header class="carte-tb-entete">
            <span class="carte-tb-icone"><?= icone('commandes', 18) ?></span>
            <div class="carte-tb-titres">
                <div class="carte-tb-titre">Bons de commande</div>
                <div class="carte-tb-compte"><?= $stats['bons'] ?> au total</div>
            </div>
        </header>
        <div class="carte-tb-corps">
            <?php if (empty($apercuCommandes)): ?>
                <div class="apercu-vide">Aucun bon de commande</div>
            <?php else: ?>
                <ul class="apercu">
                    <?php foreach ($apercuCommandes as $c): ?>
                        <li class="apercu-ligne">
                            <span class="apercu-icone"><?= icone('commandes', 15) ?></span>
                            <div class="apercu-corps">
                                <div class="apercu-primaire"><?= htmlspecialchars($c['numero_commande'] ?? '—') ?></div>
                                <div class="apercu-secondaire"><?= htmlspecialchars($c['departement'] ?? '—') ?> · <?= number_format($c['montant_estime'], 0, ',', ' ') ?> EUR</div>
                            </div>
                            <span class="apercu-fin"><span class="<?= badgeStatut($c['statut']) ?>"><?= htmlspecialchars(libelleStatut($c['statut'])) ?></span></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <footer class="carte-tb-pied">
            <span class="carte-tb-note"><?= $stats['bons'] ?> bon<?= $stats['bons'] > 1 ? 's' : '' ?> au total</span>
            <a href="/admin/commandes" class="bouton bouton-secondaire bouton-petit">Voir plus</a>
        </footer>
    </section>

    <!-- Colis -->
    <section class="carte-tb">
        <header class="carte-tb-entete">
            <span class="carte-tb-icone"><?= icone('colis', 18) ?></span>
            <div class="carte-tb-titres">
                <div class="carte-tb-titre">Colis</div>
                <div class="carte-tb-compte"><?= $stats['colis'] ?> au total</div>
            </div>
        </header>
        <div class="carte-tb-corps">
            <?php if (empty($apercuColis)): ?>
                <div class="apercu-vide">Aucun colis</div>
            <?php else: ?>
                <ul class="apercu">
                    <?php foreach ($apercuColis as $c): ?>
                        <li class="apercu-ligne">
                            <span class="apercu-icone"><?= icone('colis', 15) ?></span>
                            <div class="apercu-corps">
                                <div class="apercu-primaire"><?= htmlspecialchars($c['numero_suivi'] ?: '—') ?></div>
                                <div class="apercu-secondaire"><?= htmlspecialchars($c['departement'] ?: '—') ?></div>
                            </div>
                            <span class="apercu-fin"><span class="<?= badgeStatut($c['statut']) ?>"><?= htmlspecialchars(libelleStatut($c['statut'])) ?></span></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <footer class="carte-tb-pied">
            <span class="carte-tb-note"><?= $stats['colis'] ?> colis suivi<?= $stats['colis'] > 1 ? 's' : '' ?></span>
            <a href="/admin/colis" class="bouton bouton-secondaire bouton-petit">Voir plus</a>
        </footer>
    </section>

    <!-- Fournisseurs -->
    <section class="carte-tb">
        <header class="carte-tb-entete">
            <span class="carte-tb-icone"><?= icone('fournisseurs', 18) ?></span>
            <div class="carte-tb-titres">
                <div class="carte-tb-titre">Fournisseurs</div>
                <div class="carte-tb-compte"><?= $stats['fournisseurs'] ?> au total</div>
            </div>
        </header>
        <div class="carte-tb-corps">
            <?php if (empty($apercuFournisseurs)): ?>
                <div class="apercu-vide">Aucun fournisseur</div>
            <?php else: ?>
                <ul class="apercu">
                    <?php foreach ($apercuFournisseurs as $f): ?>
                        <li class="apercu-ligne">
                            <span class="apercu-icone"><?= icone('fournisseurs', 15) ?></span>
                            <div class="apercu-corps">
                                <div class="apercu-primaire"><?= htmlspecialchars($f['nom']) ?></div>
                                <div class="apercu-secondaire"><?= htmlspecialchars($f['contact_email'] ?: ($f['contact_nom'] ?: '—')) ?></div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <footer class="carte-tb-pied">
            <a href="/admin/ajouter-fournisseur" class="lien-action"><?= icone('plus', 13) ?> Ajouter</a>
            <a href="/admin/fournisseurs" class="bouton bouton-secondaire bouton-petit">Voir plus</a>
        </footer>
    </section>

</div>

<?php if (!empty($roles)): ?>
<!-- Répartition des utilisateurs par rôle -->
<section class="carte-tb carte-tb-pleine">
    <header class="carte-tb-entete">
        <span class="carte-tb-icone"><?= icone('utilisateurs', 18) ?></span>
        <div class="carte-tb-titres">
            <div class="carte-tb-titre">Répartition par rôle</div>
            <div class="carte-tb-compte"><?= $stats['utilisateurs'] ?> utilisateurs au total</div>
        </div>
    </header>
    <div class="repartition repartition-large">
        <?php foreach ($roles as $r): ?>
            <span class="repartition-chip"><?= htmlspecialchars(libelleRole($r['libelle'])) ?> <b><?= $r['total'] ?></b></span>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Maintenance : vérification de l'envoi des mails -->
<section class="carte-tb carte-tb-pleine mt-2">
    <header class="carte-tb-entete">
        <span class="carte-tb-icone"><?= icone('assistance', 18) ?></span>
        <div class="carte-tb-titres">
            <div class="carte-tb-titre">Maintenance</div>
            <div class="carte-tb-compte">Vérifier l'envoi des notifications par email</div>
        </div>
    </header>
    <div class="carte-tb-corps carte-tb-corps-aere">
        <form method="post" action="/admin/test-mail" class="form-mail-test">
            <input type="email" name="to" class="saisie" placeholder="destinataire@exemple.com"
                   value="<?= htmlspecialchars(getenv('MAIL_TEST_TO') ?: '') ?>" required>
            <button type="submit" class="bouton bouton-secondaire">Envoyer un mail de test</button>
        </form>
    </div>
</section>

<?php require __DIR__ . '/../partials/footer.php'; ?>
