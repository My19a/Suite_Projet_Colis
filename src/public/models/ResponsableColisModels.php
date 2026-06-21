<?php
require_once __DIR__ . '/Model.php';

class ResponsableColisModels {

    private $db;

    public function __construct() {
        $this->db = Model::getModel()->bd;
    }

    public function getColisRecus() {
        return $this->db->query("SELECT COUNT(*) FROM colis")
            ->fetchColumn();
    }

    public function getBonsCommandeTotal() {
        return $this->db->query("SELECT COUNT(*) FROM bon_commande")
            ->fetchColumn();
    }

    public function getBonsCommandeSansColis() {
        return $this->db->query("
            SELECT COUNT(DISTINCT b.id_bon_commande)
            FROM bon_commande b
            JOIN colis c ON c.bon_commande_id = b.id_bon_commande
            WHERE c.statut_id = 3
        ")->fetchColumn();
    }

    public function getColisATransferer() {
        return $this->db->query("SELECT COUNT(*) FROM colis WHERE statut_id = 1")
            ->fetchColumn();
    }

    public function getColisTransferes() {
        return $this->db->query("SELECT COUNT(*) FROM colis WHERE statut_id = 2")
            ->fetchColumn();
    }

    public function getDerniersColis() {
        return $this->db->query("
            SELECT
                c.id_colis,
                c.numero_suivi,
                c.date_reception,
                b.numero_commande,
                u.fullName AS demandeur,
                d.nom AS departement,
                s.libelle AS statut
            FROM colis c
            JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
            LEFT JOIN departement d ON b.departement_id = d.id_departement
            LEFT JOIN utilisateur u ON b.createur_id = u.id_utilisateur
            JOIN statut_colis s ON c.statut_id = s.id_statut
            ORDER BY c.date_reception DESC
            LIMIT 10
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBonsCommandeAReceptionner() {
        return $this->db->query("
            SELECT
                b.id_bon_commande,
                b.numero_commande,
                b.date_commande,
                b.date_estimee_livraison,
                b.statut,
                d.nom AS departement,
                f.nom AS fournisseur,
                u.fullName AS demandeur,
                COUNT(c.id_colis) AS colis_total,
                SUM(CASE WHEN c.statut_id = 3 THEN 1 ELSE 0 END) AS colis_a_receptionner,
                SUM(CASE WHEN c.statut_id IN (1, 2, 4) THEN 1 ELSE 0 END) AS colis_deja_recus
            FROM bon_commande b
            JOIN colis c ON c.bon_commande_id = b.id_bon_commande
            LEFT JOIN departement d ON b.departement_id = d.id_departement
            LEFT JOIN fournisseur f ON b.fournisseur_id = f.id_fournisseur
            LEFT JOIN utilisateur u ON b.createur_id = u.id_utilisateur
            GROUP BY
                b.id_bon_commande,
                b.numero_commande,
                b.date_commande,
                b.date_estimee_livraison,
                b.statut,
                d.nom,
                f.nom,
                u.fullName
            HAVING colis_a_receptionner > 0
            ORDER BY COALESCE(b.date_estimee_livraison, b.date_commande) ASC, b.id_bon_commande DESC
            LIMIT 10
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCommandeReceptionDetails($id_bon_commande) {
        $req = $this->db->prepare("
            SELECT
                b.*,
                d.objet,
                dep.nom AS departement,
                f.nom AS fournisseur,
                u.fullName AS demandeur
            FROM bon_commande b
            LEFT JOIN devis d ON b.devis_id = d.id_devis
            LEFT JOIN departement dep ON b.departement_id = dep.id_departement
            LEFT JOIN fournisseur f ON b.fournisseur_id = f.id_fournisseur
            LEFT JOIN utilisateur u ON b.createur_id = u.id_utilisateur
            WHERE b.id_bon_commande = ?
        ");
        $req->execute([$id_bon_commande]);
        $commande = $req->fetch(PDO::FETCH_ASSOC);
        if (!$commande) {
            return null;
        }

        $reqColis = $this->db->prepare("
            SELECT
                c.id_colis,
                c.numero_suivi,
                c.commentaire,
                c.date_reception,
                c.statut_id,
                s.libelle AS statut
            FROM colis c
            JOIN statut_colis s ON c.statut_id = s.id_statut
            WHERE c.bon_commande_id = ?
            ORDER BY c.id_colis ASC
        ");
        $reqColis->execute([$id_bon_commande]);
        $commande["colis"] = $reqColis->fetchAll(PDO::FETCH_ASSOC);
        return $commande;
    }

    public function getBonCommandeParNumero($numero_commande) {
        $req = $this->db->prepare("
            SELECT
                b.id_bon_commande,
                b.numero_commande,
                b.createur_id,
                d.nom AS departement
            FROM bon_commande b
            LEFT JOIN departement d ON b.departement_id = d.id_departement
            WHERE b.numero_commande = ?
        ");
        $req->execute([$numero_commande]);
        return $req->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function ajouterColisUniversite($data) {
        $bc = $this->getBonCommandeParNumero($data["numero_commande"]);

        if (!$bc) {
            return null;
        }

        $sql = "
            INSERT INTO colis (
                bon_commande_id,
                numero_suivi,
                date_reception,
                statut_id,
                commentaire,
                destinataire_id,
                receptionne_par
            )
            VALUES (?, ?, NOW(), 1, ?, ?, ?)
        ";
        $req = $this->db->prepare($sql);
        $req->execute([
            $bc["id_bon_commande"],
            $data["numero_suivi"],
            $data["commentaire"],
            $data["destinataire_id"] ?? $bc["createur_id"],
            $data["receptionne_par"] ?? null
        ]);
        return $this->db->lastInsertId();
    }


    public function getTousLesColis() {
        $sql = "
            SELECT
                c.id_colis,
                c.numero_suivi,
                c.statut_id,
                c.commentaire,
                b.numero_commande,
                d.nom AS departement,
                s.libelle AS statut,
                c.date_reception
            FROM colis c
            JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
            LEFT JOIN departement d ON b.departement_id = d.id_departement
            JOIN statut_colis s ON c.statut_id = s.id_statut
            WHERE c.statut_id = 1
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

    /**
     * Cherche un colis EN ATTENTE (statut 3) dont le n° de suivi correspond
     * ET dont le demandeur (créateur du BC) correspond au nom fourni (OCR ou manuel).
     * Renvoie [id_colis, bon_commande_id, numero_commande, demandeur] ou null.
     */
    public function trouverColisEnAttente($numero_suivi, $nom_demandeur) {
        $req = $this->db->prepare("
            SELECT c.id_colis, c.bon_commande_id, b.numero_commande, u.fullName AS demandeur
            FROM colis c
            JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
            JOIN utilisateur u ON b.createur_id = u.id_utilisateur
            WHERE c.statut_id = 3 AND c.numero_suivi = ?
        ");
        $req->execute([trim($numero_suivi)]);

        $cible = strtoupper(preg_replace('/\s+/', '', $nom_demandeur));
        foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $nom = strtoupper(preg_replace('/\s+/', '', $row["demandeur"]));
            $inverse = strtoupper(implode('', array_reverse(explode(' ', strtoupper($row["demandeur"])))));
            if ($cible !== '' && ($cible === $nom || $cible === $inverse
                || str_contains($nom, $cible) || str_contains($cible, $nom))) {
                return $row;
            }
        }
        return null;
    }

    /**
     * Recalcule le statut d'un bon de commande à partir de l'état de SES colis,
     * en suivant le flux : En attente -> Livré à l'université -> Transféré à l'IUT -> Réceptionné.
     * Le BC prend l'état du colis le MOINS avancé (il n'est "Transféré" que si tous le sont, etc.).
     */
    public function recalculerStatutBon($id_bon_commande) {
        $req = $this->db->prepare("SELECT statut_id FROM colis WHERE bon_commande_id = ?");
        $req->execute([$id_bon_commande]);
        $statuts = array_map('intval', array_column($req->fetchAll(PDO::FETCH_ASSOC), 'statut_id'));
        if (!$statuts) {
            return;
        }
        if (in_array(3, $statuts))      $slug = 'en_attente';
        elseif (in_array(1, $statuts))  $slug = 'recu_universite';
        elseif (in_array(2, $statuts))  $slug = 'transfere_iut';
        else                            $slug = 'livre';

        $u = $this->db->prepare("UPDATE bon_commande SET statut = ? WHERE id_bon_commande = ?");
        $u->execute([$slug, $id_bon_commande]);
    }

    /** Recalcule le statut du bon de commande auquel appartient un colis donné. */
    public function recalculerStatutBonParColis($id_colis) {
        $req = $this->db->prepare("SELECT bon_commande_id FROM colis WHERE id_colis = ?");
        $req->execute([$id_colis]);
        $bc = $req->fetchColumn();
        if ($bc) {
            $this->recalculerStatutBon((int) $bc);
        }
    }

    /** IDs des colis encore EN ATTENTE (statut 3) d'un bon de commande. */
    public function getColisIdsEnAttenteParCommande($bon_commande_id) {
        $req = $this->db->prepare("SELECT id_colis FROM colis WHERE bon_commande_id = ? AND statut_id = 3");
        $req->execute([$bon_commande_id]);
        return array_map('intval', array_column($req->fetchAll(PDO::FETCH_ASSOC), 'id_colis'));
    }

    public function transfererVersIUT($id_colis) {
        $sql = "
            UPDATE colis
            SET statut_id = 2
            WHERE id_colis = ? AND statut_id = 1
        ";
        $req = $this->db->prepare($sql);
        return $req->execute([$id_colis]);
    }

    public function receptionnerColis(array $ids_colis, ?int $user_id = null) {
        if (empty($ids_colis)) {
            return 0;
        }

        $ids_colis = array_values(array_unique(array_map('intval', $ids_colis)));
        $placeholders = implode(',', array_fill(0, count($ids_colis), '?'));
        $sql = "
            UPDATE colis
            SET statut_id = 1,
                date_reception = NOW(),
                receptionne_par = ?
            WHERE statut_id = 3
              AND id_colis IN ($placeholders)
        ";
        $req = $this->db->prepare($sql);
        $req->execute(array_merge([$user_id], $ids_colis));
        return $req->rowCount();
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
                h.action,
                COALESCE(resp.fullName, h.utilisateur) AS responsable,
                dem.fullName AS demandeur
            FROM historique_colis h
            JOIN colis c ON h.id_colis = c.id_colis
            LEFT JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
            LEFT JOIN departement d ON b.departement_id = d.id_departement
            LEFT JOIN statut_colis s ON c.statut_id = s.id_statut
            LEFT JOIN utilisateur resp ON c.receptionne_par = resp.id_utilisateur
            LEFT JOIN utilisateur dem ON b.createur_id = dem.id_utilisateur
            WHERE h.action NOT LIKE '%universit%'
            ORDER BY h.date_action DESC
            LIMIT 200
        ";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }


    public function rechercherDestinataireParNom($texte_ocr) {
    $texte_normalise = strtoupper(preg_replace('/\s+/', '', (string) $texte_ocr));
    if ($texte_normalise === '') {
        return null;
    }

    $utilisateurs = $this->db->query("
        SELECT id_utilisateur, fullName, departement_id
        FROM utilisateur
    ")->fetchAll(PDO::FETCH_ASSOC);

    foreach ($utilisateurs as $u) {
        $parties = explode(' ', strtoupper(trim($u["fullName"])));
        $nom_normalise = strtoupper(preg_replace('/\s+/', '', $u["fullName"]));
        $nom_inverse = strtoupper(implode('', array_reverse($parties)));

        // 1) OCR : le texte (long) contient le nom complet (ou inversé).
        if (str_contains($texte_normalise, $nom_normalise) ||
            str_contains($texte_normalise, $nom_inverse)) {
            return $u;
        }

        // 2) Saisie partielle (>= 2 caractères) : le texte tapé est un morceau du nom...
        if (strlen($texte_normalise) >= 2 &&
            (str_contains($nom_normalise, $texte_normalise) ||
             str_contains($nom_inverse, $texte_normalise))) {
            return $u;
        }

        // 3) ...ou le début d'un prénom / nom isolé (ex. "jacq" -> "Jacques Dupont").
        foreach ($parties as $partie) {
            if (strlen($texte_normalise) >= 2 && str_starts_with($partie, $texte_normalise)) {
                return $u;
            }
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
