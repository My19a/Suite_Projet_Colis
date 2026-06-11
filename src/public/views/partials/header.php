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

// [href, libellé, icône]
$menusParRole = [
    'admin' => [
        ['/admin/dashboard', 'Tableau de bord', 'tableau-bord'],
        ['/admin/utilisateurs', 'Utilisateurs', 'utilisateurs'],
        ['/admin/departements', 'Départements', 'departements'],
        ['/admin/fournisseurs', 'Fournisseurs', 'fournisseurs'],
        ['/admin/devis', 'Devis', 'devis'],
        ['/admin/colis', 'Colis', 'colis'],
        ['/tickets', 'Assistance', 'assistance'],
    ],
    'postal_iut' => [
        ['/postal/dashboard', 'Tableau de bord', 'tableau-bord'],
        ['/postal/confirmation', 'Confirmation', 'confirmation'],
        ['/postal/colis/recus', 'Colis reçus', 'reception'],
        ['/postal/colis/remis', 'Colis remis', 'valide'],
        ['/postal/colis/recherche', 'Recherche', 'recherche'],
        ['/postal/colis/non-identifies', 'Non identifiés', 'alerte'],
        ['/tickets', 'Assistance', 'assistance'],
    ],
    'postal_univ' => [
        ['/postal-univ/dashboard', 'Tableau de bord', 'tableau-bord'],
        ['/postal-univ/reception', 'Réception colis', 'reception'],
        ['/postal-univ/colis', 'Liste colis', 'colis'],
        ['/postal-univ/non-identifies', 'Non identifiés', 'alerte'],
        ['/postal-univ/historique', 'Historique', 'historique'],
        ['/tickets', 'Assistance', 'assistance'],
    ],
    'departement' => [
        ['/departement/dashboard', 'Tableau de bord', 'tableau-bord'],
        ['/departement/creer-devis', 'Créer un devis', 'devis-plus'],
        ['/departement/mes-devis', 'Mes devis', 'devis'],
        ['/departement/bons-commande', 'Bons de commande', 'commandes'],
        ['/departement/mes-colis', 'Mes colis', 'colis'],
        ['/departement/budget', 'Budget', 'budget'],
        ['/departement/fournisseurs', 'Fournisseurs', 'fournisseurs'],
        ['/tickets', 'Assistance', 'assistance'],
    ],
    'finance' => [
        ['/finance/dashboard', 'Tableau de bord', 'tableau-bord'],
        ['/finance/devis', 'Devis à vérifier', 'devis'],
        ['/finance/bons-commande', 'Bons de commande', 'commandes'],
        ['/finance/budgets', 'Budgets', 'budget'],
        ['/tickets', 'Assistance', 'assistance'],
    ],
    'directeur' => [
        ['/directeur/dashboard', 'Tableau de bord', 'tableau-bord'],
        ['/directeur/devis', 'Devis à signer', 'signature'],
        ['/directeur/bons-commande', 'Bons de commande', 'commandes'],
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
    <link rel="stylesheet" href="/assets/css/theme.css">
    <?php foreach ($feuillesDeStyle ?? [] as $feuille): ?>
    <link rel="stylesheet" href="<?= htmlspecialchars($feuille) ?>">
    <?php endforeach; ?>
</head>

<body class="app">

<header class="navbar">
    <a class="navbar-marque" href="/">
        <span class="navbar-titre">Suivi Colis</span>
    </a>

    <nav class="navbar-menu">
        <?php foreach ($menu as [$href, $libelle, $icn]): ?>
            <a href="<?= $href ?>"<?= $href === $lienActif ? ' class="actif"' : '' ?>>
                <?= icone($icn, 15) ?><?= $libelle ?><?php if ($href === '/tickets' && $notifs > 0): ?><span class="notif-pastille"><?= $notifs ?></span><?php endif; ?>
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
