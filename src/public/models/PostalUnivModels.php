<?php
require_once __DIR__ . '/Model.php';

class PostalUnivModels {

    private $db;

    public function __construct() {
        $this->db = Model::getModel()->bd;
    }

    public function getColisRecus() {
        return $this->db->query("SELECT COUNT(*) FROM colis")
            ->fetchColumn();
    }

    public function getColisATransferer() {
        return $this->db->query("SELECT COUNT(*) FROM colis WHERE statut_id = 1")
            ->fetchColumn();
    }

    public function getColisTransferes() {
        return $this->db->query("SELECT COUNT(*) FROM colis WHERE statut_id = 2")
            ->fetchColumn();
    }

    public function getColisNonIdentifies() {
        return $this->db->query("SELECT COUNT(*) FROM colis WHERE statut_id = 4")
            ->fetchColumn();
    }

    public function getDerniersColis() {
        return $this->db->query("
            SELECT
                c.id_colis,
                c.numero_suivi,
                c.date_reception,
                s.libelle AS statut
            FROM colis c
            JOIN statut_colis s ON c.statut_id = s.id_statut
            ORDER BY c.date_reception DESC
            LIMIT 10
        ")->fetchAll(PDO::FETCH_ASSOC);
    }


    public function ajouterColisUniversite($data) {

    $req = $this->db->prepare("
        SELECT id_bon_commande
        FROM bon_commande
        WHERE numero_commande = ?
    ");
    $req->execute([$data["numero_commande"]]);
    $bc = $req->fetch(PDO::FETCH_ASSOC);

    if (!$bc) {
        $sql = "
            INSERT INTO colis (
                bon_commande_id,
                numero_suivi,
                date_reception,
                statut_id,
                commentaire,
                destinataire_id
            )
            VALUES (NULL, ?, NOW(), 3, ?, ?)
        ";
        $req = $this->db->prepare($sql);
        $req->execute([
            $data["numero_suivi"],
            $data["commentaire"],
            $data["destinataire_id"]
        ]);
        return $this->db->lastInsertId();
    }

    $sql = "
        INSERT INTO colis (
            bon_commande_id,
            numero_suivi,
            date_reception,
            statut_id,
            commentaire,
            destinataire_id
        )
        VALUES (?, ?, NOW(), 1, ?, ?)
    ";
    $req = $this->db->prepare($sql);
    $req->execute([
        $bc["id_bon_commande"],
        $data["numero_suivi"],
        $data["commentaire"],
        $data["destinataire_id"]
    ]);
    return $this->db->lastInsertId();

}


    public function getTousLesColis() {
        $sql = "
            SELECT
                c.id_colis,
                c.numero_suivi,
                c.statut_id,
                b.numero_commande,
                d.nom AS departement,
                s.libelle AS statut,
                c.date_reception
            FROM colis c
            JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
            LEFT JOIN departement d ON b.departement_id = d.id_departement
            JOIN statut_colis s ON c.statut_id = s.id_statut
            ORDER BY c.date_reception DESC
        ";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Infos nécessaires pour notifier le demandeur par mail :
     * email + nom du demandeur (créateur du bon de commande), n° commande et n° suivi.
     * Renvoie null si le colis n'est lié à aucune commande/demandeur.
     */
    public function getColisInfosPourMail($id_colis) {
        $sql = "
            SELECT
                c.numero_suivi,
                b.numero_commande,
                u.email    AS demandeur_email,
                u.fullName AS demandeur_nom
            FROM colis c
            JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
            JOIN utilisateur u  ON b.createur_id = u.id_utilisateur
            WHERE c.id_colis = ?
        ";
        $req = $this->db->prepare($sql);
        $req->execute([$id_colis]);
        return $req->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function transfererVersIUT($id_colis) {
        $sql = "
            UPDATE colis
            SET statut_id = 2
            WHERE id_colis = ?
        ";
        $req = $this->db->prepare($sql);
        return $req->execute([$id_colis]);
    }

    public function getColisNonIdentifiesListe() {
        $sql = "
            SELECT
                c.id_colis,
                c.numero_suivi,
                c.date_reception,
                s.libelle AS statut
            FROM colis c
            JOIN statut_colis s ON c.statut_id = s.id_statut
            WHERE c.statut_id = 3
            ORDER BY c.date_reception DESC
        ";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }



    public function getHistorique() {

        $sql = "
            SELECT
                h.date_action,
                c.id_colis,
                c.numero_suivi,
                b.numero_commande,
                d.nom AS departement,
                s.libelle AS statut,
                h.action
            FROM historique_colis h
            JOIN colis c ON h.id_colis = c.id_colis
            LEFT JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
            LEFT JOIN departement d ON b.departement_id = d.id_departement
            LEFT JOIN statut_colis s ON c.statut_id = s.id_statut
            ORDER BY h.date_action DESC
            LIMIT 200
        ";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }


    public function rechercherDestinataireParNom($texte_ocr) {
    $texte_normalise = strtoupper(preg_replace('/\s+/', '', $texte_ocr));
    
    $utilisateurs = $this->db->query("
        SELECT id_utilisateur, fullName, departement_id
        FROM utilisateur
    ")->fetchAll(PDO::FETCH_ASSOC);

    foreach ($utilisateurs as $u) {
        $nom_normalise = strtoupper(preg_replace('/\s+/', '', $u["fullName"]));
        $parties = explode(' ', strtoupper($u["fullName"]));
        $nom_inverse = strtoupper(implode('', array_reverse($parties)));

        if (str_contains($texte_normalise, $nom_normalise) || 
            str_contains($texte_normalise, $nom_inverse)) {
            return $u;
        }
    }
    return null;
}

public function ajouterHistorique($data) {
    $sql = "
        INSERT INTO historique_colis (
            id_colis,
            action,
            utilisateur
        )
        VALUES (?, ?, ?)
    ";
    $req = $this->db->prepare($sql);
    return $req->execute([
        $data["colis_id"],
        $data["action"],
        $data["utilisateur"]
    ]);
}

public function getDepartementNom($id) {
    $req = $this->db->prepare("SELECT nom FROM departement WHERE id_departement = ?");
    $req->execute([$id]);
    return $req->fetchColumn() ?? "Inconnu";
}

}
