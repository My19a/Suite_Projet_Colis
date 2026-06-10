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


    public function receptionColis() {

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $numero_commande = $_POST["numero_commande"] ?? null;
            $numero_suivi    = $_POST["numero_suivi"] ?? null;
            $commentaire     = $_POST["commentaire"] ?? null;
            $ocr_texte_brut      = $_POST["ocr_texte_brut"] ?? null;
            $ocr_nom_destinataire = $_POST["ocr_nom_destinataire"] ?? null;
            // Recherche du destinataire via le texte OCR
            $destinataire_id = null;
            $departement = null;

            if ($ocr_texte_brut) {
                $destinataire = $this->model->rechercherDestinataireParNom($ocr_nom_destinataire);
                if ($destinataire) {
                    $destinataire_id = $destinataire["id_utilisateur"];
                    $departement = $destinataire["departement_id"];
                }
            }

            // Ajout du colis
            $colis_id = $this->model->ajouterColisUniversite([
                "numero_commande" => $numero_commande,
                "numero_suivi"    => $numero_suivi,
                "commentaire"     => $commentaire,
                "destinataire_id" => $destinataire_id
            ]);

            // Enregistrement dans historique_colis
            $this->model->ajouterHistorique([
                "colis_id"   => $colis_id,
                "action"     => "Reçu à l'université",
                "utilisateur" => $_SESSION["user"]->fullName ?? "postal_univ"
            ]);

            $message_session = "Colis enregistre avec succes";
            if ($destinataire) {
                $dept = $this->model->getDepartementNom($destinataire["departement_id"]);
                $message_session .= " — Destinataire : " . $destinataire["fullName"] . " — Departement : " . $dept;
            }
            $_SESSION["flash_message"] = $message_session;
            header("Location: /postal-univ/reception?ok=1");
            exit;
        }

        $message = null;
        if (isset($_SESSION["flash_message"])) {
            $message = $_SESSION["flash_message"];
            unset($_SESSION["flash_message"]);
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