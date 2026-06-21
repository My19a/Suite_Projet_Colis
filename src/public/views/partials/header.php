<?php
/**
 * En-tête commun à toutes les vues : <head> + navbar horizontale.
 *
 * Variables attendues (à définir avant le require) :
 *   $titre (string)            : titre de l'onglet navigateur
 *   $actif (string|null)       : href de l'onglet actif du menu (auto-détecté si absent)
 *   $feuillesDeStyle (array)   : feuilles de style additionnelles (optionnel)
 */

$utilisateurConnecte = $_SESSION['user'] ?? null;
$role = $utilisateurConnecte ? $utilisateurConnecte->getRole() : '';

// [href, libellé, icône]
$menusParRole = [
    'admin' => [
        ['/admin/dashboard', 'Tableau de bord', 'tableau-bord'],
        ['/admin/utilisateurs', 'Utilisateurs', 'utilisateurs'],
        ['/presence', 'Connectés', 'utilisateurs'],
        ['/admin/departements', 'Départements', 'departements'],
        ['/admin/fournisseurs', 'Fournisseurs', 'fournisseurs'],
        ['/admin/devis', 'Devis', 'devis'],
        ['/admin/colis', 'Colis', 'colis'],
        ['/admin/console', 'Console SQL', 'console'],
        ['/tickets', 'Assistance', 'assistance'],
    ],
    // Responsable colis : réception université puis transfert IUT des colis liés aux bons de commande
    'responsable_colis' => [
        ['/postal/dashboard', 'Tableau de bord', 'tableau-bord'],
        ['/postal/commandes', 'Commandes en attente', 'reception'],
        ['/postal/reception', "Réception d'un colis", 'colis'],
        ['/postal/colis', 'Colis à transférer', 'colis'],
        ['/postal/historique', 'Historique', 'historique'],
        ['/tickets', 'Assistance', 'assistance'],
    ],
    // Demandeur = ancien Département
    'demandeur' => [
        ['/departement/dashboard', 'Tableau de bord', 'tableau-bord'],
        ['/departement/creer-devis', 'Créer un devis', 'devis-plus'],
        ['/departement/mes-devis', 'Mes devis', 'devis'],
        ['/departement/bons-commande', 'Bons de commande', 'commandes'],
        ['/departement/mes-colis', 'Mes colis', 'colis'],
        ['/departement/budget', 'Budget', 'budget'],
        ['/departement/fournisseurs', 'Fournisseurs', 'fournisseurs'],
        ['/tickets', 'Assistance', 'assistance'],
    ],
    // Éditeur de bons de commande : vérification puis signature des devis
    'editeur_bc' => [
        ['/finance/dashboard', 'Tableau de bord', 'tableau-bord'],
        ['/finance/devis', 'Devis à vérifier', 'devis'],
        ['/directeur/devis', 'Devis à signer', 'signature'],
        ['/finance/bons-commande', 'Bons de commande', 'commandes'],
        ['/finance/budgets', 'Budgets', 'budget'],
        ['/finance/historique', 'Historique devis', 'historique'],
        ['/tickets', 'Assistance', 'assistance'],
    ],
];

$menu = $menusParRole[$role] ?? [];
$uriCourante = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$lienActif = $actif ?? $uriCourante;
$notifs = function_exists('ticketNotifsCount') ? ticketNotifsCount() : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titre ?? 'Suivi Colis') ?></title>
    <link rel="stylesheet" href="<?= asset('/assets/css/theme.css') ?>">
    <?php foreach ($feuillesDeStyle ?? [] as $feuille): ?>
    <link rel="stylesheet" href="<?= htmlspecialchars(asset($feuille)) ?>">
    <?php endforeach; ?>
</head>

<body class="app">

<a class="lien-evitement" href="#contenu-principal">Aller au contenu principal</a>

<header class="navbar" id="navbar" role="banner">
    <a class="navbar-marque" href="/">
        <img class="navbar-logo" src="/assets/img/logo-colis.png" alt="">
        <span class="navbar-titre">Suivi Colis</span>
    </a>

    <div class="navbar-utilisateur">
        <div class="utilisateur-infos">
            <span class="utilisateur-nom"><?= $utilisateurConnecte ? htmlspecialchars($utilisateurConnecte->getFullName()) : '' ?></span>
            <span class="utilisateur-role"><?= htmlspecialchars(libelleRole($role)) ?></span>
        </div>
        <a class="btn-deconnexion" href="/logout" title="Déconnexion">Déconnexion</a>
    </div>

    <button class="navbar-burger" type="button" aria-label="Menu" onclick="document.getElementById('navbar').classList.toggle('ouvert')">
        <?= icone('menu', 20) ?>
    </button>

    <nav class="navbar-menu" aria-label="Navigation principale">
        <?php foreach ($menu as [$href, $libelle, $icn]): ?>
            <a href="<?= $href ?>"<?= $href === $lienActif ? ' class="actif"' : '' ?>>
                <?= icone($icn, 15) ?><?= $libelle ?><?php if ($href === '/tickets' && $notifs > 0): ?><span class="notif-pastille"><?= $notifs ?></span><?php endif; ?>
            </a>
        <?php endforeach; ?>
    </nav>
</header>

<main class="contenu" id="contenu-principal" role="main" tabindex="-1">
