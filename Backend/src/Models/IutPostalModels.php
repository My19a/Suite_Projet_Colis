<?php

namespace SAE\Models;

require_once __DIR__ . '/Model.php';

class IutPostalModels {

    private $db;

    public function __construct() {
        $this->db = \Model::getModel()->bd;
    }

    /* ===================== STATISTIQUES ===================== */

    public function countColisRecusAujourdhui() {
        $sql = $this->db->query("SELECT COUNT(*) FROM colis WHERE DATE(date_reception) = CURDATE()");
        return $sql->fetchColumn();
    }

    public function countColisEnAttente() {
        $sql = $this->db->query("SELECT COUNT(*) FROM colis WHERE statut_id = 1");
        return $sql->fetchColumn();
    }

    public function countColisLivresAujourdhui() {
        $sql = $this->db->query("SELECT COUNT(*) FROM colis WHERE DATE(date_retrait) = CURDATE()");
        return $sql->fetchColumn();
    }

    public function countColisNonIdentifies() {
        $sql = $this->db->query("SELECT COUNT(*) FROM colis WHERE destinataire_id IS NULL");
        return $sql->fetchColumn();
    }

    /* ===================== COLIS RECENTS ===================== */

    public function getColisRecents() {
        $sql = $this->db->query("
            SELECT
                c.id_colis,
                c.date_reception,
                c.statut_id,
                b.numero_commande,
                d.nom AS departement
            FROM colis c
            LEFT JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
            LEFT JOIN departement d ON c.destinataire_id = d.id_departement
            ORDER BY c.date_reception DESC
            LIMIT 5
        ");
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getRepartitionStatuts() {
        $sql = "SELECT statut_id AS statut, COUNT(*) AS total
                FROM colis
                GROUP BY statut_id";
        return $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getTopDepartements() {
        $sql = "SELECT d.nom AS departement, COUNT(*) AS total
                FROM colis c
                LEFT JOIN departement d ON c.destinataire_id = d.id_departement
                GROUP BY c.destinataire_id
                ORDER BY total DESC
                LIMIT 5";
        return $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    /* ===================== LISTE DES COLIS ===================== */

    public function getAllColis() {
        $sql = "
            SELECT c.id_colis,
                   c.numero_suivi,
                   c.date_reception,
                   c.statut_id,
                   b.numero_commande,
                   d.nom AS departement
            FROM colis c
            LEFT JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
            LEFT JOIN departement d ON c.destinataire_id = d.id_departement
            ORDER BY c.date_reception DESC
        ";
        return $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllStatuts() {
        return $this->db->query("SELECT * FROM statut_colis")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getColisFiltre($statut) {
        $sql = "
            SELECT c.id_colis,
                   c.numero_suivi,
                   c.date_reception,
                   c.statut_id,
                   b.numero_commande,
                   d.nom AS departement
            FROM colis c
            LEFT JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
            LEFT JOIN departement d ON c.destinataire_id = d.id_departement
            WHERE c.statut_id = ?
            ORDER BY c.date_reception DESC
        ";

        $req = $this->db->prepare($sql);
        $req->execute([$statut]);

        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getColisRemis() {
        $sql = "
            SELECT 
                c.id_colis,
                c.numero_suivi,
                c.date_retrait,
                c.date_reception,
                c.statut_id,
                b.numero_commande,
                d.nom AS departement
            FROM colis c
            LEFT JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
            LEFT JOIN departement d ON c.destinataire_id = d.id_departement
            WHERE c.statut_id = 2
            ORDER BY c.date_retrait DESC
        ";
        $req = $this->db->query($sql);
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }


    /* ============================= */
/*       RECHERCHE COLIS         */
/* ============================= */

    public function rechercherColis($motcle) {
        $motcle = "%".$motcle."%";

        $sql = "
            SELECT 
                c.id_colis,
                c.numero_suivi,
                c.date_reception,
                c.statut_id,
                b.numero_commande,
                d.nom AS departement
            FROM colis c
            LEFT JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
            LEFT JOIN departement d ON c.destinataire_id = d.id_departement
            WHERE 
                c.numero_suivi LIKE ? OR
                b.numero_commande LIKE ? OR
                d.nom LIKE ? OR
                c.id_colis LIKE ?
            ORDER BY c.date_reception DESC
        ";

        $req = $this->db->prepare($sql);
        $req->execute([$motcle, $motcle, $motcle, $motcle]);

        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }




/*      pour AJOUTER UN COLIS         */

    public function insertColis($data) {

        $sql = "INSERT INTO colis 
                (bon_commande_id, numero_suivi, destinataire_id, date_reception, statut_id, commentaire)
                VALUES (?, ?, ?, NOW(), ?, ?)";

        $req = $this->db->prepare($sql);

        return $req->execute([
            $data["bon_commande_id"],
            $data["numero_suivi"],
            $data["destinataire_id"],
            $data["statut_id"],
            $data["commentaire"]
        ]);
    }

/* Trouver infos d’un BC → retrouver destinataire automatiquement */
    public function getBCInfo($num_bc) {

        $sql = "SELECT b.id_bon_commande, u.id_utilisateur AS destinataire_id, d.id_departement
                FROM bon_commande b
                LEFT JOIN utilisateur u ON b.createur_id = u.id_utilisateur
                LEFT JOIN departement d ON b.departement_id = d.id_departement
                WHERE b.numero_commande = ?";

        $req = $this->db->prepare($sql);
        $req->execute([$num_bc]);

        return $req->fetch(\PDO::FETCH_ASSOC);
    }


    public function ajouterColis($numero_commande, $numero_suivi, $commentaire, $photo) {
        $sql = "INSERT INTO colis 
                (bon_commande_id, numero_suivi, commentaire, photo, date_reception, statut_id)
                VALUES (?, ?, ?, ?, NOW(), 1)";

        $req = $this->db->prepare($sql);
        return $req->execute([$numero_commande, $numero_suivi, $commentaire, $photo]);
    }



    public function getColisById($id) {
        $sql = "
            SELECT c.*, 
                b.numero_commande,
                d.nom AS departement
            FROM colis c
            LEFT JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
            LEFT JOIN departement d ON c.destinataire_id = d.id_departement
            WHERE c.id_colis = ?
        ";
        $req = $this->db->prepare($sql);
        $req->execute([$id]);
        return $req->fetch(\PDO::FETCH_ASSOC);
    }


    public function addHistorique($colis_id, $action) {
        $sql = "INSERT INTO historique_colis (colis_id, action, date_action) VALUES (?, ?, NOW())";
        $req = $this->db->prepare($sql);
        $req->execute([$colis_id, $action]);
    }


    public function updateStatut($id_colis, $nouveau_statut) {
        $sql = "UPDATE colis SET statut_id = ? WHERE id_colis = ?";
        $req = $this->db->prepare($sql);
        $req->execute([$nouveau_statut, $id_colis]);

        $this->addHistorique($id_colis, "Statut modifié en $nouveau_statut");
    }


    public function marquerLivre($id) {
        $sql = "UPDATE colis 
                SET statut_id = 2, date_retrait = NOW()
                WHERE id_colis = ?";
        $req = $this->db->prepare($sql);
        $req->execute([$id]);

        $this->addHistorique($id, "Colis marqué comme livré");
    }


    public function getHistorique($id_colis) {
        $sql = "SELECT * FROM historique_colis 
                WHERE colis_id = ?
                ORDER BY date_action DESC";
        $req = $this->db->prepare($sql);
        $req->execute([$id_colis]);
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function marquerRetire($id) {
        $sql = "UPDATE colis 
                SET statut_id = 3, date_retrait = NOW()
                WHERE id_colis = ?";
        $req = $this->db->prepare($sql);
        $req->execute([$id]);

        $this->addHistorique($id, "Colis retiré par utilisateur");
    }




    public function getColisNonIdentifies() {
        $sql = "
            SELECT 
                c.id_colis,
                c.numero_suivi,
                c.date_reception,
                b.numero_commande,
                c.commentaire
            FROM colis c
            LEFT JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
            WHERE c.statut_id = 4 OR c.destinataire_id IS NULL
            ORDER BY c.date_reception DESC
        ";
        return $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function assignerDepartement($id_colis, $departement_id) {

        // récupérer un utilisateur du département (premier trouvé)
        $sqlUser = "SELECT id_utilisateur FROM utilisateur WHERE departement_id = ? LIMIT 1";
        $reqUser = $this->db->prepare($sqlUser);
        $reqUser->execute([$departement_id]);
        $destinataire = $reqUser->fetchColumn();

        // mise à jour du colis
        $sql = "UPDATE colis 
                SET destinataire_id = ?, statut_id = 1
                WHERE id_colis = ?";
        $req = $this->db->prepare($sql);
        $req->execute([$destinataire, $id_colis]);

        // historique
        $this->addHistorique($id_colis, "Colis identifié et assigné au département ID $departement_id");
    }


    public function marquerNonIdentifie($id_colis) {
        $sql = "UPDATE colis SET statut_id = 4 WHERE id_colis = ?";
        $req = $this->db->prepare($sql);
        $req->execute([$id_colis]);

        $this->addHistorique($id_colis, "Colis marqué comme non identifié");
    }

    public function getAllDepartement(){
        return $this->db->query("SELECT * FROM departement")->fetchAll(\PDO::FETCH_ASSOC);

    }

    public function marquerNonIdentifier($id) {
        $sql = "UPDATE colis 
                SET statut_id = 4
                WHERE id_colis = ?";
        $req = $this->db->prepare($sql);
        $req->execute([$id]);

        $this->addHistorique($id, "Colis marqué NON IDENTIFIÉ");
    }



    public function assignerColisDepartement($id_colis, $departement_id) {

        // 1) mise à jour destinataire + statut "en attente"
        $sql = "UPDATE colis 
                SET destinataire_id = ?, statut_id = 1
                WHERE id_colis = ?";
        $req = $this->db->prepare($sql);
        $req->execute([$departement_id, $id_colis]);

        // 2) historique
        $this->addHistorique($id_colis, "Colis assigné au département $departement_id");
    }



    public function marquerCommeNonIdentifie($id_colis) {

        // statut = 4
        $sql = "UPDATE colis 
                SET statut_id = 4
                WHERE id_colis = ?";
        $req = $this->db->prepare($sql);
        $req->execute([$id_colis]);

        // historique
        $this->addHistorique($id_colis, "Colis marqué comme non identifié");
    }


    public function getColisEnAttente() {
        $sql = "
            SELECT 
                c.id_colis,
                c.numero_suivi,
                c.date_reception,
                b.numero_commande,
                d.nom AS departement,
                c.statut_id
            FROM colis c
            LEFT JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
            LEFT JOIN departement d ON c.destinataire_id = d.id_departement
            WHERE c.statut_id = 1
            ORDER BY c.date_reception DESC";

        return $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }


    /* ============================= */
/*     HISTORIQUE GLOBAL         */
/* ============================= */

    public function getHistoriqueGlobal() {
        $sql = "
            SELECT 
                h.colis_id,
                h.date_action,
                h.action,
                c.numero_suivi,
                b.numero_commande
            FROM historique_colis h
            LEFT JOIN colis c ON h.colis_id = c.id_colis
            LEFT JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
            ORDER BY h.date_action DESC
            LIMIT 200
        ";

        $req = $this->db->query($sql);
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function updateColis($id, $data) {

        $sql = "UPDATE colis 
                SET numero_suivi = ?,
                    bon_commande_id = ?,
                    destinataire_id = ?,
                    statut_id = ?,
                    commentaire = ?
                WHERE id_colis = ?";

        $req = $this->db->prepare($sql);
        return $req->execute([
            $data["numero_suivi"],
            $data["bon_commande_id"],
            $data["destinataire_id"],
            $data["statut_id"],
            $data["commentaire"],
            $id
        ]);
    }



    // dans IutPostalModels.php (class IutPostalModels)

    public function getBonCommandes() {
        $sql = "SELECT id_bon_commande, numero_commande FROM bon_commande ORDER BY date_commande DESC";
        return $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function updateColisById($id_colis, $data) {
        $sql = "UPDATE colis SET
                    numero_suivi = ?,
                    bon_commande_id = ?,
                    destinataire_id = ?,
                    statut_id = ?,
                    commentaire = ?
                WHERE id_colis = ?";
        $req = $this->db->prepare($sql);
        return $req->execute([
            $data['numero_suivi'],
            $data['bon_commande_id'],
            $data['destinataire_id'],
            $data['statut_id'],
            $data['commentaire'],
            $id_colis
        ]);
    }





}