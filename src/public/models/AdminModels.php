<?php
require_once __DIR__ . "/Model.php";

class AdminModels {

    private $db;

    public function __construct() {
        $this->db = Model::getModel()->bd;
    }


    public function countUtilisateurs() {
        return $this->db
            ->query("SELECT COUNT(*) FROM utilisateur")
            ->fetchColumn();
    }

    public function countDevis() {
        return $this->db
            ->query("SELECT COUNT(*) FROM devis")
            ->fetchColumn();
    }

    public function countBonsCommande() {
        return $this->db
            ->query("SELECT COUNT(*) FROM bon_commande")
            ->fetchColumn();
    }

    public function countColis() {
        return $this->db
            ->query("SELECT COUNT(*) FROM colis")
            ->fetchColumn();
    }

    public function countDepartements() {
        return $this->db
            ->query("SELECT COUNT(*) FROM departement")
            ->fetchColumn();
    }

    public function countFournisseurs() {
        return $this->db
            ->query("SELECT COUNT(*) FROM fournisseur")
            ->fetchColumn();
    }

    /**
     * Devis encore en cours de traitement (ni acceptés, ni refusés/annulés).
     */
    public function countDevisEnCours() {
        $sql = "
            SELECT COUNT(*) FROM devis
            WHERE statut NOT IN (
                'accepte', 'accepted',
                'refuse', 'rejected', 'rejete', 'rejete_finance',
                'annule', 'cancelled'
            )
        ";
        return $this->db->query($sql)->fetchColumn();
    }


    public function countUtilisateursParRole() {
        $sql = "
            SELECT r.libelle, COUNT(u.id_utilisateur) AS total
            FROM role r
            LEFT JOIN utilisateur u ON u.role_id = r.id_role
            GROUP BY r.libelle
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ===== UTILISATEURS ===== */

    public function getTousLesUtilisateurs() {
        $sql = "
            SELECT
                u.id_utilisateur,
                u.uid_cas,
                u.fullName,
                u.email,
                r.libelle AS role,
                u.role_id,
                d.nom AS departement,
                u.departement_id
            FROM utilisateur u
            JOIN role r ON u.role_id = r.id_role
            LEFT JOIN departement d ON u.departement_id = d.id_departement
            ORDER BY u.fullName
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRoles() {
        return $this->db->query("
            SELECT id_role, libelle
            FROM role
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDepartements() {
        return $this->db->query("
            SELECT id_departement, nom
            FROM departement
            ORDER BY nom
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ajouterUtilisateur($data) {
        $sql = "
            INSERT INTO utilisateur (fullName, email, uid_cas, role_id, departement_id, access_token_api_cas)
            VALUES (?, ?, ?, ?, ?, ?)
        ";
        $req = $this->db->prepare($sql);
        $req->execute([
            $data['fullName'],
            $data['email'],
            $data['uid_cas'],
            $data['role_id'],
            $data['departement_id'] ?: null,
            ''
        ]);
    }

    public function getUtilisateurById($id) {
        $sql = "
            SELECT id_utilisateur, uid_cas, fullName, email, role_id, departement_id
            FROM utilisateur
            WHERE id_utilisateur = ?
        ";
        $req = $this->db->prepare($sql);
        $req->execute([$id]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUtilisateur($id, $data) {
        $sql = "
            UPDATE utilisateur
            SET fullName = ?, email = ?, uid_cas = ?, role_id = ?, departement_id = ?
            WHERE id_utilisateur = ?
        ";
        $req = $this->db->prepare($sql);
        $req->execute([
            $data['fullName'],
            $data['email'],
            $data['uid_cas'],
            $data['role_id'],
            $data['departement_id'] ?: null,
            $id
        ]);
    }

    public function supprimerUtilisateur($id) {
        $req = $this->db->prepare("DELETE FROM utilisateur WHERE id_utilisateur = ?");
        $req->execute([$id]);
    }

    // Récupérer tous les fournisseurs
    public function getFournisseurs() {
        return $this->db->query("
            SELECT *
            FROM fournisseur
            ORDER BY nom
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ajouter
    public function ajouterFournisseur($data) {
        $sql = "
            INSERT INTO fournisseur (nom, contact_nom, contact_email, contact_telephone)
            VALUES (?, ?, ?, ?)
        ";
        $req = $this->db->prepare($sql);
        $req->execute([
            $data['nom'],
            $data['contact_nom'],
            $data['contact_email'],
            $data['contact_telephone']
        ]);
    }

    // Modifier
    public function updateFournisseur($id, $data) {
        $sql = "
            UPDATE fournisseur
            SET nom = ?, contact_nom = ?, contact_email = ?, contact_telephone = ?
            WHERE id_fournisseur = ?
        ";
        $req = $this->db->prepare($sql);
        $req->execute([
            $data['nom'],
            $data['contact_nom'],
            $data['contact_email'],
            $data['contact_telephone'],
            $id
        ]);
    }

    public function getFournisseurById($id) {
        $req = $this->db->prepare("
            SELECT *
            FROM fournisseur
            WHERE id_fournisseur = ?
        ");
        $req->execute([$id]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    public function supprimerFournisseur($id) {
        $req = $this->db->prepare("DELETE FROM fournisseur WHERE id_fournisseur = ?");
        $req->execute([$id]);
    }

    /* ===== DEPARTEMENTS ===== */

    public function getDepartementsAdmin() {
        return $this->db->query("
            SELECT id_departement, nom, budget_total, budget_utilise
            FROM departement
            ORDER BY nom
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ajouterDepartement($nom, $budget) {
        $req = $this->db->prepare("
            INSERT INTO departement (nom, budget_total, budget_utilise)
            VALUES (?, ?, 0)
        ");
        $req->execute([$nom, $budget]);
    }

    public function getDepartementById($id) {
        $req = $this->db->prepare("
            SELECT *
            FROM departement
            WHERE id_departement = ?
        ");
        $req->execute([$id]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    public function updateDepartement($id, $nom, $budget) {
        $req = $this->db->prepare("
            UPDATE departement
            SET nom = ?, budget_total = ?
            WHERE id_departement = ?
        ");
        $req->execute([$nom, $budget, $id]);
    }

    public function supprimerDepartement($id) {
        $req = $this->db->prepare("DELETE FROM departement WHERE id_departement = ?");
        $req->execute([$id]);
    }

    public function countDevisParStatut() {
        $sql = "
            SELECT statut, COUNT(*) AS total
            FROM devis
            GROUP BY statut
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tous les devis (avec filtres)
    public function getTousLesDevis($search = null) {

        $sql = "
            SELECT
                d.id_devis,
                d.objet,
                d.montant_estime,
                d.statut,
                d.date_demande,
                dep.nom AS departement,
                f.nom AS fournisseur
            FROM devis d
            LEFT JOIN utilisateur u ON d.createur_id = u.id_utilisateur
            LEFT JOIN departement dep ON u.departement_id = dep.id_departement
            LEFT JOIN fournisseur f ON d.fournisseur_id = f.id_fournisseur
        ";

        $params = [];

        if ($search) {
            $sql .= "
                WHERE d.objet LIKE ?
                OR d.statut LIKE ?
                OR dep.nom LIKE ?
                OR f.nom LIKE ?
            ";
            $search = "%$search%";
            $params = [$search, $search, $search, $search];
        }

        $sql .= " ORDER BY d.date_demande DESC";

        $req = $this->db->prepare($sql);
        $req->execute($params);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }


    public function countCommandesParStatut() {
        $sql = "
            SELECT statut, COUNT(*) AS total
            FROM bon_commande
            GROUP BY statut
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tous les bons de commande (+ recherche)
    public function getToutesLesCommandes($search = null) {

        $sql = "
            SELECT
                b.id_bon_commande,
                b.numero_commande,
                b.date_commande,
                b.montant_estime,
                b.statut,
                d.nom AS departement,
                f.nom AS fournisseur
            FROM bon_commande b
            LEFT JOIN departement d ON b.departement_id = d.id_departement
            LEFT JOIN fournisseur f ON b.fournisseur_id = f.id_fournisseur
        ";

        $params = [];

        if ($search) {
            $sql .= "
                WHERE b.numero_commande LIKE ?
                OR b.statut LIKE ?
                OR d.nom LIKE ?
                OR f.nom LIKE ?
            ";
            $search = "%$search%";
            $params = [$search, $search, $search, $search];
        }

        $sql .= " ORDER BY b.date_commande DESC";

        $req = $this->db->prepare($sql);
        $req->execute($params);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }


    public function countColisParStatut() {
        $sql = "
            SELECT s.libelle AS statut, COUNT(*) AS total
            FROM colis c
            JOIN statut_colis s ON c.statut_id = s.id_statut
            GROUP BY s.libelle
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countColisParDepartement() {
        $sql = "
            SELECT COALESCE(d.nom, 'Non identifié') AS departement, COUNT(*) AS total
            FROM colis c
            LEFT JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
            LEFT JOIN departement d ON b.departement_id = d.id_departement
            GROUP BY departement
            ORDER BY total DESC
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tous les colis + recherche
    public function getTousLesColisAdmin($search = null) {

        $sql = "
            SELECT
                c.id_colis,
                c.numero_suivi,
                c.date_reception,
                c.date_retrait,
                b.numero_commande,
                d.nom AS departement,
                s.libelle AS statut
            FROM colis c
            LEFT JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
            LEFT JOIN departement d ON b.departement_id = d.id_departement
            JOIN statut_colis s ON c.statut_id = s.id_statut
        ";

        $params = [];

        if ($search) {
            $sql .= "
                WHERE c.numero_suivi LIKE ?
                OR b.numero_commande LIKE ?
                OR d.nom LIKE ?
                OR s.libelle LIKE ?
            ";
            $search = "%$search%";
            $params = [$search, $search, $search, $search];
        }

        $sql .= " ORDER BY c.date_reception DESC";

        $req = $this->db->prepare($sql);
        $req->execute($params);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }


    /* ===== APERÇUS TABLEAU DE BORD =====
       Petites listes limitées pour les cartes du dashboard admin.
       $limite est un entier interne : injecté directement (jamais une saisie utilisateur)
       pour éviter les soucis de binding de LIMIT en mode PDO émulé. */

    public function getDerniersUtilisateurs($limite = 5) {
        $limite = (int) $limite;
        $sql = "
            SELECT
                u.id_utilisateur,
                u.fullName,
                u.email,
                r.libelle AS role,
                d.nom AS departement
            FROM utilisateur u
            JOIN role r ON u.role_id = r.id_role
            LEFT JOIN departement d ON u.departement_id = d.id_departement
            ORDER BY u.id_utilisateur DESC
            LIMIT {$limite}
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getApercuDepartements($limite = 5) {
        $limite = (int) $limite;
        $sql = "
            SELECT id_departement, nom, budget_total, budget_utilise
            FROM departement
            ORDER BY nom
            LIMIT {$limite}
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDerniersDevis($limite = 5) {
        $limite = (int) $limite;
        $sql = "
            SELECT
                d.id_devis,
                d.objet,
                d.montant_estime,
                d.statut,
                d.date_demande,
                f.nom AS fournisseur
            FROM devis d
            LEFT JOIN fournisseur f ON d.fournisseur_id = f.id_fournisseur
            ORDER BY d.date_demande DESC, d.id_devis DESC
            LIMIT {$limite}
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDerniersColis($limite = 5) {
        $limite = (int) $limite;
        $sql = "
            SELECT
                c.id_colis,
                c.numero_suivi,
                c.date_reception,
                d.nom AS departement,
                s.libelle AS statut
            FROM colis c
            LEFT JOIN bon_commande b ON c.bon_commande_id = b.id_bon_commande
            LEFT JOIN departement d ON b.departement_id = d.id_departement
            JOIN statut_colis s ON c.statut_id = s.id_statut
            ORDER BY c.date_reception DESC, c.id_colis DESC
            LIMIT {$limite}
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDernieresCommandes($limite = 5) {
        $limite = (int) $limite;
        $sql = "
            SELECT
                b.id_bon_commande,
                b.numero_commande,
                b.date_commande,
                b.montant_estime,
                b.statut,
                d.nom AS departement,
                f.nom AS fournisseur
            FROM bon_commande b
            LEFT JOIN departement d ON b.departement_id = d.id_departement
            LEFT JOIN fournisseur f ON b.fournisseur_id = f.id_fournisseur
            ORDER BY b.date_commande DESC, b.id_bon_commande DESC
            LIMIT {$limite}
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getApercuFournisseurs($limite = 5) {
        $limite = (int) $limite;
        $sql = "
            SELECT id_fournisseur, nom, contact_nom, contact_email
            FROM fournisseur
            ORDER BY nom
            LIMIT {$limite}
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ===== CONSOLE SQL ===== */

    // Liste de toutes les tables de la base, avec leur nombre de lignes.
    public function getTablesBd() {
        $tables = $this->db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

        $resultat = [];
        foreach ($tables as $table) {
            $nb = null;
            try {
                $nb = (int) $this->db
                    ->query("SELECT COUNT(*) FROM `" . str_replace("`", "``", $table) . "`")
                    ->fetchColumn();
            } catch (\PDOException $e) {
                // Vue ou table inaccessible : on garde null
            }
            $resultat[] = ["nom" => $table, "lignes" => $nb];
        }
        return $resultat;
    }

    // Execute une requete SQL brute (sans filtre) et renvoie un descriptif du resultat.
    // Reservee a l'administrateur : aucune validation n'est appliquee.
    public function executerSqlBrut($sql) {
        $debut = microtime(true);
        $stmt  = $this->db->query($sql);
        $duree = round((microtime(true) - $debut) * 1000, 2);

        // Une requete qui renvoie des colonnes (SELECT / SHOW / DESCRIBE / EXPLAIN...)
        if ($stmt->columnCount() > 0) {
            $lignes   = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $colonnes = !empty($lignes) ? array_keys($lignes[0]) : [];

            // Cas d'une table vide : on recupere quand meme l'entete des colonnes
            if (empty($colonnes)) {
                for ($i = 0; $i < $stmt->columnCount(); $i++) {
                    $meta = $stmt->getColumnMeta($i);
                    $colonnes[] = $meta["name"] ?? ("col" . $i);
                }
            }

            return [
                "type"     => "resultset",
                "colonnes" => $colonnes,
                "lignes"   => $lignes,
                "nbLignes" => count($lignes),
                "duree"    => $duree,
            ];
        }

        // Requete d'ecriture (INSERT / UPDATE / DELETE / DDL...)
        return [
            "type"       => "execution",
            "nbAffectes" => $stmt->rowCount(),
            "duree"      => $duree,
        ];
    }
}
