<?php
/**
 * En-tête commun à toutes les vues : <head> + navbar horizontale.
 *
 * Variables attendues (à définir avant le require) :
 *   $titre (string)            : titre de l'onglet navigateur
 *   $actif (string|null)       : href de l'onglet actif du menu (auto-détecté si absent)
 *   $feuillesDeStyle (array)   : feuilles de style additionnelles (optionnel)
 */

$utilisateur = $_SESSION['user'] ?? null;
$role = $utilisateur ? $utilisateur->getRole() : '';

$libellesRoles = [
    'admin'       => 'Administrateur',
    'postal_iut'  => 'Postal IUT',
    'postal_univ' => 'Postal Université',
    'departement' => 'Département',
    'finance'     => 'Service Financier',
    'directeur'   => 'Directeur IUT',
];

$menusParRole = [
    'admin' => [
        ['/admin/dashboard', 'Tableau de bord'],
        ['/admin/utilisateurs', 'Utilisateurs'],
        ['/admin/departements', 'Départements'],
        ['/admin/fournisseurs', 'Fournisseurs'],
        ['/admin/devis', 'Devis'],
        ['/admin/colis', 'Colis'],
        ['/tickets', 'Assistance'],
    ],
    'postal_iut' => [
        ['/postal/dashboard', 'Tableau de bord'],
        ['/postal/confirmation', 'Confirmation'],
        ['/postal/colis/recus', 'Colis reçus'],
        ['/postal/colis/remis', 'Colis remis'],
        ['/postal/colis/recherche', 'Recherche'],
        ['/postal/colis/non-identifies', 'Non identifiés'],
        ['/tickets', 'Assistance'],
    ],
    'postal_univ' => [
        ['/postal-univ/dashboard', 'Tableau de bord'],
        ['/postal-univ/reception', 'Réception colis'],
        ['/postal-univ/colis', 'Liste colis'],
        ['/postal-univ/non-identifies', 'Non identifiés'],
        ['/postal-univ/historique', 'Historique'],
        ['/tickets', 'Assistance'],
    ],
    'departement' => [
        ['/departement/dashboard', 'Tableau de bord'],
        ['/departement/creer-devis', 'Créer un devis'],
        ['/departement/mes-devis', 'Mes devis'],
        ['/departement/bons-commande', 'Bons de commande'],
        ['/departement/mes-colis', 'Mes colis'],
        ['/departement/budget', 'Budget'],
        ['/departement/fournisseurs', 'Fournisseurs'],
        ['/tickets', 'Assistance'],
    ],
    'finance' => [
        ['/finance/dashboard', 'Tableau de bord'],
        ['/finance/devis', 'Devis à vérifier'],
        ['/finance/bons-commande', 'Bons de commande'],
        ['/finance/budgets', 'Budgets'],
        ['/tickets', 'Assistance'],
    ],
    'directeur' => [
        ['/directeur/dashboard', 'Tableau de bord'],
        ['/directeur/devis', 'Devis à signer'],
        ['/directeur/bons-commande', 'Bons de commande'],
        ['/tickets', 'Assistance'],
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
    <link rel="stylesheet" href="/assets/css/theme.css">
    <?php foreach ($feuillesDeStyle ?? [] as $feuille): ?>
    <link rel="stylesheet" href="<?= htmlspecialchars($feuille) ?>">
    <?php endforeach; ?>
</head>

<body class="app">

<header class="navbar">
    <a class="navbar-marque" href="/">
        <img src="/assets/img/logo-iutv.png" class="navbar-logo" alt="Logo IUT">
        <span class="navbar-titre">Suivi Colis</span>
    </a>

    <nav class="navbar-menu">
        <?php foreach ($menu as [$href, $libelle]): ?>
            <a href="<?= $href ?>"<?= $href === $lienActif ? ' class="actif"' : '' ?>>
                <?= $libelle ?><?php if ($href === '/tickets' && $notifs > 0): ?><span class="notif-pastille"><?= $notifs ?></span><?php endif; ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="navbar-utilisateur">
        <div class="utilisateur-infos">
            <span class="utilisateur-nom"><?= $utilisateur ? htmlspecialchars($utilisateur->getFullName()) : '' ?></span>
            <span class="utilisateur-role"><?= htmlspecialchars($libellesRoles[$role] ?? $role) ?></span>
        </div>
        <a class="btn-deconnexion" href="/logout" title="Déconnexion">Déconnexion</a>
    </div>
</header>

<main class="contenu">
