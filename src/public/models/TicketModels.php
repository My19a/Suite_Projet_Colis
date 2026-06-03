<?php
require_once __DIR__ . "/Model.php";

/**
 * Acces aux donnees du systeme de tickets / assistance.
 * Suit le meme pattern que les autres modeles du projet
 * (connexion via Model::getModel()->bd, requetes preparees).
 *
 */
class TicketModels {

    private $db;

    public function __construct() {
        $this->db = Model::getModel()->bd;
    }

    /* ===================== ECRITURE ===================== */

    /** Cree un ticket et renvoie son identifiant. */
    public function creerTicket(string $sujet, string $description, string $categorie, string $priorite, int $createur_id): int {
        $sql = "
            INSERT INTO ticket (sujet, description, categorie, priorite, createur_id)
            VALUES (?, ?, ?, ?, ?)
        ";
        $req = $this->db->prepare($sql);
        $req->execute([$sujet, $description, $categorie, $priorite, $createur_id]);
        return (int) $this->db->lastInsertId();
    }

    /** Ajoute un message au fil de discussion d'un ticket. */
    public function ajouterMessage(int $ticket_id, int $auteur_id, string $message): void {
        $req = $this->db->prepare("
            INSERT INTO ticket_message (ticket_id, auteur_id, message)
            VALUES (?, ?, ?)
        ");
        $req->execute([$ticket_id, $auteur_id, $message]);

        // On rafraichit la date de mise a jour du ticket parent.
        $this->db->prepare("UPDATE ticket SET date_maj = NOW() WHERE id_ticket = ?")
                 ->execute([$ticket_id]);
    }

    /** Change le statut d'un ticket (ouvert / en_cours / resolu). */
    public function changerStatut(int $ticket_id, string $statut): void {
        $req = $this->db->prepare("UPDATE ticket SET statut = ? WHERE id_ticket = ?");
        $req->execute([$statut, $ticket_id]);
    }

    /** Assigne un ticket a un membre du support (ou NULL pour desassigner). */
    public function assigner(int $ticket_id, ?int $assigne_id): void {
        $req = $this->db->prepare("UPDATE ticket SET assigne_id = ? WHERE id_ticket = ?");
        $req->execute([$assigne_id, $ticket_id]);
    }

    /* ===================== LECTURE ===================== */

    /** Tickets crees par un utilisateur donne (avec nb de messages). */
    public function getTicketsUtilisateur(int $createur_id): array {
        $sql = "
            SELECT t.*,
                   (SELECT COUNT(*) FROM ticket_message m WHERE m.ticket_id = t.id_ticket) AS nb_messages
            FROM ticket t
            WHERE t.createur_id = ?
            ORDER BY t.date_maj DESC
        ";
        $req = $this->db->prepare($sql);
        $req->execute([$createur_id]);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Tous les tickets (vue support/admin), filtrable par statut. */
    public function getTousLesTickets(?string $statut = null): array {
        $sql = "
            SELECT t.*,
                   u.fullName AS createur_nom,
                   (SELECT COUNT(*) FROM ticket_message m WHERE m.ticket_id = t.id_ticket) AS nb_messages
            FROM ticket t
            JOIN utilisateur u ON t.createur_id = u.id_utilisateur
        ";
        $params = [];
        if ($statut !== null && $statut !== '') {
            $sql .= " WHERE t.statut = ?";
            $params[] = $statut;
        }
        $sql .= " ORDER BY FIELD(t.statut, 'ouvert', 'en_cours', 'resolu'), t.date_maj DESC";

        $req = $this->db->prepare($sql);
        $req->execute($params);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Un ticket precis avec les infos de son createur. */
    public function getTicket(int $id): ?array {
        $req = $this->db->prepare("
            SELECT t.*, u.fullName AS createur_nom, u.email AS createur_email
            FROM ticket t
            JOIN utilisateur u ON t.createur_id = u.id_utilisateur
            WHERE t.id_ticket = ?
        ");
        $req->execute([$id]);
        return $req->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /** Fil de discussion d'un ticket (messages + auteurs). */
    public function getMessages(int $ticket_id): array {
        $req = $this->db->prepare("
            SELECT m.*, u.fullName AS auteur_nom, r.libelle AS auteur_role
            FROM ticket_message m
            JOIN utilisateur u ON m.auteur_id = u.id_utilisateur
            JOIN role r        ON u.role_id = r.id_role
            WHERE m.ticket_id = ?
            ORDER BY m.date_envoi ASC
        ");
        $req->execute([$ticket_id]);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Compteurs par statut pour le tableau de bord support. */
    public function compterParStatut(): array {
        $rows = $this->db->query("
            SELECT statut, COUNT(*) AS total FROM ticket GROUP BY statut
        ")->fetchAll(PDO::FETCH_KEY_PAIR);

        return [
            'ouvert'   => (int) ($rows['ouvert']   ?? 0),
            'en_cours' => (int) ($rows['en_cours'] ?? 0),
            'resolu'   => (int) ($rows['resolu']   ?? 0),
        ];
    }
}
