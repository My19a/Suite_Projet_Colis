<?php

namespace SAE\Controllers;

use SAE\Auth\User;
use SAE\Models\IutPostalModels;

class IutPostalController {

    private $model;
    private $currentUser;

    public function __construct(User $user) {
        $this->model = new IutPostalModels();
        $this->currentUser = $user;
    }

    /*  DASHBOARD  */
    public function dashboard() {

        $stats = [
            "recus_aujourdhui" => $this->model->countColisRecusAujourdhui(),
            "en_attente"       => $this->model->countColisEnAttente(),
            "livres_auj"       => $this->model->countColisLivresAujourdhui(),
            "non_identifies"   => $this->model->countColisNonIdentifies()
        ];

        $colis_recents        = $this->model->getColisRecents();
        $statuts_repartition  = $this->model->getRepartitionStatuts();
        $top_departements     = $this->model->getTopDepartements();

        view('postal-iut/dashboard', compact('stats', 'colis_recents', 'statuts_repartition', 'top_departements'));
    }

    /* Voir les COLIS RECUS  */
    public function colisRecus() {

        $statut = isset($_GET["statut"]) ? intval($_GET["statut"]) : null;

        if ($statut) {
            $colis = $this->model->getColisFiltre($statut);
        } else {
            $colis = $this->model->getAllColis();
        }

        $statuts = $this->model->getAllStatuts();

        view('postal-iut/colis-recus', compact('colis', 'statuts'));
    }

    /* Pour les COLIS REMIS */
    public function colisRemis() {
        $colis = $this->model->getColisRemis();
        view('postal-iut/colis-remis', compact('colis'));
    }



    /* Pour faire une RECHERCHE DE COLIS  */
    public function rechercheColis() {

        $resultats = [];
        $motcle = "";

        if (!empty($_GET["q"])) {
            $motcle = trim($_GET["q"]);
            $resultats = $this->model->rechercherColis($motcle);
        }

        view('postal-iut/recherche-colis', compact('resultats', 'motcle'));
    }


/* Sa permet d'AJOUTER UN COLIS  */

    /* ============ AJOUTER UN COLIS ============ */
    public function ajouterColis() {

        $message = null;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $num_bc      = trim($_POST["numero_bc"]);
            $num_suivi   = trim($_POST["numero_suivi"]);
            $commentaire = trim($_POST["commentaire"]);

            // Récupérer info sur le BC
            $bcInfo = $this->model->getBCInfo($num_bc);

            if (!$bcInfo) {
                $message = "❌ Numéro de bon de commande introuvable.";
            } else {

                $data = [
                    "bon_commande_id" => $bcInfo["id_bon_commande"],
                    "numero_suivi"    => $num_suivi,
                    "destinataire_id" => $bcInfo["destinataire_id"],
                    "statut_id"       => 1, // 1 = En attente
                    "commentaire"     => $commentaire
                ];

                $ok = $this->model->insertColis($data);

                if ($ok) {
                    $message = "✅ Colis ajouté avec succès.";
                } else {
                    $message = "❌ Erreur lors de l’enregistrement du colis.";
                }
            }
        }

        view('postal-iut/ajouter-colis', compact('message'));
    }

    public function colisDetails() {

        if (!isset($_GET["id"])) {
            die("ID colis manquant");
        }

        $id = intval($_GET["id"]);
        $colis = $this->model->getColisById($id);
        $historique = $this->model->getHistorique($id);

        if (!$colis) {
            die("Colis introuvable");
        }

        view('postal-iut/colis-details', compact('colis', 'historique'));
    }

    public function actionLivrer() {
        $id = intval($_GET["id"]);
        $this->model->marquerLivre($id);
        redirect("/postal/colis/details/{$id}");
    }

    public function actionRetirer() {
        $id = intval($_GET["id"]);
        $this->model->marquerRetire($id);
        redirect("/postal/colis/details/{$id}");
    }


    public function nonIdentifies(){
        $colis = $this->model->getColisNonIdentifies();
        $departements = $this->model->getAllDepartement();

        view('postal-iut/non-identifies', compact('colis', 'departements'));
    }

    public function actionAssigner() {
        $id = $_POST["id_colis"];
        $dep = $_POST["departement_id"];

        $this->model->assignerDepartement($id, $dep);

        redirect('/postal/colis/non-identifies');
    }

    public function actionMarquerNonIdentifie() {
        $id = $_GET["id"];

        $this->model->marquerNonIdentifie($id);

        redirect('/postal/colis/non-identifies');
    }



    public function actionMarquerNonIdentifier() {
        if (!isset($_GET["id"])) {
            die("ID manquant");
        }

        $id = intval($_GET["id"]);

        $this->model->marquerNonIdentifie($id);

        redirect("/postal/colis/details/{$id}");
    }


    public function assignerColis() {
        if (!isset($_POST["id_colis"]) || !isset($_POST["departement_id"])) {
            die("Paramètres manquants.");
        }

        $id = intval($_POST["id_colis"]);
        $dep = intval($_POST["departement_id"]);

        $this->model->assignerColisDepartement($id, $dep);

        redirect('/postal/colis/non-identifies');
    }

    public function marquerNonIdentifie() {
        if (!isset($_GET["id"])) {
            die("ID manquant.");
        }

        $id = intval($_GET["id"]);

        $this->model->marquerCommeNonIdentifie($id);

        redirect('/postal/colis/non-identifies');
    }

    public function colisEnAttente() {
        $colis = $this->model->getColisEnAttente();
        view('postal-iut/colis-attente', compact('colis'));
    }


    public function historiqueGlobal() {
        $historique = $this->model->getHistoriqueGlobal();
        view('postal-iut/historique', compact('historique'));
    }





    public function actionModifier() {

        $id = $_POST["id_colis"];

        $data = [
            "numero_suivi"    => $_POST["numero_suivi"],
            "bon_commande_id" => $_POST["bon_commande_id"],
            "destinataire_id" => $_POST["destinataire_id"],
            "statut_id"       => $_POST["statut_id"],
            "commentaire"     => $_POST["commentaire"]
        ];

        $this->model->updateColis($id, $data);
        $this->model->addHistorique($id, "Colis mis à jour");

        redirect("/postal/colis/details/{$id}");
    }


    // dans IutPostalController.php (class IutPostalController)

    public function modifierColis() {
        // id attendu en GET
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id <= 0) {
            redirect('/postal/colis/recus');
        }

        $colis = $this->model->getColisById($id);
        if (!$colis) {
            // pas trouvé -> redirect
            redirect('/postal/colis/recus');
        }

        $bonCommandes = $this->model->getBonCommandes();
        $departements = $this->model->getAllDepartement();
        $statuts      = $this->model->getAllStatuts(); // si déjà existant

        // expose les variables à la vue
        view('postal-iut/modifier-colis', compact('colis', 'bonCommandes', 'departements', 'statuts'));
    }

    public function updateColis() {
        // traite le POST d'édition
        $id = isset($_POST['id_colis']) ? intval($_POST['id_colis']) : 0;
        if ($id <= 0) {
            redirect('/postal/colis/recus');
        }

        $data = [
            'numero_suivi'    => $_POST['numero_suivi'] ?? null,
            'bon_commande_id' => $_POST['bon_commande_id'] ? intval($_POST['bon_commande_id']) : null,
            'destinataire_id' => $_POST['destinataire_id'] ? intval($_POST['destinataire_id']) : null,
            'statut_id'       => $_POST['statut_id'] ? intval($_POST['statut_id']) : 1,
            'commentaire'     => $_POST['commentaire'] ?? null
        ];

        $this->model->updateColisById($id, $data);

        // retour vers la page détails du colis
        redirect("/postal/colis/details/{$id}");
    }













    


}
