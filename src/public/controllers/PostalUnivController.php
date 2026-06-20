<?php
require_once __DIR__ . '/../models/PostalUnivModels.php';
require_once __DIR__ . '/../../lib-tools/Mail/MailService.php';

class PostalUnivController {

    private $model;

    public function __construct() {
        $this->model = new PostalUnivModels();
    }

    /**
     * Notifie par mail le demandeur (créateur du bon de commande) d'un changement de statut.
     * $etape : 'reception' (reçu à l'université) ou 'transfert' (transféré à l'IUT).
     * Silencieux si pas de demandeur lié ou si l'envoi échoue (ne casse jamais le flux).
     */
    private function notifierDemandeur($id_colis, $etape) {
        $infos = $this->model->getColisInfosPourMail($id_colis);
        if (!$infos || empty($infos["demandeur_email"])) {
            return;
        }

        $numCommande = $infos["numero_commande"];
        $numColis    = htmlspecialchars($infos["numero_suivi"] ?: "—");
        $sujet = "Notification de changement de statut - Commande N°" . $numCommande;

        if ($etape === "reception") {
            $body = "Bonjour,<br><br>"
                  . "Nous vous informons que votre colis n°" . $numColis . " est bel et bien arrivé à l'Université.<br>"
                  . "Une fois transféré à l'IUT par le responsable, vous serez notifié afin de pouvoir venir le retirer.<br><br>"
                  . "Cordialement.";
        } else {
            $body = "Bonjour,<br><br>"
                  . "Votre colis n°" . $numColis . " a bien été transféré à l'IUT, vous pouvez désormais venir le retirer.<br><br>"
                  . "Cordialement.";
        }

        try {
            MailService::send($infos["demandeur_email"], $infos["demandeur_nom"] ?? "", $sujet, $body);
        } catch (\Throwable $e) {
            // L'envoi du mail ne doit jamais bloquer la réception/le transfert du colis.
        }
    }

    public function dashboard() {

        $stats = [
            "bons_total"       => $this->model->getBonsCommandeTotal(),
            "a_receptionner"   => $this->model->getBonsCommandeSansColis(),
            "a_transferer"     => $this->model->getColisATransferer(),
            "transferes"      => $this->model->getColisTransferes()
        ];

        $colis_recents = $this->model->getDerniersColis();
        $commandes_a_receptionner = $this->model->getBonsCommandeAReceptionner();

        require __DIR__ . '/../views/postal-univ/dashboard.php';
    }

//
    public function receptionColis() {

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $numero_commande = trim($_POST["numero_commande"] ?? "");
            $numero_suivi    = trim($_POST["numero_suivi"] ?? "");
            $commentaire     = $_POST["commentaire"] ?? null;
            $ocr_texte_brut      = $_POST["ocr_texte_brut"] ?? null;
            $ocr_nom_destinataire = $_POST["ocr_nom_destinataire"] ?? null;
            // Recherche du destinataire via le texte OCR
            $destinataire_id = null;
            $departement = null;
            $destinataire = null;

            if ($numero_commande === "") {
                $_SESSION["flash_message"] = "Erreur : le numero de bon de commande est obligatoire.";
                header("Location: /postal-univ/reception");
                exit;
            }

            if (!$this->model->getBonCommandeParNumero($numero_commande)) {
                $_SESSION["flash_message"] = "Erreur : aucun bon de commande ne correspond a ce numero.";
                header("Location: /postal-univ/reception");
                exit;
            }

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
                "destinataire_id" => $destinataire_id,
                "receptionne_par" => $_SESSION["user"]->getId() ?? null
            ]);

            if (!$colis_id) {
                $_SESSION["flash_message"] = "Erreur : impossible d'enregistrer ce colis.";
                header("Location: /postal-univ/reception");
                exit;
            }

            // Enregistrement dans historique_colis
            $this->model->ajouterHistorique([
                "colis_id"   => $colis_id,
                "action" => "Recu a l universite",
                "utilisateur" => $_SESSION["user"]->getFullName() ?? "postal_univ"
            ]);

            $message_session = "Colis enregistre avec succes";
            if ($destinataire) {
                $dept = $this->model->getDepartementNom($destinataire["departement_id"]);
                $message_session .= " — Destinataire : " . $destinataire["fullName"] . " — Departement : " . $dept;
            }
            // Mail au demandeur : colis reçu à l'université
            $this->notifierDemandeur($colis_id, "reception");

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

    public function commandesAReceptionner() {
        $commandes = $this->model->getBonsCommandeAReceptionner();
        require __DIR__ . '/../views/postal-univ/commandes-reception.php';
    }

    public function detailCommande() {
        $id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
        if ($id <= 0) {
            die("ID bon de commande manquant");
        }

        $commande = $this->model->getCommandeReceptionDetails($id);
        if (!$commande) {
            die("Bon de commande introuvable");
        }

        require __DIR__ . '/../views/postal-univ/commande-details.php';
    }

    public function receptionnerCommande() {
        $ids = $_POST["colis_ids"] ?? [];
        $idCommande = isset($_POST["id_bon_commande"]) ? (int) $_POST["id_bon_commande"] : 0;

        if (empty($ids) || $idCommande <= 0) {
            header("Location: /postal/commande?id=" . $idCommande);
            exit;
        }

        $this->model->receptionnerColis($ids, $_SESSION["user"]->getId() ?? null);

        foreach ($ids as $idColis) {
            $this->model->ajouterHistorique([
                "colis_id" => (int) $idColis,
                "action" => "Recu a l universite",
                "utilisateur" => $_SESSION["user"]->getFullName() ?? "responsable_colis"
            ]);
            $this->notifierDemandeur((int) $idColis, "reception");
        }

        header("Location: /postal/commande?id=" . $idCommande . "&ok=1");
        exit;
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

        // Mail au demandeur : colis transféré à l'IUT
        $this->notifierDemandeur($id_colis, "transfert");

        $this->model->ajouterHistorique([
            "colis_id"   => $id_colis,
            "action" => "Transfere a l IUT",
            "utilisateur" => $_SESSION["user"]->getFullName() ?? "responsable_colis"
        ]);

        header("Location: /postal/colis?transfer=ok");
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

    public function rechercherDestinataire() {
    $nom = $_GET["nom"] ?? "";
    $destinataire = $this->model->rechercherDestinataireParNom($nom);
    
    if ($destinataire) {
        $dept = $this->model->getDepartementNom($destinataire["departement_id"]);
        echo json_encode(["departement" => $dept]);
    } else {
        echo json_encode(["departement" => null]);
    }
    exit;
    }
}
