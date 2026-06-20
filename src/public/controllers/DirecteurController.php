<?php

require_once __DIR__ . "/../models/DirecteurModels.php";

class DirecteurController {

    private $model;

    public function __construct() {
        $this->model = new DirecteurModels();
    }
//
    public function dashboard() {

        $stats = [
            "devis_attente" => $this->model->countDevisEnAttente(),
            "bc_signes"     => $this->model->countBonCommande()
        ];

        $devis = $this->model->getDevisAValider();
        $bons  = $this->model->getBonCommandeSignes();

        require __DIR__ . "/../views/directeur-iut/dashboard.php";
    }
//

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

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $lignes = [];
            $numeros = $_POST["numero_suivi"] ?? [];
            $descriptions = $_POST["description"] ?? [];
            $quantites = $_POST["quantite"] ?? [];

            foreach ($numeros as $index => $numero) {
                if (trim($numero) === "") {
                    continue;
                }
                $lignes[] = [
                    "numero_suivi" => $numero,
                    "description" => $descriptions[$index] ?? "",
                    "quantite" => $quantites[$index] ?? 1,
                ];
            }

            if (empty($lignes)) {
                $erreur = "Ajoutez au moins un colis à la commande.";
                require __DIR__ . "/../views/directeur-iut/signer-devis-form.php";
                return;
            }

            $this->model->signerDevis($id, [
                "numero_commande" => $_POST["numero_commande"] ?? "",
                "objet" => $_POST["objet"] ?? "",
                "montant_estime" => $_POST["montant_estime"] ?? "",
                "date_estimee_livraison" => $_POST["date_estimee_livraison"] ?? null,
            ], $lignes);

            header("Location: /directeur/bons-commande");
            exit;
        }

        require __DIR__ . "/../views/directeur-iut/signer-devis-form.php";
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
