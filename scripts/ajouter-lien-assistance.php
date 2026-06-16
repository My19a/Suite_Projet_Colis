<?php
/**
 * Ajoute le lien "Assistance" (vers /tickets) en dernier element de chaque
 * menu lateral (<nav class="menu">) des vues du projet.
 *
 * Le projet ne partage pas de sidebar : chaque vue recopie son menu. Ce script
 * insere le lien partout d'un coup, de maniere idempotente (ne double pas si
 * deja present).
 *
 * Usage : php scripts/ajouter-lien-assistance.php
 *
 */

$racineVues = __DIR__ . '/../src/public/views';
$lien = '        <a href="/tickets">Assistance</a>' . "\n";

// On insere le lien juste avant le </nav> qui precede le bloc deconnexion.
$motif = '#([ \t]*</nav>)(\s*<div class="deconnexion">)#';

$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($racineVues));
$modifies = 0;
$ignores = 0;

foreach ($rii as $fichier) {
    if ($fichier->getExtension() !== 'php') continue;

    // On ne touche pas aux vues du module tickets (menu deja adapte).
    if (str_contains(str_replace('\\', '/', $fichier->getPathname()), '/views/tickets/')) continue;

    $contenu = file_get_contents($fichier->getPathname());

    if (str_contains($contenu, 'href="/tickets"')) {
        $ignores++;
        continue; // deja present
    }

    $nouveau = preg_replace($motif, $lien . '$1$2', $contenu, 1, $count);

    if ($count > 0 && $nouveau !== null && $nouveau !== $contenu) {
        file_put_contents($fichier->getPathname(), $nouveau);
        $modifies++;
        echo "  + " . $fichier->getFilename() . "\n";
    }
}

echo "\nTermine : $modifies fichier(s) modifie(s), $ignores deja a jour.\n";
