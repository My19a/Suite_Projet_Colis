<?php

class Model
{
    private static $instance = null;
    public $bd;

    private function __construct()
    {
        require_once __DIR__ . '/../../config/database.php';

        try {
            $this->bd = new \PDO($dsn, $user, $password, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ]);
        } catch (\PDOException $e) {
            if (getenv('APP_ENV') === 'development') {
                die("❌ Erreur BD : " . $e->getMessage());
            } else {
                error_log("Erreur BD : " . $e->getMessage());
                die("❌ Une erreur technique est survenue. Contactez l'administrateur.");
            }
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
