<?php

// Acces commun a tous les roles : le systeme de tickets / assistance
// est ouvert a tout utilisateur connecte (l'admin joue le role de support).
$tickets = ['tickets', 'tickets/*'];

return [
    'admin' => array_merge([
        'admin/*',
        'departement/*',
        'finance/*',
        'directeur/*',
        'postal-univ/*',
        'postal/*',
    ], $tickets),

    'finance' => array_merge([
        'finance/*',
    ], $tickets),

    'directeur' => array_merge([
        'directeur/*',
    ], $tickets),

    'postal_univ' => array_merge([
        'postal-univ/*',
    ], $tickets),

    'postal_iut' => array_merge([
        'postal/*',
    ], $tickets),

    'departement' => array_merge([
        'departement/*',
    ], $tickets),
];
