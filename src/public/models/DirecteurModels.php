<?php

require_once __DIR__ . "/Model.php";

class DirecteurModels {

    private $db;

    public function __construct() {
        $this->db = Model::getModel()->bd;
    }

    /*  DEVIS  */

    public function getDevisAValider() {
        $sql = "
            SELECT 
                d.id_devis,
                d.objet,
                d.montant_estime,
                d.date_demande,
                dep.nom AS departement,
                u.fullName AS demandeur
            FROM devis d
            JOIN utilisateur u ON d.createur_id = u.id_utilisateur
            JOIN departement dep ON u.departement_id = dep.id_departement
            WHERE d.statut = 'valide_finance'
            ORDER BY d.date_demande DESC
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBonCommandeSignes() {
        $sql = "
            SELECT b.id_bon_commande, b.numero_commande, b.date_commande
            FROM bon_commande b
            ORDER BY b.date_commande DESC
            LIMIT 20
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /*  STATS  */

    public function countDevisEnAttente() {
        return $this->db
            ->query("SELECT COUNT(*) FROM devis WHERE statut = 'valide_finance'")
            ->fetchColumn();
    }

    public function countBonCommande() {
        return $this->db
            ->query("SELECT COUNT(*) FROM bon_commande")
            ->fetchColumn();
    }


    /* ===================== */
/*   SIGNATURE DEVIS     */
/* ===================== */

    public function getDevisById($id) {
        $sql = "
            SELECT
                d.*,
                f.nom AS fournisseur_nom,
                u.fullName AS demandeur_nom,
                u.departement_id,
                dep.nom AS departement_nom
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

    public function signerDevis($id_devis, array $commande = [], array $colis = []) {
        $this->db->beginTransaction();

        try {
            $devis = $this->getDevisById($id_devis);
            if (!$devis) {
                throw new RuntimeException("Devis introuvable");
            }

            $objet = trim($commande['objet'] ?? $devis['objet']);
            $montant = (float) ($commande['montant_estime'] ?? $devis['montant_estime']);
            $numeroBC = trim($commande['numero_commande'] ?? '');
            if ($numeroBC === '') {
                $numeroBC = "BC-" . date("Y") . "-" . str_pad($id_devis, 3, "0", STR_PAD_LEFT);
            }

            $sql = "UPDATE devis SET statut = 'signe_directeur', objet = ?, montant_estime = ? WHERE id_devis = ?";
            $req = $this->db->prepare($sql);
            $req->execute([$objet, $montant, $id_devis]);

            $sqlBC = "
                INSERT INTO bon_commande (
                    numero_commande,
                    date_commande,
                    date_estimee_livraison,
                    montant_estime,
                    statut,
                    fournisseur_id,
                    createur_id,
                    departement_id,
                    devis_id,
                    commentaire
                )
                VALUES (?, CURDATE(), ?, ?, 'en_cours', ?, ?, ?, ?, ?)
            ";
            $reqBC = $this->db->prepare($sqlBC);
            $reqBC->execute([
                $numeroBC,
                $commande['date_estimee_livraison'] ?: null,
                $montant,
                $devis['fournisseur_id'],
                $devis['createur_id'],
                $devis['departement_id'],
                $id_devis,
                $objet
            ]);

            $idBC = (int) $this->db->lastInsertId();
            $reqColis = $this->db->prepare("
                INSERT INTO colis (
                    bon_commande_id,
                    statut_id,
                    numero_suivi,
                    destinataire_id,
                    commentaire
                )
                VALUES (?, 3, ?, ?, ?)
            ");

            foreach ($colis as $ligne) {
                $numeroSuivi = trim($ligne['numero_suivi'] ?? '');
                if ($numeroSuivi === '') {
                    continue;
                }
                $description = trim($ligne['description'] ?? '');
                $quantite = max(1, (int) ($ligne['quantite'] ?? 1));
                $commentaire = trim($description . "\nQuantite : " . $quantite);
                $reqColis->execute([$idBC, $numeroSuivi, $devis['createur_id'], $commentaire]);
            }

            $this->db->commit();
            return $idBC;
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }


    public function getTousLesBonsCommande() {
        $sql = "
            SELECT 
                b.id_bon_commande,
                b.numero_commande,
                b.date_commande,
                d.objet,
                d.montant_estime
            FROM bon_commande b
            LEFT JOIN devis d ON b.devis_id = d.id_devis
            ORDER BY b.date_commande DESC
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDevisPDF($id) {
        $sql = "SELECT fichier_pdf FROM devis WHERE id_devis = ?";
        $req = $this->db->prepare($sql);
        $req->execute([$id]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }


    public function bonCommandeExistePourDevis($id_devis) {
        $sql = "SELECT COUNT(*) FROM bon_commande WHERE devis_id = ?";
        $req = $this->db->prepare($sql);
        $req->execute([$id_devis]);
        return $req->fetchColumn() > 0;
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
