<?php
class Model
{
    private $bd;                 
    private static $instance = null; 

    // utilisation de try/catch car ca permet de mieux comprendre l'erreur sans pour autant avoir à relancer le script
    private function __construct()
    {
        require_once __DIR__ . "/../../Utils/credentials.php";



        try {
            $this->bd = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

 // Méthode permettant de récupérer un modèle car le constructeur est privé
    public static function getModel()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

// Les fonctions qui permettent de recupérer les informations de chaque table afin de pouvoir les récupérer facilement
    public function getDepartements()
    {
        $stmt = $this->bd->query("SELECT * FROM departement");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
    public function getUtilisateurs()
    {
        $stmt = $this->bd->query("SELECT * FROM utilisateur");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function getFournisseurs()
    {
        $stmt = $this->bd->query("SELECT * FROM fournisseur");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function getDevis()
    {
        $stmt = $this->bd->query("SELECT * FROM devis");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function getBonCommandes()
    {
        $stmt = $this->bd->query("SELECT * FROM bon_commande");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function getStatutsColis()
    {
        $stmt = $this->bd->query("SELECT * FROM statut_colis");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function getColis()
    {
        $stmt = $this->bd->query("SELECT * FROM colis");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

     public function getColisRecusAujourdhui()
    {
        $sql = "SELECT COUNT(*) as count FROM colis 
                WHERE DATE(date_reception) = CURDATE()";
        $stmt = $this->bd->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    // Compte les colis en cours de transfert
    public function getColisTransferes()
    {
        $sql = "SELECT COUNT(*) as count FROM colis c
                JOIN statut_colis s ON c.statut_id = s.id_statut
                WHERE s.libelle = 'transfere_iut'";
        $stmt = $this->bd->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    // Compte les colis en attente
    public function getColisEnAttente()
    {
        $sql = "SELECT COUNT(*) as count FROM colis c
                JOIN statut_colis s ON c.statut_id = s.id_statut
                WHERE s.libelle = 'en_attente'";
        $stmt = $this->bd->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    // Récupère tous les colis reçus à l'université (à traiter)
    public function getColisRecusUniv()
    {
        $sql = "SELECT c.*, 
                bc.numero_commande, 
                u.fullName as destinataire,
                d.nom as departement,
                s.libelle as statut
                FROM colis c
                JOIN bon_commande bc ON c.bon_commande_id = bc.id_bon_commande
                LEFT JOIN utilisateur u ON c.destinataire_id = u.id_utilisateur
                LEFT JOIN departement d ON u.departement_id = d.id_departement
                JOIN statut_colis s ON c.statut_id = s.id_statut
                WHERE s.libelle IN ('recu_universite', 'en_attente')
                ORDER BY c.date_reception DESC";
        $stmt = $this->bd->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère l'historique des colis transférés
    public function getColisTransferesHistorique()
    {
        $sql = "SELECT c.*, 
                bc.numero_commande,
                u.fullName as destinataire,
                d.nom as departement,
                s.libelle as statut,
                c.date_retrait
                FROM colis c
                JOIN bon_commande bc ON c.bon_commande_id = bc.id_bon_commande
                LEFT JOIN utilisateur u ON c.destinataire_id = u.id_utilisateur
                LEFT JOIN departement d ON u.departement_id = d.id_departement
                JOIN statut_colis s ON c.statut_id = s.id_statut
                WHERE s.libelle = 'transfere_iut'
                ORDER BY c.date_retrait DESC
                LIMIT 50";
        $stmt = $this->bd->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Transfère un colis vers l'IUT
    public function transfererColis($idColis, $userId)
    {
        try {
            // Récupère l'ID du statut "transfere_iut"
            $sqlStatut = "SELECT id_statut FROM statut_colis WHERE libelle = 'transfere_iut'";
            $stmt = $this->bd->query($sqlStatut);
            $statut = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Met à jour le colis
            $sql = "UPDATE colis 
                    SET statut_id = :statut_id, 
                        date_retrait = NOW(),
                        receptionne_par = :user_id
                    WHERE id_colis = :id_colis";
            $stmt = $this->bd->prepare($sql);
            $stmt->execute([
                'statut_id' => $statut['id_statut'],
                'user_id' => $userId,
                'id_colis' => $idColis
            ]);
            
            return true;
        } catch (PDOException $e) {
            error_log("Erreur transfert colis: " . $e->getMessage());
            return false;
        }
    }


    public function getNotifications()
    {
        $stmt = $this->bd->query("SELECT * FROM notification");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
