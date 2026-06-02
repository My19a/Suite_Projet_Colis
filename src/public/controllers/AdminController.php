<?php
require_once __DIR__ . '/../models/AdminModels.php';
require_once __DIR__ . '/../../lib-tools/Mail/MailService.php';

class AdminController {

    private $model;

    public function __construct() {
        $this->model = new AdminModels();
    }

    public function dashboard() {

        $stats = [
            "utilisateurs" => $this->model->countUtilisateurs(),
            "devis"        => $this->model->countDevis(),
            "bons"         => $this->model->countBonsCommande(),
            "colis"        => $this->model->countColis()
        ];

        $roles = $this->model->countUtilisateursParRole();

        require __DIR__ . '/../views/admin/dashboard.php';
    }

        public function utilisateurs() {

        $utilisateurs = $this->model->getTousLesUtilisateurs();
        $roles        = $this->model->getRoles();
        $departements = $this->model->getDepartements();

        require __DIR__ . '/../views/admin/utilisateurs.php';
    }

    public function updateUtilisateur() {

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            die("Accès invalide");
        }

        $this->model->updateUtilisateur(
            $_POST["id_utilisateur"],
            $_POST["role_id"],
            $_POST["departement_id"] ?: null
        );

        header("Location: /admin/utilisateurs?ok=1");
        exit;
    }

    // ajout par fares
        public function ajouterUtilisateur() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->model->ajouterUtilisateur($_POST);
                header('Location: /admin/utilisateurs');
                exit;
            }
    
            $roles = $this->model->getRoles();
            $departements = $this->model->getDepartements();
    
            require __DIR__ . '/../views/admin/ajouter-utilisateur.php';
        }

    // Liste fournisseurs
    public function fournisseurs() {
        $fournisseurs = $this->model->getFournisseurs();
        require __DIR__ . '/../views/admin/fournisseurs.php';
    }

    // Ajouter
    public function ajouterFournisseur() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->ajouterFournisseur($_POST);
            header('Location: /admin/fournisseurs');
            exit;
        }
    }

    // Modifier
    public function updateFournisseur() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->updateFournisseur(
                $_POST['id_fournisseur'],
                $_POST
            );
            header('Location: /admin/fournisseurs');
            exit;
        }
    }

    public function modifierFournisseur() {

        if (!isset($_GET['id'])) {
            die("ID fournisseur manquant");
        }

        $fournisseur = $this->model->getFournisseurById($_GET['id']);

        if (!$fournisseur) {
            die("Fournisseur introuvable");
        }

        require __DIR__ . '/../views/admin/modifier-fournisseur.php';
    }

    /* ===== DEPARTEMENTS ===== */

    public function departements() {
        $departements = $this->model->getDepartementsAdmin();
        require __DIR__ . '/../views/admin/departements.php';
    }

    public function ajouterDepartement() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->ajouterDepartement(
                $_POST['nom'],
                $_POST['budget_total']
            );
            header("Location: /admin/departements");
            exit;
        }
    }

    public function modifierDepartement() {
        if (!isset($_GET['id'])) {
            die("ID département manquant");
        }

        $departement = $this->model->getDepartementById($_GET['id']);
        require __DIR__ . '/../views/admin/modifier-departement.php';
    }

    public function updateDepartement() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->updateDepartement(
                $_POST['id_departement'],
                $_POST['nom'],
                $_POST['budget_total']
            );
            header("Location: /admin/departements");
            exit;
        }
    }

    public function devis() {

        $stats = $this->model->countDevisParStatut();

        $search = $_GET['q'] ?? null;
        $devis  = $this->model->getTousLesDevis($search);

        require __DIR__ . '/../views/admin/devis.php';
    }

    public function commandes() {

        $stats = $this->model->countCommandesParStatut();

        $search = $_GET['q'] ?? null;
        $commandes = $this->model->getToutesLesCommandes($search);

        require __DIR__ . '/../views/admin/commandes.php';
    }

    /* ===== TEST MAIL ===== */

    public function testMail() {
        $to   = getenv('MAIL_TEST_TO') ?: 'test@yopmail.com';
        $nbUtilisateurs = count($this->model->getTousLesUtilisateurs());

        $subject = '[Test] Suivi Colis – Rapport utilisateurs';
        $body = "
            <h2>Mail de test – Suivi Colis IUT</h2>
            <p>Ce mail confirme que l'envoi automatique fonctionne.</p>
            <p><strong>Nombre d'utilisateurs enregistrés :</strong> {$nbUtilisateurs}</p>
            <p><em>Envoyé depuis l'interface d'administration.</em></p>
        ";

        try {
            MailService::send($to, 'Administrateur', $subject, $body);
            header('Location: /admin/utilisateurs?mail=ok&to=' . urlencode($to));
        } catch (\Exception $e) {
            header('Location: /admin/utilisateurs?mail=error&msg=' . urlencode($e->getMessage()));
        }
        exit;
    }

    /* ===== COLIS ===== */

    public function colis() {

        $stats = $this->model->countColisParStatut();

        $search = $_GET['q'] ?? null;
        $colis = $this->model->getTousLesColisAdmin($search);

        require __DIR__ . '/../views/admin/colis.php';
    }

    
}