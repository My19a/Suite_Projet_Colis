<?php
require_once __DIR__ . "/Model.php";

class PostalIutModels {

    private $db;

    public function __construct() {
        $this->db = Model::getModel()->bd;
    }

    /* ===== STATISTIQUES ===== */

    public function getColisRecusIUT() {
        return $this->db->query("SELECT COUNT(*) FROM colis WHERE statut_id = 2")->fetchColumn();
    }

    public function getColisEnAttente() {
        return $this->db->query("SELECT COUNT(*) FROM colis WHERE statut_id = 3")->fetchColumn();
    }

    public function getColisRetires() {
        return $this->db->query("SELECT COUNT(*) FROM colis WHERE statut_id = 4")->fetchColumn();
    }

    public function getColisNonIdentifies() {
        return $this->db->query("SELECT COUNT(*) FROM colis WHERE statut_id = 1 AND destinataire_id IS NULL")->fetchColumn();
    }

    public function countColisRecusAujourdhui() {
        return $this->db->query("SELECT COUNT(*) FROM colis WHERE DATE(date_reception) = CURDATE()")->fetchColumn();
    }

    public function countColisLivresAujourdhui() {
        return $this->db->query("SELECT COUNT(*) FROM colis WHERE DATE(date_retrait) = CURDATE()")->fetchColumn();
    }

    public function getRepartitionStatuts() {
        return $this->db->query("SELECT statut_id AS statut, COUNT(*) AS total FROM colis GROUP BY statut_id")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTopDepartements() {
        $sql = "SELECT d.nom AS departement, COUNT(*) AS total
                FROM colis c
                LEFT JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
                LEFT JOIN departement d ON b.departement_id = d.id_departement
                GROUP BY d.id_departement ORDER BY total DESC LIMIT 5";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ===== LISTES COLIS ===== */

    public function getDerniersColis() {
        $sql = "SELECT c.id_colis, c.numero_suivi, c.date_reception, s.libelle AS statut, d.nom AS departement
                FROM colis c
                LEFT JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
                LEFT JOIN departement d ON b.departement_id = d.id_departement
                JOIN statut_colis s ON c.statut_id = s.id_statut
                ORDER BY c.date_reception DESC LIMIT 10";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllColis() {
        $sql = "SELECT c.id_colis, c.numero_suivi, c.date_reception, c.statut_id,
                       b.numero_commande, d.nom AS departement, s.libelle AS statut
                FROM colis c
                LEFT JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
                LEFT JOIN departement d ON b.departement_id = d.id_departement
                JOIN statut_colis s ON c.statut_id = s.id_statut
                ORDER BY c.date_reception DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getColisRecus() {
        $sql = "SELECT c.id_colis, c.numero_suivi, c.date_reception, d.nom AS departement, s.libelle AS statut
                FROM colis c
                LEFT JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
                LEFT JOIN departement d ON b.departement_id = d.id_departement
                JOIN statut_colis s ON c.statut_id = s.id_statut
                WHERE c.statut_id = 2
                ORDER BY c.date_reception DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getColisRemis() {
        $sql = "SELECT c.id_colis, c.numero_suivi, c.date_reception, c.date_retrait, d.nom AS departement
                FROM colis c
                LEFT JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
                LEFT JOIN departement d ON b.departement_id = d.id_departement
                WHERE c.statut_id = 4
                ORDER BY c.date_retrait DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getColisFiltre($statut) {
        $sql = "SELECT c.id_colis, c.numero_suivi, c.date_reception, c.statut_id,
                       b.numero_commande, d.nom AS departement, s.libelle AS statut
                FROM colis c
                LEFT JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
                LEFT JOIN departement d ON b.departement_id = d.id_departement
                JOIN statut_colis s ON c.statut_id = s.id_statut
                WHERE c.statut_id = ?
                ORDER BY c.date_reception DESC";
        $req = $this->db->prepare($sql);
        $req->execute([$statut]);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getListeColisEnAttente() {
        $sql = "SELECT c.id_colis, c.numero_suivi, c.date_reception, b.numero_commande,
                       d.nom AS departement, s.libelle AS statut
                FROM colis c
                LEFT JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
                LEFT JOIN departement d ON b.departement_id = d.id_departement
                JOIN statut_colis s ON c.statut_id = s.id_statut
                WHERE c.statut_id = 3
                ORDER BY c.date_reception DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getColisNonIdentifie() {
        $sql = "SELECT c.id_colis, c.numero_suivi, c.date_reception, c.commentaire
                FROM colis c WHERE c.statut_id = 1 AND c.destinataire_id IS NULL
                ORDER BY c.date_reception DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getColisATConfirmer() {
        $sql = "SELECT c.id_colis, c.numero_suivi, c.date_reception, d.nom AS departement, b.numero_commande
                FROM colis c
                LEFT JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
                LEFT JOIN departement d ON b.departement_id = d.id_departement
                WHERE c.statut_id = 1
                ORDER BY c.date_reception DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ===== DETAILS COLIS ===== */

    public function getColisById($id_colis) {
        $sql = "SELECT c.*, s.libelle AS statut, b.numero_commande, d.nom AS departement
                FROM colis c
                LEFT JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
                LEFT JOIN departement d ON b.departement_id = d.id_departement
                JOIN statut_colis s ON c.statut_id = s.id_statut
                WHERE c.id_colis = ?";
        $req = $this->db->prepare($sql);
        $req->execute([$id_colis]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    public function getHistoriqueColis($id_colis) {
        $sql = "SELECT action, date_action FROM historique_colis WHERE id_colis = ? ORDER BY date_action DESC";
        $req = $this->db->prepare($sql);
        $req->execute([$id_colis]);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHistoriqueGlobal() {
        $sql = "SELECT h.id_colis, h.date_action, h.action, c.numero_suivi, b.numero_commande
                FROM historique_colis h
                LEFT JOIN colis c ON h.id_colis = c.id_colis
                LEFT JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
                ORDER BY h.date_action DESC LIMIT 200";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ===== RECHERCHE ===== */

    public function rechercherColis($motcle) {
        $motcle = "%" . $motcle . "%";
        $sql = "SELECT c.id_colis, c.numero_suivi, c.date_reception, s.libelle AS statut,
                       d.nom AS departement, b.numero_commande
                FROM colis c
                LEFT JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
                LEFT JOIN departement d ON b.departement_id = d.id_departement
                JOIN statut_colis s ON c.statut_id = s.id_statut
                WHERE c.numero_suivi LIKE ? OR b.numero_commande LIKE ? OR d.nom LIKE ? OR c.id_colis LIKE ?
                ORDER BY c.date_reception DESC";
        $req = $this->db->prepare($sql);
        $req->execute([$motcle, $motcle, $motcle, $motcle]);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ===== ACTIONS ===== */

    public function insertColis($data) {
        $sql = "INSERT INTO colis (bon_commande_id, numero_suivi, destinataire_id, date_reception, statut_id, commentaire)
                VALUES (?, ?, ?, NOW(), ?, ?)";
        $req = $this->db->prepare($sql);
        $result = $req->execute([
            $data["bon_commande_id"] ?? null,
            $data["numero_suivi"],
            $data["destinataire_id"] ?? null,
            $data["statut_id"] ?? 1,
            $data["commentaire"] ?? null
        ]);
        if ($result) $this->addHistorique($this->db->lastInsertId(), "Colis créé");
        return $result;
    }

    public function updateColis($id, $data) {
        $sql = "UPDATE colis SET numero_suivi = ?, bon_commande_id = ?, destinataire_id = ?, statut_id = ?, commentaire = ?
                WHERE id_colis = ?";
        $req = $this->db->prepare($sql);
        $result = $req->execute([
            $data["numero_suivi"], $data["bon_commande_id"], $data["destinataire_id"],
            $data["statut_id"], $data["commentaire"], $id
        ]);
        if ($result) $this->addHistorique($id, "Colis modifié");
        return $result;
    }

    public function updateStatut($id_colis, $nouveau_statut) {
        $sql = "UPDATE colis SET statut_id = ? WHERE id_colis = ?";
        $req = $this->db->prepare($sql);
        $req->execute([$nouveau_statut, $id_colis]);
        $this->addHistorique($id_colis, "Statut modifié en $nouveau_statut");
    }

    public function confirmerReceptionIUT($id_colis) {
        $sql = "UPDATE colis SET statut_id = 2 WHERE id_colis = ?";
        $req = $this->db->prepare($sql);
        $result = $req->execute([$id_colis]);
        $this->addHistorique($id_colis, "Reception confirmee a l'IUT");
        return $result;
    }

    public function marquerColisRetire($id_colis) {
        $sql = "UPDATE colis SET statut_id = 4, date_retrait = NOW() WHERE id_colis = ?";
        $req = $this->db->prepare($sql);
        $result = $req->execute([$id_colis]);
        $this->addHistorique($id_colis, "Colis retiré");
        return $result;
    }

    public function marquerLivre($id) {
        $sql = "UPDATE colis SET statut_id = 2, date_retrait = NOW() WHERE id_colis = ?";
        $req = $this->db->prepare($sql);
        $req->execute([$id]);
        $this->addHistorique($id, "Colis marqué comme livré");
    }

    public function assignerDepartement($id_colis, $departement_id) {
        $sql = "UPDATE colis SET destinataire_id = ?, statut_id = 3 WHERE id_colis = ?";
        $req = $this->db->prepare($sql);
        $req->execute([$departement_id, $id_colis]);
        $this->addHistorique($id_colis, "Colis assigne au departement $departement_id");
    }

    public function marquerNonIdentifie($id_colis) {
        $sql = "UPDATE colis SET destinataire_id = NULL WHERE id_colis = ?";
        $req = $this->db->prepare($sql);
        $req->execute([$id_colis]);
        $this->addHistorique($id_colis, "Colis marque comme non identifie");
    }

    public function addHistorique($colis_id, $action) {
        $sql = "INSERT INTO historique_colis (id_colis, action, date_action) VALUES (?, ?, NOW())";
        $req = $this->db->prepare($sql);
        $req->execute([$colis_id, $action]);
    }

    /* ===== DONNÉES RÉFÉRENTIELLES ===== */

    public function getAllStatuts() {
        return $this->db->query("SELECT * FROM statut_colis")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllDepartements() {
        return $this->db->query("SELECT * FROM departement ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBonCommandes() {
        return $this->db->query("SELECT id_bon_commande, numero_commande FROM bon_commande ORDER BY date_commande DESC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBCInfo($num_bc) {
        $sql = "SELECT b.id_bon_commande, u.id_utilisateur AS destinataire_id, d.id_departement
                FROM bon_commande b
                LEFT JOIN utilisateur u ON b.createur_id = u.id_utilisateur
                LEFT JOIN departement d ON b.departement_id = d.id_departement
                WHERE b.numero_commande = ?";
        $req = $this->db->prepare($sql);
        $req->execute([$num_bc]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    public function rechercherDestinataireParNom($nom)
{
    $sql = "
        SELECT id_utilisateur, fullName
        FROM utilisateur
        WHERE LOWER(fullName) LIKE LOWER(?)
        LIMIT 10
    ";

    $req = $this->db->prepare($sql);
    $req->execute(["%".$nom."%"]);

    return $req->fetchAll(PDO::FETCH_ASSOC);
}

    public function ajouterHistorique(
        $colisId,
        $utilisateurId,
        $ancienStatut,
        $nouveauStatut,
        $commentaire = null
    ) {
        $sql = "
            INSERT INTO historique_colis
            (
                colis_id,
                utilisateur_id,
                date_modification,
                ancien_statut,
                nouveau_statut,
                commentaire
            )
            VALUES (?, ?, NOW(), ?, ?, ?)
        ";

        $req = $this->db->prepare($sql);

        return $req->execute([
            $colisId,
            $utilisateurId,
            $ancienStatut,
            $nouveauStatut,
            $commentaire
        ]);
    }

    public function getDepartementNom($id)
    {
        $req = $this->db->prepare(
            "SELECT nom FROM departement WHERE id_departement = ?"
        );

        $req->execute([$id]);

        return $req->fetchColumn();
    }
}
