<?php

class Model
{
    private static $instance = null;
    public $bd;

    private function __construct()
    {
        // Importation de la config
        require_once __DIR__ . '/../../config/database.php';

        // Connexion BD
        try {
            $this->bd = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ]);
        } catch (PDOException $e) {
            die("❌ Erreur BD : " . $e->getMessage());
        }
    }

    public static function getModel()
    {
        if (self::$instance === null) {
            self::$instance = new Model();
        }
        return self::$instance;
    }

    // Pour préparer des requêtes SQL
    public function prepare($sql)
    {
        return $this->bd->prepare($sql);
    }
}
