<?php
require_once __DIR__ . "/Model.php";

class FinanceModels {

    private $db;

    public function __construct() {
        $this->db = Model::getModel()->bd;
    }


    public function countDevisEnAttente() {
        return $this->db
            ->query("SELECT COUNT(*) FROM devis WHERE statut = 'en_attente'")
            ->fetchColumn();
    }

    public function countBonCommande() {
        return $this->db
            ->query("SELECT COUNT(*) FROM bon_commande")
            ->fetchColumn();
    }

    public function getBudgetsDepartements() {
        return $this->db->query("
            SELECT nom, budget_total, budget_utilise
            FROM departement
        ")->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getDevisEnAttente() {
        return $this->db->query("
            SELECT d.id_devis, d.objet, d.montant_estime, dep.nom AS departement
            FROM devis d
            JOIN utilisateur u ON d.createur_id = u.id_utilisateur
            JOIN departement dep ON u.departement_id = dep.id_departement
            WHERE d.statut = 'en_attente'
            ORDER BY d.date_demande DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBonsCommandeRecents() {
        return $this->db->query("
            SELECT numero_commande, date_commande, montant_estime, statut
            FROM bon_commande
            ORDER BY date_commande DESC
            LIMIT 10
        ")->fetchAll(PDO::FETCH_ASSOC);
    }


    public function validerDevis($id) {

        // 1️⃣ Récupérer le devis + département
        $sql = "
            SELECT d.montant_estime, u.departement_id
            FROM devis d
            JOIN utilisateur u ON d.createur_id = u.id_utilisateur
            WHERE d.id_devis = ?
        ";
        $req = $this->db->prepare($sql);
        $req->execute([$id]);
        $devis = $req->fetch(PDO::FETCH_ASSOC);

        if (!$devis) {
            return;
        }

        // 2️⃣ Mettre à jour le statut du devis
        $sql = "UPDATE devis SET statut = 'valide_finance' WHERE id_devis = ?";
        $req = $this->db->prepare($sql);
        $req->execute([$id]);

        // 3️⃣ Mettre à jour le budget du département
        $sql = "
            UPDATE departement
            SET budget_utilise = budget_utilise + ?
            WHERE id_departement = ?
        ";
        $req = $this->db->prepare($sql);
        $req->execute([
            $devis["montant_estime"],
            $devis["departement_id"]
        ]);
    }

    public function rejeterDevis($id) {
        $sql = "UPDATE devis SET statut = 'rejete_finance' WHERE id_devis = ?";
        $req = $this->db->prepare($sql);
        $req->execute([$id]);
    }

    public function getDevisAVerifier() {
        $sql = "
            SELECT 
                d.id_devis,
                d.objet,
                d.montant_estime,
                d.date_demande,
                dep.nom AS departement
            FROM devis d
            LEFT JOIN utilisateur u ON d.createur_id = u.id_utilisateur
            LEFT JOIN departement dep ON u.departement_id = dep.id_departement
            WHERE d.statut = 'en_attente'
            ORDER BY d.date_demande DESC
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getTousLesBonsCommande() {
        $sql = "
            SELECT 
                b.id_bon_commande,
                b.numero_commande,
                b.date_commande,
                b.montant_estime,
                b.statut,
                dep.nom AS departement,
                f.nom AS fournisseur
            FROM bon_commande b
            LEFT JOIN departement dep ON b.departement_id = dep.id_departement
            LEFT JOIN fournisseur f ON b.fournisseur_id = f.id_fournisseur
            ORDER BY b.date_commande DESC
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getBudgetDepartements() {
        $sql = "
            SELECT
                nom,
                budget_total,
                budget_utilise,
                (budget_total - budget_utilise) AS budget_restant
            FROM departement
            ORDER BY nom
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDevisById($id) {
        $sql = "SELECT * FROM devis WHERE id_devis = ?";
        $req = $this->db->prepare($sql);
        $req->execute([$id]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    public function getDevisComplet($id) {
        $sql = "
            SELECT
                d.id_devis,
                d.date_demande,
                d.objet,
                d.montant_estime,
                d.statut,
                f.nom AS fournisseur_nom,
                f.contact_nom AS fournisseur_contact,
                f.contact_email AS fournisseur_email,
                f.contact_telephone AS fournisseur_telephone,
                u.fullName AS demandeur_nom,
                u.email AS demandeur_email,
                dep.nom AS departement_nom,
                dep.budget_total,
                dep.budget_utilise
            FROM devis d
            LEFT JOIN fournisseur f ON d.fournisseur_id = f.id_fournisseur
            LEFT JOIN utilisateur u ON d.createur_id = u.id_utilisateur
            LEFT JOIN departement dep ON u.departement_id = dep.id_departement
            WHERE d.id_devis = ?
        ";
        $req = $this->db->prepare($sql);
        $req->execute([$id]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }


    
}