<?php
require_once __DIR__ . '/../models/PostalIutModels.php';

class PostalIutController {

    private $model;

    public function __construct() {
        $this->model = new PostalIutModels();
    }
//
    public function dashboard() {

        $stats = [
            "recus"           => $this->model->getColisRecusIUT(),
            "en_attente"     => $this->model->getColisEnAttente(),
            "retires"        => $this->model->getColisRetires(),
            "non_identifies" => $this->model->getColisNonIdentifies()
        ];

        $colis = $this->model->getDerniersColis();

        require __DIR__ . '/../views/postal-iut/dashboard.php';
    }
//

    public function colisRecus() {

        $colis = $this->model->getColisRecus();

        require __DIR__ . '/../views/postal-iut/colis-recus.php';
    }


    public function detailsColis() {

        if (!isset($_GET["id"])) {
            die("ID colis manquant");
        }

        $id_colis = intval($_GET["id"]);

        $colis = $this->model->getColisById($id_colis);

        if (!$colis) {
            die("Colis introuvable");
        }

        $historique = $this->model->getHistoriqueColis($id_colis);

        require __DIR__ . '/../views/postal-iut/colis-details.php';
    }


    public function colisRemis() {

        // Colis avec statut "retiré"
        $colis = $this->model->getColisRemis();

        require __DIR__ . '/../views/postal-iut/colis-remis.php';
    }

    public function rechercheColis() {

        $resultats = [];

        if (isset($_GET["q"]) && !empty($_GET["q"])) {
            $resultats = $this->model->rechercherColis($_GET["q"]);
        }

        require __DIR__ . '/../views/postal-iut/recherche-colis.php';
    }


    public function colisNonIdentifies() {

        $colis = $this->model->getColisNonIdentifie();

        require __DIR__ . '/../views/postal-iut/colis-non-identifies.php';
    }

    public function confirmation() {
        $colis = $this->model->getColisATConfirmer();
        require __DIR__ . '/../views/postal-iut/confirmation.php';
    }

    public function confirmerColis() {
        if (!isset($_GET["id"])) {
            die("ID colis manquant");
        }

        $this->model->confirmerReceptionIUT((int)$_GET["id"]);
        header("Location: /postal/confirmation?ok=1");
        exit;
    }


    public function retirerColis()
    {
        if (!isset($_GET["id"])) {
            die("ID colis manquant");
        }

        $id_colis = (int) $_GET["id"];

        $this->model->marquerColisRetire($id_colis);

        header("Location: /postal/colis/recus?ok=1");
        exit;
    }

    public function ajouterColis()
{
    $message = null;

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $numeroBC = trim($_POST["numero_bc"] ?? "");
        $numeroSuivi = trim($_POST["numero_suivi"] ?? "");
        $commentaire = trim($_POST["commentaire"] ?? "");

        $nomDestinataire = trim($_POST["nom_destinataire"] ?? "");

        $bcInfo = null;

        if (!empty($numeroBC)) {
            $bcInfo = $this->model->getBCInfo($numeroBC);
        }

        $destinataireId = null;

        if (!empty($nomDestinataire)) {

            $resultats =
                $this->model->rechercherDestinataireParNom(
                    $nomDestinataire
                );

            if (!empty($resultats)) {
                $destinataireId =
                    $resultats[0]['id_utilisateur'];
            }
        }

        $data = [
            "bon_commande_id" => $bcInfo["id_bon_commande"] ?? null,
            "numero_suivi"    => $numeroSuivi,
            "destinataire_id" => $destinataireId,
            "commentaire"     => $commentaire,
            "statut_id"       => $bcInfo ? 2 : 1
        ];

        $ok = $this->model->insertColis($data);

        if ($ok) {
            $message = "Colis enregistré avec succès.";
        } else {
            $message = "Erreur lors de l'enregistrement.";
        }
    }

    require __DIR__ . '/../views/postal-iut/ajouter-colis.php';
}

    public function modifierColis() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id <= 0) {
            header("Location: /postal/colis/recus");
            exit;
        }

        $colis = $this->model->getColisById($id);
        if (!$colis) {
            header("Location: /postal/colis/recus");
            exit;
        }

        $bonCommandes = $this->model->getBonCommandes();
        $departements = $this->model->getAllDepartements();
        $statuts      = $this->model->getAllStatuts();

        require __DIR__ . '/../views/postal-iut/modifier-colis.php';
    }

    public function updateColis() {
        $id = isset($_POST['id_colis']) ? intval($_POST['id_colis']) : 0;
        if ($id <= 0) {
            header("Location: /postal/colis/recus");
            exit;
        }

        $data = [
            'numero_suivi'    => $_POST['numero_suivi'] ?? null,
            'bon_commande_id' => !empty($_POST['bon_commande_id']) ? intval($_POST['bon_commande_id']) : null,
            'destinataire_id' => !empty($_POST['destinataire_id']) ? intval($_POST['destinataire_id']) : null,
            'statut_id'       => !empty($_POST['statut_id']) ? intval($_POST['statut_id']) : 1,
            'commentaire'     => $_POST['commentaire'] ?? null
        ];

        $this->model->updateColis($id, $data);

        header("Location: /postal/colis/details?id={$id}");
        exit;
    }

    public function colisEnAttente() {
        $colis = $this->model->getListeColisEnAttente();
        require __DIR__ . '/../views/postal-iut/colis-attente.php';
    }

    public function historiqueGlobal() {
        $historique = $this->model->getHistoriqueGlobal();
        require __DIR__ . '/../views/postal-iut/historique.php';
    }

    public function rechercherDestinataire()
{
    header('Content-Type: application/json');

    $nom = $_GET['nom'] ?? '';

    echo json_encode(
        $this->model->rechercherDestinataireParNom($nom)
    );

    exit;
}
}