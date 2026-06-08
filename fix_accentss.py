#!/usr/bin/env python3
"""
fix_accents.py — Corrige les accents manquants dans les textes HTML visibles.
Usage :
  python3 fix_accents.py src/          → corrige tous les fichiers
  python3 fix_accents.py src/ --debug  → montre ce qui sera changé sans toucher les fichiers
"""

import os, sys, re

SKIP_DIRS = {'vendor', 'node_modules', '.git', 'lib-tools'}

CORRECTIONS = [
    (r'\bsysteme\b',        'système'),
    (r'\bSysteme\b',        'Système'),
    (r'\bdepartements\b',   'départements'),
    (r'\bDepartements\b',   'Départements'),
    (r'\bdepartement\b',    'département'),
    (r'\bDepartement\b',    'Département'),
    (r'\btracabilite\b',    'traçabilité'),
    (r'\bTracabilite\b',    'Traçabilité'),
    (r'\bbudgetaire\b',     'budgétaire'),
    (r'\bBudgetaire\b',     'Budgétaire'),
    (r'\bverifier\b',       'vérifier'),
    (r'\bVerifier\b',       'Vérifier'),
    (r'\bcreer\b',          'créer'),
    (r'\bCreer\b',          'Créer'),
    (r'\bnumeros\b',        'numéros'),
    (r'\bNumeros\b',        'Numéros'),
    (r'\bnumero\b',         'numéro'),
    (r'\bNumero\b',         'Numéro'),
    (r'\btelephone\b',      'téléphone'),
    (r'\bTelephone\b',      'Téléphone'),
    (r'\broles\b',          'rôles'),
    (r'\bRoles\b',          'Rôles'),
    (r'\brole\b',           'rôle'),
    (r'\bRole\b',           'Rôle'),
    (r'\bdeconnexion\b',    'déconnexion'),
    (r'\bDeconnexion\b',    'Déconnexion'),
    (r'\bresultats\b',      'résultats'),
    (r'\bResultats\b',      'Résultats'),
    (r'\bresultat\b',       'résultat'),
    (r'\bResultat\b',       'Résultat'),
    (r'\brepartition\b',    'répartition'),
    (r'\bRepartition\b',    'Répartition'),
    (r'\breception\b',      'réception'),
    (r'\bReception\b',      'Réception'),
    (r'\betiquette\b',      'étiquette'),
    (r'\bEtiquette\b',      'Étiquette'),
    (r'\bmateriel\b',       'matériel'),
    (r'\bMateriel\b',       'Matériel'),
    (r'\bcamera\b',         'caméra'),
    (r'\bCamera\b',         'Caméra'),
    (r'\buniversite\b',     'université'),
    (r'\bUniversite\b',     'Université'),
    (r'\bdecisions\b',      'décisions'),
    (r'\bDecisions\b',      'Décisions'),
    (r'\bfinancieres\b',    'financières'),
    (r'\bFinancieres\b',    'Financières'),
    (r'\bfinanciere\b',     'financière'),
    (r'\bFinanciere\b',     'Financière'),
    (r'\barrivee\b',        'arrivée'),
    (r'\bArrivee\b',        'Arrivée'),
    (r'\baupres\b',         'auprès'),
    (r'\bpreparation\b',    'préparation'),
    (r'\bPreparation\b',    'Préparation'),
    (r'\bacceder\b',        'accéder'),
    (r'\bAcceder\b',        'Accéder'),
    (r'\bselectionnez\b',   'sélectionnez'),
    (r'\bSelectionnez\b',   'Sélectionnez'),
    (r'\bdecrivez\b',       'décrivez'),
    (r'\bDecrivez\b',       'Décrivez'),
    (r'\bbrievement\b',     'brièvement'),
    (r'\bdernieres\b',      'dernières'),
    (r'\bDernieres\b',      'Dernières'),
    (r'\bderniere\b',       'dernière'),
    (r'\bDerniere\b',       'Dernière'),
    (r'\beffectuees\b',     'effectuées'),
    (r'\bEffectuees\b',     'Effectuées'),
    (r'\bcomplete\b',       'complète'),
    (r'\bComplete\b',       'Complète'),
    (r'\bincomplete\b',     'incomplète'),
    (r'\bIncomplete\b',     'Incomplète'),
    (r'\bautorises\b',      'autorisés'),
    (r'\bAutorises\b',      'Autorisés'),
    (r'\bautorise\b',       'autorisé'),
    (r'\bAutorise\b',       'Autorisé'),
    # participes passés
    (r'\brecus\b',          'reçus'),
    (r'\bRecus\b',          'Reçus'),
    (r'\brecu\b',           'reçu'),
    (r'\bRecu\b',           'Reçu'),
    (r'\bidentifies\b',     'identifiés'),
    (r'\bIdentifies\b',     'Identifiés'),
    (r'\bidentifie\b',      'identifié'),
    (r'\bIdentifie\b',      'Identifié'),
    (r'\btransferes\b',     'transférés'),
    (r'\bTransferes\b',     'Transférés'),
    (r'\btransfere\b',      'transféré'),
    (r'\bTransfere\b',      'Transféré'),
    (r'\bvalides\b',        'validés'),
    (r'\bValides\b',        'Validés'),
    (r'\bvalide\b',         'validé'),
    (r'\brejetes\b',        'rejetés'),
    (r'\bRejetes\b',        'Rejetés'),
    (r'\brejete\b',         'rejeté'),
    (r'\bRejete\b',         'Rejeté'),
    (r'\bsignes\b',         'signés'),
    (r'\bSignes\b',         'Signés'),
    (r'\bsigne\b',          'signé'),
    (r'\bSigne\b',          'Signé'),
    (r'\blivres\b',         'livrés'),
    (r'\bLivres\b',         'Livrés'),
    (r'\blivre\b',          'livré'),
    (r'\bLivre\b',          'Livré'),
    (r'\bretires\b',        'retirés'),
    (r'\bRetires\b',        'Retirés'),
    (r'\bretire\b',         'retiré'),
    (r'\bRetire\b',         'Retiré'),
    (r'\butilise\b',        'utilisé'),
    (r'\bUtilise\b',        'Utilisé'),
    (r'\bdepasse\b',        'dépassé'),
    (r'\bDepasse\b',        'Dépassé'),
    (r'\bdepense\b',        'dépensé'),
    (r'\bDepense\b',        'Dépensé'),
    (r'\balloue\b',         'alloué'),
    (r'\bAlloue\b',         'Alloué'),
    (r'\bassigne\b',        'assigné'),
    (r'\bAssigne\b',        'Assigné'),
    (r'\bcree\b',           'créé'),
    (r'\bCree\b',           'Créé'),
    (r'\benvoye\b',         'envoyé'),
    (r'\bEnvoye\b',         'Envoyé'),
    (r'\bmarque\b',         'marqué'),
    (r'\bMarque\b',         'Marqué'),
    (r'\breceptionnes\b',   'réceptionnés'),
    (r'\bReceptionnes\b',   'Réceptionnés'),
    (r'\blistes\b',         'listés'),
    # expressions avec "à"
    (r'\ba verifier\b',     'à vérifier'),
    (r'\bA verifier\b',     'À vérifier'),
    (r'\ba signer\b',       'à signer'),
    (r'\ba traiter\b',      'à traiter'),
    (r'\bA traiter\b',      'À traiter'),
    (r'\ba retirer\b',      'à retirer'),
    (r'\bA retirer\b',      'À retirer'),
    (r'\ba transferer\b',   'à transférer'),
    (r'\bA transferer\b',   'À transférer'),
]

COMPILED = [(re.compile(pat), repl) for pat, repl in CORRECTIONS]


def fix_text(text):
    """Applique toutes les corrections sur un bloc de texte pur."""
    for pattern, repl in COMPILED:
        text = pattern.sub(repl, text)
    return text


def fix_line(line):
    """
    Corrige une ligne HTML en ne touchant QUE :
      - le texte entre balises  >texte<
      - les valeurs des attributs title=, placeholder=, alt=, aria-label=
    Ignore tout le reste (href, PHP, CSS, JS, variables...).
    """
    result = line

    # 1. Texte visible entre balises
    result = re.sub(
        r'>([^<>]+)<',
        lambda m: '>' + fix_text(m.group(1)) + '<',
        result
    )

    # 2. Attributs textuels
    for attr in ('title', 'placeholder', 'alt', 'aria-label', 'aria-describedby'):
        result = re.sub(
            rf'({attr}=")([^"]*?)(")',
            lambda m: m.group(1) + fix_text(m.group(2)) + m.group(3),
            result
        )

    return result


def fix_file(filepath, debug=False):
    with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
        lines = f.readlines()

    new_lines = [fix_line(l) for l in lines]

    changed_lines = [(i+1, lines[i].rstrip(), new_lines[i].rstrip())
                     for i in range(len(lines)) if lines[i] != new_lines[i]]

    if not changed_lines:
        return False

    if debug:
        print(f"\n  [{filepath}]")
        for lineno, old, new in changed_lines[:5]:  # max 5 exemples par fichier
            print(f"    L{lineno}: {old.strip()[:60]}")
            print(f"         → {new.strip()[:60]}")
    else:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.writelines(new_lines)

    return True


def run(root_dir, debug=False):
    extensions = {'.php', '.html', '.htm'}
    changed, total = 0, 0

    for dirpath, dirnames, filenames in os.walk(root_dir):
        dirnames[:] = [d for d in dirnames if d not in SKIP_DIRS]
        for fname in filenames:
            if os.path.splitext(fname)[1].lower() not in extensions:
                continue
            fpath = os.path.join(dirpath, fname)
            total += 1
            if fix_file(fpath, debug=debug):
                changed += 1
                if not debug:
                    print(f"  modifié : {os.path.relpath(fpath, root_dir)}")

    mode = "[DEBUG — aucun fichier modifié]" if debug else ""
    print(f"\n✓ {changed}/{total} fichiers {'à modifier' if debug else 'modifiés'}. {mode}")


if __name__ == '__main__':
    args = [a for a in sys.argv[1:] if not a.startswith('--')]
    debug = '--debug' in sys.argv

    target = args[0] if args else '.'
    if not os.path.isdir(target):
        print(f"Erreur : '{target}' n'est pas un dossier.")
        sys.exit(1)

    print(f"{'[DEBUG] ' if debug else ''}Correction des accents dans : {os.path.abspath(target)}\n")
    run(target, debug=debug)
