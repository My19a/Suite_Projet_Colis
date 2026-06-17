<?php
$titre = 'Console SQL – Administrateur';
$actif = '/admin/console';
$feuillesDeStyle = ['/assets/css/console.css'];
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
    <div class="page-header-info">
        <h1 class="page-title">Console SQL</h1>
        <p class="page-subtitle">Accès direct à la base de données — exécution de requêtes SQL brutes</p>
    </div>
</div>

<div class="message message-attn console-avert">
    <span class="message-icone"><?= icone('alerte', 16) ?></span>
    <div class="message-corps">
        <strong>Zone sensible.</strong> Les requêtes sont exécutées telles quelles, sans aucun filtre,
        directement sur la base. Une commande <code>UPDATE</code>, <code>DELETE</code> ou
        <code>DROP</code> est irréversible — à utiliser en connaissance de cause.
    </div>
</div>

<div class="console-grille">

    <!-- Liste des tables -->
    <aside class="tables-panel">
        <div class="tables-panel-entete">
            <span class="icone-pastille"><?= icone('base-donnees', 17) ?></span>
            <div>
                <div class="titre">Tables</div>
                <div class="compte"><?= count($tables) ?> table<?= count($tables) > 1 ? 's' : '' ?></div>
            </div>
        </div>
        <div class="tables-liste">
            <?php if (empty($tables)): ?>
                <div style="padding:16px; color:var(--texte-leger); font-size:12.5px;">Aucune table.</div>
            <?php else: ?>
                <?php foreach ($tables as $t): ?>
                    <button type="button" class="table-item"
                            data-table="<?= htmlspecialchars($t['nom'], ENT_QUOTES) ?>"
                            title="Cliquer pour générer un SELECT">
                        <span class="nom"><?= htmlspecialchars($t['nom']) ?></span>
                        <span class="nb"><?= $t['lignes'] === null ? '—' : $t['lignes'] ?></span>
                    </button>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </aside>

    <!-- Éditeur + résultats -->
    <div class="console-principale">

        <div class="editeur">
            <div class="editeur-barre">
                <span class="titre"><?= icone('console', 14) ?> Requête SQL</span>
            </div>
            <textarea id="sql-input" class="editeur-zone" spellcheck="false"
                      placeholder="SELECT * FROM utilisateur LIMIT 100;"></textarea>
            <div class="editeur-actions">
                <div class="gauche">
                    <button type="button" id="btn-executer" class="bouton bouton-principal"><?= icone('console', 14) ?> Exécuter</button>
                    <button type="button" id="btn-effacer" class="bouton bouton-secondaire">Effacer</button>
                </div>
                <span class="editeur-raccourci"><kbd>Ctrl</kbd> + <kbd>Entrée</kbd> pour exécuter</span>
            </div>
        </div>

        <div class="resultats" id="resultats" style="display:none;">
            <div class="resultats-statut" id="resultats-statut"></div>
            <div class="resultats-corps" id="resultats-corps"></div>
        </div>

    </div>
</div>

<script>
(function () {
    const input    = document.getElementById('sql-input');
    const btnExec  = document.getElementById('btn-executer');
    const btnClear = document.getElementById('btn-effacer');
    const carte    = document.getElementById('resultats');
    const statutEl = document.getElementById('resultats-statut');
    const corpsEl  = document.getElementById('resultats-corps');
    const libelleExec = btnExec.innerHTML;

    // Cliquer sur une table -> insère un SELECT
    document.querySelectorAll('.table-item').forEach(function (btn) {
        btn.addEventListener('click', function () {
            input.value = 'SELECT * FROM `' + this.dataset.table + '` LIMIT 100;';
            input.focus();
        });
    });

    btnClear.addEventListener('click', function () {
        input.value = '';
        carte.style.display = 'none';
        input.focus();
    });

    // Ctrl/Cmd + Entrée pour exécuter
    input.addEventListener('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            e.preventDefault();
            executer();
        }
    });

    btnExec.addEventListener('click', executer);

    function executer() {
        const sql = input.value.trim();
        if (!sql) { return; }

        btnExec.disabled = true;
        btnExec.textContent = 'Exécution…';

        const body = new URLSearchParams();
        body.append('sql', sql);

        fetch('/admin/console/executer', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body.toString()
        })
        .then(function (r) { return r.json(); })
        .then(afficher)
        .catch(function (err) {
            afficher({ ok: false, erreur: 'Erreur réseau : ' + err.message });
        })
        .finally(function () {
            btnExec.disabled = false;
            btnExec.innerHTML = libelleExec;
        });
    }

    function afficher(res) {
        carte.style.display = 'block';
        corpsEl.innerHTML = '';
        statutEl.innerHTML = '';

        if (!res.ok) {
            statutEl.className = 'resultats-statut err';
            statutEl.textContent = '✕ Erreur';
            const pre = document.createElement('div');
            pre.className = 'resultats-erreur';
            pre.textContent = res.erreur || 'Erreur inconnue';
            corpsEl.appendChild(pre);
            return;
        }

        statutEl.className = 'resultats-statut ok';

        if (res.type === 'execution') {
            ajouterTexte('✓ Requête exécutée — ' + res.nbAffectes + ' ligne(s) affectée(s)');
            ajouterDuree(res.duree);
            return;
        }

        // Jeu de résultats
        ajouterTexte('✓ ' + res.nbLignes + ' ligne(s)');
        ajouterDuree(res.duree);

        if (res.nbLignes === 0) {
            const vide = document.createElement('div');
            vide.className = 'resultats-vide';
            vide.textContent = 'Aucune ligne retournée.';
            corpsEl.appendChild(vide);
            return;
        }

        corpsEl.appendChild(construireTable(res.colonnes, res.lignes));
    }

    function ajouterTexte(txt) {
        const s = document.createElement('span');
        s.textContent = txt;
        statutEl.appendChild(s);
    }

    function ajouterDuree(duree) {
        const d = document.createElement('span');
        d.className = 'duree';
        d.textContent = duree + ' ms';
        statutEl.appendChild(d);
    }

    function construireTable(colonnes, lignes) {
        const table = document.createElement('table');
        table.className = 'console-table';

        const thead = document.createElement('thead');
        const trh = document.createElement('tr');
        colonnes.forEach(function (c) {
            const th = document.createElement('th');
            th.textContent = c;
            trh.appendChild(th);
        });
        thead.appendChild(trh);
        table.appendChild(thead);

        const tbody = document.createElement('tbody');
        lignes.forEach(function (ligne) {
            const tr = document.createElement('tr');
            colonnes.forEach(function (c) {
                const td = document.createElement('td');
                const val = ligne[c];
                if (val === null) {
                    td.className = 'cell-null';
                    td.textContent = 'NULL';
                } else {
                    td.textContent = val;
                    td.title = val;
                }
                tr.appendChild(td);
            });
            tbody.appendChild(tr);
        });
        table.appendChild(tbody);
        return table;
    }
})();
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
