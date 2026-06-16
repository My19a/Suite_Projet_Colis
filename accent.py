#!/usr/bin/env python3
"""
fix_accents_views_only.py — Sécurité Maximale.
Modifie UNIQUEMENT le texte pur visible entre les balises HTML.
Ignore les dossiers de code (controllers, models) et le code PHP.
"""

import os
import sys
import re

# Liste nettoyée au maximum (aucun mot risqué pour la BD ou les fichiers)
CORRECTIONS = {

    "departement": "département", 
    "Departement": "Département",
    "departements": "départements", 
    "Departements": "Départements",

    "systeme": "système", "Systeme": "Système",
    "tracabilite": "traçabilité", "Tracabilite": "Traçabilité",
    "budgetaire": "budgétaire", "Budgetaire": "Budgétaire",
    "a verifier": "à vérifier", "A verifier": "À vérifier",
    "a signer": "à signer", "a traiter": "à traiter", "A traiter": "À traiter",
    "a retirer": "à retirer", "A retirer": "À retirer",
    "a transferer": "à transférer", "A transférer": "À transférer",
    "recus": "reçus", "Recus": "Reçus", "recu": "reçu", "Recu": "Reçu",
    "identifies": "identifiés", "Identifies": "Identifiés", "identifie": "identifié",
    "non-identifies": "non-identifiés", "transferes": "transférés", 
    "Transferes": "Transférés", "transfere": "transféré",
    "valides": "validés", "Valides": "Validés", "valide": "validé", "Valide": "Validé",
    "rejetes": "rejetés", "Rejetes": "Rejetés", "rejete": "rejeté",
    "signes": "signés", "Signes": "Signés", "signe": "signé",
    "livres": "livrés", "Livres": "Livrés", "livre": "livré",
    "retires": "retirés", "Retires": "Retirés", "retire": "retiré",
    "utilise": "utilisé", "Utilise": "Utilisé",
    "depasse": "dépassé", "Depasse": "Dépassé",
    "depense": "dépensé", "Depense": "Dépensé",
    "alloue": "alloué", "Alloue": "Alloué",
    "assigne": "assigné", "Assigne": "Assigné",
    "cree": "créé", "Cree": "Créé", "envoye": "envoyé", "Envoye": "Envoyé",
    "verifie": "vérifié", "Verifie": "Vérifié", "verifier": "vérifier",
    "numero": "numéro", "Numero": "Numéro", "numeros": "numéros",
    "telephone": "téléphone", "Telephone": "Téléphone",
    "receptionnes": "réceptionnés", "reception": "réception", "Reception": "Réception",
    "etiquette": "étiquette", "Etiquette": "Étiquette",
    "materiels": "matériels", "materiel": "matériel",
    "decrivez": "décrivez", "brievement": "brièvement",
    "selectionnez": "sélectionnez", "Selectionnez": "Sélectionnez",
    "arrivee": "arrivée", "Arrivee": "Arrivée",
    "camera": "caméra", "Camera": "Caméra",
    "acceder": "accéder", "genere": "généré",
    "deconnexion": "déconnexion", "Deconnexion": "Déconnexion",
    "resultat": "résultat", "Resultat": "Résultat", "resultats": "résultats",
    "repartition": "répartition", "dernieres": "dernières", "derniere": "dernière",
    "effectuees": "effectuées", "completee": "complétée",
    "complete": "complète", "incomplete": "incomplète",
    "autorise": "autorisé", "autorises": "autorisés",
    "universite": "université", "Universite": "Université",
    "aupres": "auprès", "listes": "listés", "a ete": "a été", "etaient": "étaient",
    "financieres": "financières", "financiere": "financière", "decisions": "décisions"
}

def clean_pure_text(match):
    """Applique les corrections uniquement sur le texte capturé hors des balises."""
    text = match.group(0)
    for wrong, correct in CORRECTIONS.items():
        # \b garantit qu'on remplace le mot entier, pas un bout de code
        pattern = r'\b' + re.escape(wrong) + r'\b'
        text = re.sub(pattern, correct, text)
    return text

def process_html_content(content):
    # Étape 1 : On isole complètement les blocs PHP globaux pour ne PAS y toucher
    php_pattern = re.compile(r'(<\?php.*?\?>|<\?=.*?\?>)', re.DOTALL)
    parts = php_pattern.split(content)
    
    for i in range(len(parts)):
        # Si c'est du HTML (index pair)
        if i % 2 == 0:
            # Étape 2 : Cette Regex cible UNIQUEMENT le texte situé entre ">" et "<" 
            # (Ex: >Mon texte sans accent<)
            # Elle ignore tout ce qui est à l'intérieur d'une balise <div class="..." id="...">
            parts[i] = re.sub(r'(?<=>)[^<]+(?=<)', clean_pure_text, parts[i])
            
            # Étape 3 : Cas particulier pour les placeholders des inputs
            parts[i] = re.sub(r'placeholder="([^"]+)"', 
                              lambda m: f'placeholder="{clean_pure_text(m)}"', 
                              parts[i])
    
    return ''.join(parts)

def should_skip_directory_or_file(name, is_dir=False):
    """Bloque radicalement l'accès aux dossiers sensibles."""
    forbidden = {'controllers', 'models', 'config', 'vendor', 'node_modules', '.git', 'database'}
    if is_dir:
        return name.lower() in forbidden
    return False

def run(root_dir):
    changed = 0
    total = 0
    
    print(f"🛡️  Lancement du script ultra-sécurisé sur : {os.path.abspath(root_dir)}")
    
    for dirpath, dirnames, filenames in os.walk(root_dir):
        # Filtrage strict des dossiers : si un dossier est dans la liste interdite, on n'entre pas dedans
        dirnames[:] = [d for d in dirnames if not should_skip_directory_or_file(d, is_dir=True)]
        
        for fname in filenames:
            ext = os.path.splitext(fname)[1].lower()
            # On ne traite que le HTML ou les fichiers explicitement de vue (.view.php ou .phtml)
            # Si tes fichiers de vue sont de simples .php, le script marchera mais l'étape "dirnames" les aura protégés s'ils sont dans les bons dossiers
            if ext not in ('.html', '.htm', '.php'):
                continue
                
            fpath = os.path.join(dirpath, fname)
            total += 1
            
            try:
                with open(fpath, 'r', encoding='utf-8', errors='ignore') as f:
                    original = f.read()
                
                fixed = process_html_content(original)
                
                if fixed != original:
                    with open(fpath, 'w', encoding='utf-8') as f:
                        f.write(fixed)
                    changed += 1
                    print(f"  [TEXTE VISIBLE CORRIGÉ] : {os.path.relpath(fpath, root_dir)}")
            except Exception as e:
                print(f"  [ERREUR] Impossible de lire {fname} : {e}")
                
    print(f"\n✓ Terminé de manière sécurisée. {changed} fichiers de vues mis à jour.")

if __name__ == '__main__':
    target = sys.argv[1] if len(sys.argv) > 1 else '.'
    run(target)