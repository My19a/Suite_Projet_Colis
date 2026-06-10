<?php
require_once __DIR__ . "/../models/TicketModels.php";

/**
 * Systeme de tickets et d'assistance.
 *
 * - N'importe quel utilisateur connecte peut ouvrir un ticket et suivre
 *   ses propres tickets.
 * - L'administrateur ("administrateur BD" cote S4) voit tous les tickets,
 *   y repond et change leur statut.
 *
 */
class TicketController {

    private TicketModels $model;

    /** Statuts et categories autorises (garde-fou cote serveur). */
    private const STATUTS    = ['ouvert', 'en_cours', 'resolu'];
    private const PRIORITES  = ['basse', 'normale', 'haute'];
    private const CATEGORIES = ['general', 'colis', 'devis', 'compte', 'technique'];

    public function __construct() {
        $this->model = new TicketModels();
    }

    private function user(): User {
        return $_SESSION['user'];
    }

    private function estSupport(): bool {
        // L'admin joue le role de support. Adapter ici si un autre role
        // doit aussi gerer les tickets (ex. "responsable colis").
        return $this->user()->getRole() === 'admin';
    }

    /** URL du tableau de bord selon le role, pour les liens de retour. */
    private function dashboardUrl(): string {
        $map = [
            'admin'        => '/admin/dashboard',
            'postal_iut'   => '/postal/dashboard',
            'postal_univ'  => '/postal-univ/dashboard',
            'finance'      => '/finance/dashboard',
            'directeur'    => '/directeur/dashboard',
            'departement'  => '/departement/dashboard',
        ];
        return $map[$this->user()->getRole()] ?? '/';
    }

    /* ===================== PAGES ===================== */

    /** Liste : tous les tickets pour le support, sinon mes tickets. */
    public function index() {
        $estSupport   = $this->estSupport();
        $dashboardUrl = $this->dashboardUrl();

        if ($estSupport) {
            $filtre  = $_GET['statut'] ?? null;
            $tickets = $this->model->getTousLesTickets($filtre);
            $stats   = $this->model->compterParStatut();
        } else {
            $filtre  = null;
            $tickets = $this->model->getTicketsUtilisateur($this->user()->getId());
            $stats   = null;
        }

        // Notifications non lues (nouvelles reponses) : affichees puis marquees lues.
        $notifications = $this->model->getNotificationsNonLues($this->user()->getId());
        $this->model->marquerNotificationsLues($this->user()->getId());

        require __DIR__ . "/../views/tickets/index.php";
    }

    /** Formulaire de creation d'un ticket. */
    public function nouveau() {
        $dashboardUrl = $this->dashboardUrl();
        $categories   = self::CATEGORIES;
        $priorites    = self::PRIORITES;
        $erreurs      = $_SESSION['ticket_erreurs'] ?? [];
        $ancien       = $_SESSION['ticket_ancien'] ?? [];
        unset($_SESSION['ticket_erreurs'], $_SESSION['ticket_ancien']);

        require __DIR__ . "/../views/tickets/nouveau.php";
    }

    /** Traitement du formulaire de creation. */
    public function creer() {
        $sujet       = trim($_POST['sujet'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $categorie   = $_POST['categorie'] ?? 'general';
        $priorite    = $_POST['priorite'] ?? 'normale';

        // Validation cote serveur.
        $erreurs = [];
        if ($sujet === '' || mb_strlen($sujet) < 5) {
            $erreurs['sujet'] = "Le sujet doit faire au moins 5 caracteres.";
        }
        if (mb_strlen($sujet) > 150) {
            $erreurs['sujet'] = "Le sujet ne doit pas depasser 150 caracteres.";
        }
        if ($description === '' || mb_strlen($description) < 10) {
            $erreurs['description'] = "Merci de decrire le probleme (10 caracteres minimum).";
        }
        if (!in_array($categorie, self::CATEGORIES, true)) {
            $categorie = 'general';
        }
        if (!in_array($priorite, self::PRIORITES, true)) {
            $priorite = 'normale';
        }

        if (!empty($erreurs)) {
            $_SESSION['ticket_erreurs'] = $erreurs;
            $_SESSION['ticket_ancien']  = ['sujet' => $sujet, 'description' => $description,
                                           'categorie' => $categorie, 'priorite' => $priorite];
            header('Location: /tickets/nouveau');
            exit;
        }

        $id = $this->model->creerTicket($sujet, $description, $categorie, $priorite, $this->user()->getId());

        // Le contenu initial devient aussi le premier message du fil.
        $this->model->ajouterMessage($id, $this->user()->getId(), $description);

        header('Location: /tickets/' . $id);
        exit;
    }

    /** Detail d'un ticket + fil de discussion. */
    public function detail($id) {
        $id     = (int) $id;
        $ticket = $this->model->getTicket($id);

        if (!$ticket) {
            throw new \Exception("Ticket introuvable", 404);
        }
        // Un utilisateur ne peut voir que ses propres tickets (sauf support).
        if (!$this->estSupport() && (int) $ticket['createur_id'] !== $this->user()->getId()) {
            throw new \Exception("Acces refuse", 403);
        }

        $messages     = $this->model->getMessages($id);
        $estSupport   = $this->estSupport();
        $statuts      = self::STATUTS;
        $dashboardUrl = $this->dashboardUrl();

        require __DIR__ . "/../views/tickets/detail.php";
    }

    /** Ajout d'un message dans le fil d'un ticket. */
    public function repondre($id) {
        $id      = (int) $id;
        $ticket  = $this->model->getTicket($id);
        $message = trim($_POST['message'] ?? '');

        if (!$ticket) {
            throw new \Exception("Ticket introuvable", 404);
        }
        if (!$this->estSupport() && (int) $ticket['createur_id'] !== $this->user()->getId()) {
            throw new \Exception("Acces refuse", 403);
        }
        if ($message !== '') {
            $auteurId   = $this->user()->getId();
            $createurId = (int) $ticket['createur_id'];

            $this->model->ajouterMessage($id, $auteurId, $message);

            // Si le support repond a un ticket encore "ouvert", il passe "en_cours".
            if ($this->estSupport() && $ticket['statut'] === 'ouvert') {
                $this->model->changerStatut($id, 'en_cours');
            }

            // Notification a l'autre partie (dans les 2 sens).
            if ($auteurId === $createurId) {
                // Le demandeur a repondu -> on previent le support (les admins).
                foreach ($this->model->getAdminIds() as $adminId) {
                    if ($adminId !== $auteurId) {
                        $this->model->ajouterNotification($adminId,
                            "Nouvelle reponse sur le ticket #{$id} : " . $ticket['sujet']);
                    }
                }
            } else {
                // Le support a repondu -> on previent le demandeur.
                $this->model->ajouterNotification($createurId,
                    "Reponse du support sur votre ticket #{$id} : " . $ticket['sujet']);
            }
        }

        header('Location: /tickets/' . $id);
        exit;
    }

    /** Changement de statut (reserve au support). */
    public function changerStatut($id) {
        if (!$this->estSupport()) {
            throw new \Exception("Acces refuse", 403);
        }
        $id     = (int) $id;
        $statut = $_POST['statut'] ?? '';

        if (in_array($statut, self::STATUTS, true)) {
            $this->model->changerStatut($id, $statut);
        }
        header('Location: /tickets/' . $id);
        exit;
    }
}
