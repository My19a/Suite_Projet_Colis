<?php

require_once __DIR__ . "/../models/DirecteurModels.php";

class DirecteurController {

    private $model;

    public function __construct() {
        $this->model = new DirecteurModels();
    }

    public function dashboard() {

        $stats = [
            "devis_attente" => $this->model->countDevisEnAttente(),
            "bc_signes"     => $this->model->countBonCommande()
        ];

        $devis = $this->model->getDevisAValider();
        $bons  = $this->model->getBonCommandeSignes();

        require __DIR__ . "/../views/directeur-iut/dashboard.php";
    }


    public function signerDevis() {

        if (!isset($_GET["id"])) {
            die("ID devis manquant");
        }

        $id = intval($_GET["id"]);

        $devis = $this->model->getDevisById($id);

        if (!$devis) {
            die("Devis introuvable");
        }

        //  on signe seulement si finance a validé
        if ($devis["statut"] !== "valide_finance") {
            die("Ce devis ne peut pas être signé");
        }

        if ($this->model->bonCommandeExistePourDevis($id)) {
            die("Ce devis a déjà été signé.");
        }



        $this->model->signerDevis($id);

        // Retour au dashboard
        header("Location: /directeur/dashboard");
        exit;
    }


    public function devisASigner() {

        $devis = $this->model->getDevisAValider();

        require __DIR__ . '/../views/directeur-iut/devis-a-signer.php';
    }


    public function bonCommande(){
        $bons = $this->model->getTousLesBonsCommande();
        require __DIR__ . "/../views/directeur-iut/bons-commande.php";
    }


    public function voirDevis() {
        if (!isset($_GET['id'])) {
            die("ID devis manquant");
        }

        $id = intval($_GET['id']);
        $devis = $this->model->getDevisComplet($id);

        if (!$devis) {
            die("Devis introuvable");
        }

        require_once __DIR__ . '/../services/PdfGenerator.php';
        $pdfGenerator = new PdfGenerator();
        $pdfGenerator->genererDevis($devis);
    }
}
