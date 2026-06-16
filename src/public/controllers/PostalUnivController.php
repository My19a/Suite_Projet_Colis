<?php
require_once __DIR__ . '/../models/PostalUnivModels.php';

class PostalUnivController {

    private $model;

    public function __construct() {
        $this->model = new PostalUnivModels();
    }

    public function dashboard() {

        $stats = [
            "recus"          => $this->model->getColisRecus(),
            "a_transferer"   => $this->model->getColisATransferer(),
            "transferes"    => $this->model->getColisTransferes(),
            "non_identifies" => $this->model->getColisNonIdentifies()
        ];

        $colis_recents = $this->model->getDerniersColis();

        require __DIR__ . '/../views/postal-univ/dashboard.php';
    }

//
    public function receptionColis() {

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $this->model->ajouterColisUniversite([
                "numero_commande" => $_POST["numero_commande"],
                "numero_suivi"    => $_POST["numero_suivi"],
                "commentaire"     => $_POST["commentaire"] ?? null
            ]);

            header("Location: /postal-univ/reception?ok=1");
            exit;
        }

        require __DIR__ . '/../views/postal-univ/reception-colis.php';
    }


    public function listeColis() {

        $colis = $this->model->getTousLesColis();

        require __DIR__ . '/../views/postal-univ/colis.php';
    }


    public function transfererColis() {

        if (!isset($_GET["id"])) {
            die("ID colis manquant");
        }

        $id_colis = intval($_GET["id"]);

        $this->model->transfererVersIUT($id_colis);

        header("Location: /postal-univ/colis?transfer=ok");
        exit;
    }

    public function nonIdentifies() {

        $colis = $this->model->getColisNonIdentifiesListe();

        require __DIR__ . '/../views/postal-univ/non-identifies.php';
    }


    public function historique() {

        $historique = $this->model->getHistorique();

        require __DIR__ . '/../views/postal-univ/historique.php';
    }






}