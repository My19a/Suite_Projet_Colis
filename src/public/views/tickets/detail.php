<?php
$titre = 'Ticket #' . (int) $ticket['id_ticket'] . ' - ' . $ticket['sujet'];
$actif = '/tickets';
$feuillesDeStyle = ['/assets/css/style-tickets.css'];
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Ticket #<?= (int) $ticket['id_ticket'] ?></h1>
            <p class="page-subtitle"><?= e($ticket['sujet']) ?></p>
        </div>
        <a class="bouton bouton-secondaire" href="/tickets">&larr; Retour à la liste</a>
    </div>

    <div class="ticket-meta">
        <div class="ticket-meta-item">
            <span class="ticket-meta-label">Statut</span>
            <span class="ticket-meta-valeur">
                <span class="badge badge-<?= e($ticket['statut']) ?>">
                    <?= e(libelleStatut($ticket['statut'])) ?>
                </span>
            </span>
        </div>
        <div class="ticket-meta-item">
            <span class="ticket-meta-label">Priorité</span>
            <span class="ticket-meta-valeur">
                <span class="badge badge-priorite-<?= e($ticket['priorite']) ?>"><?= e(libellePriorite($ticket['priorite'])) ?></span>
            </span>
        </div>
        <div class="ticket-meta-item">
            <span class="ticket-meta-label">Catégorie</span>
            <span class="ticket-meta-valeur"><?= ucfirst(e($ticket['categorie'])) ?></span>
        </div>
        <div class="ticket-meta-item">
            <span class="ticket-meta-label">Demandeur</span>
            <span class="ticket-meta-valeur"><?= e($ticket['createur_nom'] ?? '') ?></span>
        </div>
        <div class="ticket-meta-item">
            <span class="ticket-meta-label">Ouvert le</span>
            <span class="ticket-meta-valeur"><?= date('d/m/Y H:i', strtotime($ticket['date_creation'])) ?></span>
        </div>
    </div>

    <?php if ($estSupport): ?>
    <form class="ticket-actions-support" method="POST" action="/tickets/<?= (int) $ticket['id_ticket'] ?>/statut">
        <span class="ticket-meta-label">Changer le statut :</span>
        <select name="statut" class="liste-deroulante" style="max-width:200px;">
            <?php foreach ($statuts as $s): ?>
                <option value="<?= e($s) ?>" <?= $ticket['statut'] === $s ? 'selected' : '' ?>>
                    <?= e(libelleStatut($s)) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="bouton bouton-principal bouton-petit">Mettre à jour</button>
    </form>
    <?php endif; ?>

    <div class="bloc">
        <div class="bloc-entete">
            <h2 class="bloc-titre">Discussion</h2>
            <span class="bloc-sous-titre"><?= count($messages) ?> message(s)</span>
        </div>

        <div class="ticket-fil">
            <?php foreach ($messages as $m): ?>
                <?php $isSupport = strtolower($m['auteur_role']) === 'admin'; ?>
                <div class="ticket-message <?= $isSupport ? 'is-support' : '' ?>">
                    <div class="ticket-message-entete">
                        <span class="ticket-message-auteur">
                            <?= e($m['auteur_nom']) ?>
                            <?php if ($isSupport): ?><span class="ticket-message-role">Support</span><?php endif; ?>
                        </span>
                        <span class="ticket-message-date"><?= date('d/m/Y H:i', strtotime($m['date_envoi'])) ?></span>
                    </div>
                    <div class="ticket-message-corps"><?= nl2br(e($m['message'])) ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($ticket['statut'] === 'resolu' && !$estSupport): ?>
            <p class="vide">Ce ticket est résolu. Réouvrez-en un nouveau si le problème persiste.</p>
        <?php else: ?>
            <form class="ticket-reponse" method="POST" action="/tickets/<?= (int) $ticket['id_ticket'] ?>/message">
                <div class="champ">
                    <label for="message" class="etiquette">Votre réponse</label>
                    <textarea id="message" name="message" placeholder="Écrire un message..." required></textarea>
                </div>
                <div class="formulaire-boutons">
                    <button type="submit" class="bouton bouton-principal">Envoyer</button>
                </div>
            </form>
        <?php endif; ?>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
