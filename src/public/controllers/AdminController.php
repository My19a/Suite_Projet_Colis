<?php
require_once __DIR__ . '/../models/AdminModels.php';
require_once __DIR__ . '/../../lib-tools/Mail/MailService.php';
//Controller pour la partie admin (tableau de bord, gestion utilisateurs, devis, etc.)
class AdminController {

    private $model;

    public function __construct() {

        $this->model = new AdminModels();
    
        }

    public function dashboard() {

        $stats = [
            "utilisateurs"   => $this->model->countUtilisateurs(),
            "departements"   => $this->model->countDepartements(),
            "devis_en_cours" => $this->model->countDevisEnCours(),
            "devis"          => $this->model->countDevis(),
            "bons"           => $this->model->countBonsCommande(),
            "colis"          => $this->model->countColis(),
            "fournisseurs"   => $this->model->countFournisseurs(),
        ];

        $roles = $this->model->countUtilisateursParRole();
//
        // Aperçus pour les cartes du tableau de bord
        $apercuUtilisateurs = $this->model->getDerniersUtilisateurs(5);
        $apercuDepartements = $this->model->getApercuDepartements(5);
        $apercuDevis        = $this->model->getDerniersDevis(5);
        $apercuColis        = $this->model->getDerniersColis(5);
        $apercuCommandes    = $this->model->getDernieresCommandes(5);
        $apercuFournisseurs = $this->model->getApercuFournisseurs(5);

        // Données des mini-graphiques
        $colisParDepartement = $this->model->countColisParDepartement();
        $colisParStatut      = $this->model->countColisParStatut();

        require __DIR__ . '/../views/admin/dashboard.php';
    }

        public function utilisateurs() {

        $utilisateurs = $this->model->getTousLesUtilisateurs();
        $roles        = $this->model->getRoles();
        $departements = $this->model->getDepartements();

        require __DIR__ . '/../views/admin/utilisateurs.php';
    }

    public function ajouterUtilisateur() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->ajouterUtilisateur($_POST);
            header('Location: /admin/utilisateurs?ok=1');
            exit;
        }


        $roles        = $this->model->getRoles();
        $departements = $this->model->getDepartements();


        require __DIR__ . '/../views/admin/ajouter-utilisateur.php';
    }

    public function modifierUtilisateur() {
        if (!isset($_GET['id'])) {
            die("ID utilisateur manquant");
        }

        $utilisateur = $this->model->getUtilisateurById($_GET['id']);

        if (!$utilisateur) {
            die("Utilisateur introuvable");
        }

        $roles        = $this->model->getRoles();
        $departements = $this->model->getDepartements();

        require __DIR__ . '/../views/admin/modifier-utilisateur.php';
    }

    public function updateUtilisateur() {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            die("Accès invalide");
        }

        $this->model->updateUtilisateur($_POST["id_utilisateur"], $_POST);

        header("Location: /admin/utilisateurs?ok=1");
        exit;
    }

    public function supprimerUtilisateur() {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            die("Accès invalide");
        }


        try {
            $this->model->supprimerUtilisateur($_POST["id_utilisateur"]);
            header("Location: /admin/utilisateurs?deleted=1");
        } catch (\PDOException $e) {
            header("Location: /admin/utilisateurs?error=fk");
        }
        exit;
    }

    // Liste fournisseurs
    public function fournisseurs() {
        $fournisseurs = $this->model->getFournisseurs();
        require __DIR__ . '/../views/admin/fournisseurs.php';
  //
        }

    // Ajouter
    public function ajouterFournisseur() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->ajouterFournisseur($_POST);
            header('Location: /admin/fournisseurs');
            exit;
        }

        require __DIR__ . '/../views/admin/ajouter-fournisseur.php';
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

    public function supprimerFournisseur() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die("Accès invalide");
        }
        try {
            $this->model->supprimerFournisseur($_POST['id_fournisseur']);
            header('Location: /admin/fournisseurs?deleted=1');
        } catch (\PDOException $e) {
            header('Location: /admin/fournisseurs?error=fk');
        }
        exit;
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

        require __DIR__ . '/../views/admin/ajouter-departement.php';
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

    public function supprimerDepartement() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die("Accès invalide");
        }
        try {
            $this->model->supprimerDepartement($_POST['id_departement']);
            header('Location: /admin/departements?deleted=1');
        } catch (\PDOException $e) {
            header('Location: /admin/departements?error=fk');
        }
        exit;
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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die("Accès invalide");
        }

        // Adresse saisie au clic, avec repli sur la valeur du .env
        $to = trim($_POST['to'] ?? '') ?: (getenv('MAIL_TEST_TO') ?: '');

        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            header('Location: /admin/dashboard?mail=error&msg=' . urlencode('Adresse email invalide.'));
            exit;
        }

        $nbUtilisateurs = $this->model->countUtilisateurs();

        $subject = '[Test] Suivi Colis – Notification administrateur';
        $body = "
            <h2>Mail de test – Suivi Colis IUT</h2>
            <p>Ce mail confirme que l'envoi automatique fonctionne.</p>
            <p><strong>Nombre d'utilisateurs enregistrés :</strong> {$nbUtilisateurs}</p>
            <p><em>Envoyé depuis le tableau de bord administrateur.</em></p>
        ";

        try {
            MailService::send($to, 'Administrateur', $subject, $body);
            header('Location: /admin/dashboard?mail=ok&to=' . urlencode($to));
        } catch (\Exception $e) {
            header('Location: /admin/dashboard?mail=error&msg=' . urlencode($e->getMessage()));
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