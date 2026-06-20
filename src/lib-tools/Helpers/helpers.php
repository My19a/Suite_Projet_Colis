<?php

/**
 * Fonctions helper globales
 */

function url(string $path): string
{
    $config = require __DIR__ . '/../../config/app.php';
    $base = rtrim($config['base_url'], '/');
    return $base . '/' . ltrim($path, '/');
}

function redirect(string $path): never
{
    header('Location: ' . url($path));
    exit;
}

function view(string $viewName, array $data = []): void
{
    extract($data);
    $viewPath = __DIR__ . "/../../public/views/{$viewName}.php";

    if (!file_exists($viewPath)) {
        throw new \Exception("View not found: {$viewName}");
    }

    require $viewPath;
}

/**
 * Lien vers un asset public, suffixé de la version (mtime) du fichier
 * pour forcer le rechargement par le navigateur quand il change.
 */
function asset(string $chemin): string
{
    $abs = __DIR__ . '/../../public' . $chemin;
    return is_file($abs) ? $chemin . '?v=' . filemtime($abs) : $chemin;
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function currentUser(): ?User
{
    return $_SESSION['user'] ?? null;
}

function isAuthenticated(): bool
{
    return isset($_SESSION['user']) && $_SESSION['user'] instanceof User;
}

function hasRole(string $role): bool
{
    $user = currentUser();
    return $user !== null && $user->hasRole($role);
}

function config(string $key = null): mixed
{
    static $config = null;
    if ($config === null) {
        $config = require __DIR__ . '/../../config/app.php';
    }

    if ($key === null) return $config;

    $keys = explode('.', $key);
    $value = $config;
    foreach ($keys as $k) {
        if (!isset($value[$k])) return null;
        $value = $value[$k];
    }
    return $value;
}

function ticketNotifsCount(): int
{
    if (!isset($_SESSION['user']) || !($_SESSION['user'] instanceof User)) return 0;
    try {
        require_once __DIR__ . '/../../public/models/Model.php';
        $db = Model::getModel()->bd;
        $req = $db->prepare("SELECT COUNT(*) FROM notification WHERE id_utilisateur = ? AND lu = 0");
        $req->execute([$_SESSION['user']->getId()]);
        return (int) $req->fetchColumn();
    } catch (\Throwable $e) { return 0; }
}

/**
 * Met en forme un libellé stocké en base (statut, rôle…) pour l'affichage.
 * Ex : "en_attente" -> "En Attente", "transfere_iut" -> "Transfere IUT".
 */
function joli(?string $texte): string
{
    if ($texte === null || trim($texte) === '') return '—';
    $t = ucwords(str_replace(['_', '-'], ' ', strtolower(trim($texte))));
    return strtr($t, ['Iut' => 'IUT', 'Bc' => 'BC', 'Cas' => 'CAS', 'Uid' => 'UID']);
}

/**
 * Libellé lisible d'un rôle. Retombe sur joli() pour un rôle inconnu.
 */
function libelleRole(?string $role): string
{
    $map = [
        'admin'             => 'Administrateur BD',
        'responsable_colis' => 'Responsable colis',
        'demandeur'         => 'Demandeur',
        'editeur_bc'        => 'Éditeur de bons de commande',
    ];
    return $map[$role] ?? joli($role);
}

/**
 * Libellé lisible et accentué d'un statut (devis, bon de commande, colis).
 * Casse de phrase française (accents corrects), repli sur joli() si inconnu.
 */
function libelleStatut(?string $statut): string
{
    $s = strtolower(trim($statut ?? ''));
    $map = [
        'en_attente'      => 'En attente',
        'en_cours'        => 'En cours',
        'en_preparation'  => 'En préparation',
        'ouvert'          => 'Ouvert',
        'resolu'          => 'Résolu',
        'ferme'           => 'Fermé',
        'clos'            => 'Clos',
        'brouillon'       => 'Brouillon',
        'envoye'          => 'Envoyé',
        'sent'            => 'Envoyé',
        'accepte'         => 'Accepté',
        'accepted'        => 'Accepté',
        'refuse'          => 'Refusé',
        'rejected'        => 'Refusé',
        'rejete'          => 'Rejeté',
        'rejete_finance'  => 'Rejeté (Finance)',
        'valide'          => 'Validé',
        'validated'       => 'Validé',
        'valide_finance'  => 'Validé (Finance)',
        'signe'           => 'Signé',
        'signe_directeur' => 'Signé (Directeur)',
        'signed'          => 'Signé',
        'annule'          => 'Annulé',
        'annulee'         => 'Annulée',
        'cancelled'       => 'Annulé',
        'livre'           => 'Réceptionné',
        'livree'          => 'Livrée',
        'delivered'       => 'Livré',
        'retire'          => 'Retiré',
        'retrieved'       => 'Retiré',
        'recu_universite' => "Livré à l'université",
        'received'        => 'Reçu',
        'transfere_iut'   => "Transféré à l'IUT",
        'transferred'     => 'Transféré',
        'non_identifie'   => 'Non identifié',
    ];
    return $map[$s] ?? joli($statut);
}

/**
 * Libellé lisible et accentué d'une priorité (tickets).
 */
function libellePriorite(?string $priorite): string
{
    $s = strtolower(trim($priorite ?? ''));
    $map = [
        'basse'    => 'Basse',
        'normale'  => 'Normale',
        'moyenne'  => 'Moyenne',
        'haute'    => 'Haute',
        'elevee'   => 'Élevée',
        'urgente'  => 'Urgente',
        'critique' => 'Critique',
    ];
    return $map[$s] ?? joli($priorite);
}

/**
 * Classe CSS de badge correspondant à un statut (slug normalisé).
 */
function badgeStatut(?string $statut): string
{
    return 'badge badge-' . strtolower(str_replace(' ', '_', trim($statut ?? '')));
}

/**
 * État du budget selon le reste disponible rapporté au budget total.
 * Renvoie une classe : budget-large (vert), budget-moyen (orange),
 * budget-faible (rouge), budget-neutre (pas de total défini).
 */
function classeBudget($restant, $total): string
{
    $total = (float) $total;
    if ($total <= 0) {
        return 'budget-neutre';
    }
    $ratio = (float) $restant / $total;
    if ($ratio < 0.20) {
        return 'budget-faible';
    }
    if ($ratio < 0.50) {
        return 'budget-moyen';
    }
    return 'budget-large';
}

/**
 * État vide soigné (icône + titre + sous-texte + bouton d'action optionnel).
 * Usage : <?= etatVide('colis', 'Aucun colis', 'Les colis reçus apparaîtront ici.', '/postal/colis/ajouter', 'Ajouter un colis') ?>
 */
function etatVide(string $icone, string $titre, string $sous = '', ?string $ctaHref = null, ?string $ctaLibelle = null): string
{
    $html  = '<div class="etat-vide">';
    $html .= '<div class="etat-vide-icone">' . icone($icone, 22) . '</div>';
    $html .= '<div class="etat-vide-titre">' . htmlspecialchars($titre) . '</div>';
    if ($sous !== '') {
        $html .= '<div class="etat-vide-sous">' . htmlspecialchars($sous) . '</div>';
    }
    if ($ctaHref !== null && $ctaLibelle !== null) {
        $html .= '<a href="' . htmlspecialchars($ctaHref) . '" class="bouton bouton-principal">'
               . icone('plus', 14) . htmlspecialchars($ctaLibelle) . '</a>';
    }
    return $html . '</div>';
}

/**
 * Icône SVG inline (style trait, hérite de la couleur du texte via currentColor).
 * Usage : <?= icone('utilisateurs') ?> ou <?= icone('plus', 14) ?>
 */
function icone(string $nom, int $taille = 16): string
{
    static $traits = [
        'tableau-bord'  => '<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>',
        'utilisateurs'  => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
        'utilisateur'   => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
        'departements'  => '<polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/>',
        'fournisseurs'  => '<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>',
        'devis'         => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>',
        'devis-plus'    => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/>',
        'signature'     => '<path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>',
        'colis'         => '<line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>',
        'assistance'    => '<circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/>',
        'plus'          => '<line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>',
        'recherche'     => '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>',
        'croix'         => '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>',
        'valide'        => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>',
        'confirmation'  => '<polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>',
        'reception'     => '<polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>',
        'historique'    => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
        'alerte'        => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>',
        'info'          => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>',
        'commandes'     => '<path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>',
        'budget'        => '<path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/>',
        'liste'         => '<line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>',
        'batiment'      => '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>',
        'menu'          => '<line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>',
        'console'       => '<polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/>',
        'base-donnees'  => '<ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>',
    ];

    if (!isset($traits[$nom])) return '';

    return '<svg class="icone" width="' . $taille . '" height="' . $taille . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $traits[$nom] . '</svg>';
}
