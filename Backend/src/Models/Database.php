<?php
class Model
{
    private $bd;                 
    private static $instance = null; 

    // utilisation de try/catch car ca permet de mieux comprendre l'erreur sans pour autant avoir à relancer le script
    private function __construct()
    {
         include "Utils/credentials.php";

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

    
    public function getNotifications()
    {
        $stmt = $this->bd->query("SELECT * FROM notification");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
