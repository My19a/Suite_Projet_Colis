<?php
require_once __DIR__ . "/Model.php";

/**
 * Suivi de presence des utilisateurs (qui est connecte / en ligne).
 *
 * On considere un utilisateur "en ligne" si sa derniere activite date de
 * moins de SEUIL_MINUTES minutes.
 *
 */
class PresenceModel {

    /** Au-dela de ce delai sans activite, l'utilisateur est considere hors ligne. */
    public const SEUIL_MINUTES = 5;

    private $db;

    public function __construct() {
        $this->db = Model::getModel()->bd;
    }

    /** Met a jour l'horodatage de derniere activite d'un utilisateur. */
    public function marquerActivite(int $idUtilisateur): void {
        $req = $this->db->prepare("UPDATE utilisateur SET derniere_activite = NOW() WHERE id_utilisateur = ?");
        $req->execute([$idUtilisateur]);
    }

    /** Liste tous les utilisateurs avec leur etat de presence. */
    public function getPresence(): array {
        $sql = "
            SELECT u.id_utilisateur, u.fullName, u.email, r.libelle AS role,
                   u.derniere_activite,
                   TIMESTAMPDIFF(MINUTE, u.derniere_activite, NOW()) AS minutes_inactif,
                   (u.derniere_activite IS NOT NULL
                    AND u.derniere_activite >= NOW() - INTERVAL ? MINUTE) AS en_ligne
            FROM utilisateur u
            JOIN role r ON u.role_id = r.id_role
            ORDER BY en_ligne DESC, u.derniere_activite DESC, u.fullName
        ";
        $req = $this->db->prepare($sql);
        $req->execute([self::SEUIL_MINUTES]);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Nombre d'utilisateurs actuellement en ligne. */
    public function compterEnLigne(): int {
        $req = $this->db->prepare("
            SELECT COUNT(*) FROM utilisateur
            WHERE derniere_activite IS NOT NULL
              AND derniere_activite >= NOW() - INTERVAL ? MINUTE
        ");
        $req->execute([self::SEUIL_MINUTES]);
        return (int) $req->fetchColumn();
    }
}
