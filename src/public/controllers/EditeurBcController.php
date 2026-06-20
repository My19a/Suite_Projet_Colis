<?php
require_once __DIR__ . "/../models/EditeurBcModels.php";

/**
 * Contrôleur du rôle « éditeur_bc » (Éditeur de bons de commande).
 * Fusion des anciens rôles Finance (vérification des devis, budgets)
 * et Directeur (signature des devis + génération des bons de commande / colis).
 */
class EditeurBcController {

    private $model;

    public function __construct() {
        $this->model = new EditeurBcModels();
    }

    public function dashboard() {
        $stats = [
            "devis_attente" => $this->model->countDevisEnAttente(),
            "bons_commande" => $this->model->countBonCommande()
        ];

        $budgets = $this->model->getBudgetsDepartements();
        $devis   = $this->model->getDevisEnAttente();
        $bons    = $this->model->getBonsCommandeRecents();

        require __DIR__ . "/../views/editeur-bc/dashboard.php";
    }

    public function validerDevis() {
        if (!isset($_GET["id"])) {
            die("ID devis manquant");
        }
        $this->model->validerDevis(intval($_GET["id"]));
        header("Location: /finance/dashboard");
        exit;
    }

    public function rejeterDevis() {
        if (!isset($_GET["id"])) {
            die("ID devis manquant");
        }
        $this->model->rejeterDevis(intval($_GET["id"]));
        header("Location: /finance/dashboard");
        exit;
    }

    public function devisAVerifier() {
        $devis = $this->model->getDevisAVerifier();
        require __DIR__ . '/../views/editeur-bc/devis-a-verifier.php';
    }

    public function bonsCommande() {
        $bons = $this->model->getTousLesBonsCommande();
        require __DIR__ . '/../views/editeur-bc/bons-commande.php';
    }

    public function budgets() {
        $budgets = $this->model->getBudgetDepartements();
        require __DIR__ . "/../views/editeur-bc/budgets.php";
    }

    public function voirDevis() {
        if (!isset($_GET['id'])) {
            die("ID devis manquant");
        }
        $devis = $this->model->getDevisComplet(intval($_GET['id']));
        if (!$devis) {
            die("Devis introuvable");
        }
        require_once __DIR__ . '/../services/PdfGenerator.php';
        $pdfGenerator = new PdfGenerator();
        $pdfGenerator->genererDevis($devis);
    }

    public function devisASigner() {
        $devis = $this->model->getDevisAValider();
        require __DIR__ . '/../views/editeur-bc/devis-a-signer.php';
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

        // On signe seulement si la vérification (finance) a validé le devis.
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
                require __DIR__ . "/../views/editeur-bc/signer-devis-form.php";
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

        require __DIR__ . "/../views/editeur-bc/signer-devis-form.php";
    }
}
