<?php
$titre = 'Dashboard – Administrateur';
$actif = '/admin/dashboard';
$avecTutoriel = true;
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Tableau de bord</h1>
            <p class="page-subtitle">Vue globale du système</p>
        </div>
        <form method="post" action="/admin/test-mail" style="display:flex; gap:0.5rem; align-items:center;">
            <input type="email" name="to" class="form-input" placeholder="destinataire@exemple.com"
                   value="<?= htmlspecialchars(getenv('MAIL_TEST_TO') ?: '') ?>" required
                   style="min-width:240px;">
            <button type="submit" class="btn btn-secondary">Envoyer mail de test</button>
        </form>
    </div>

    <?php if (isset($_GET['mail'])): ?>
        <?php if ($_GET['mail'] === 'ok'): ?>
            <div class="alert alert-success">
                Mail de test envoyé avec succès vers <strong><?= htmlspecialchars($_GET['to'] ?? '') ?></strong>.
            </div>
        <?php else: ?>
            <div class="alert alert-error">
                Erreur lors de l'envoi : <?= htmlspecialchars($_GET['msg'] ?? 'inconnue') ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-label">Utilisateurs</span>
            <div class="stat-value"><?= $stats["utilisateurs"] ?></div>
            <div class="stat-description">Total</div>
        </div>

        <div class="stat-card stat-blue">
            <span class="stat-label">Devis</span>
            <div class="stat-value"><?= $stats["devis"] ?></div>
            <div class="stat-description">Total</div>
        </div>

        <div class="stat-card stat-warning">
            <span class="stat-label">Bons de commande</span>
            <div class="stat-value"><?= $stats["bons"] ?></div>
            <div class="stat-description">Total</div>
        </div>

        <div class="stat-card stat-success">
            <span class="stat-label">Colis</span>
            <div class="stat-value"><?= $stats["colis"] ?></div>
            <div class="stat-description">Total</div>
        </div>
    </div>

    <div class="section">
        <div class="section-header">
            <h2 class="section-title">Repartition des utilisateurs par role</h2>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Role</th>
                        <th>Nombre</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($roles)): ?>
                        <tr><td colspan="2" class="empty-state">Aucun role trouve</td></tr>
                    <?php else: ?>
                        <?php foreach ($roles as $r): ?>
                        <tr>
                            <td><strong><?= ucfirst(htmlspecialchars($r["libelle"])) ?></strong></td>
                            <td><?= $r["total"] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
