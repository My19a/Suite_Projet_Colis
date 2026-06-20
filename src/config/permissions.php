<?php

// Acces commun a tous les roles : le systeme de tickets / assistance
// est ouvert a tout utilisateur connecte (l'admin joue le role de support).
$tickets = ['tickets', 'tickets/*'];

// Modele a 4 roles (consigne SAE) :
//   admin             -> Administrateur BD
//   responsable_colis -> réception université et transfert IUT des colis liés aux bons de commande
//   demandeur          -> ancien "departement"
//   editeur_bc         -> fusion finance + directeur (edition/validation des bons de commande)
return [
    'admin' => array_merge([
        'admin/*',
        'departement/*',
        'finance/*',
        'directeur/*',
        'postal-univ/*',
        'postal/*',
        'presence',
    ], $tickets),

    'responsable_colis' => array_merge([
        'postal',
        'postal/dashboard',
        'postal/reception',
        'postal/commande',
        'postal/commande/receptionner',
        'postal/colis',
        'postal/transferer',
        'postal/historique',
        'postal/rechercher-destinataire',
    ], $tickets),

    'demandeur' => array_merge([
        'departement/*',
    ], $tickets),

    'editeur_bc' => array_merge([
        'finance/*',
        'directeur/*',
    ], $tickets),
];
