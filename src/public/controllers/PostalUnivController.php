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
                  . "Une fois transféré à l'IUT, vous serez notifié afin de pouvoir venir le retirer.<br><br>"
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