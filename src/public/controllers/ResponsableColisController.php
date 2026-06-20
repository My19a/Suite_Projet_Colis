<?php
require_once __DIR__ . '/../models/ResponsableColisModels.php';
require_once __DIR__ . '/../../lib-tools/Mail/MailService.php';

class ResponsableColisController {

    private $model;

    public function __construct() {
        $this->model = new ResponsableColisModels();
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
            // L'envoi du mail ne doit jamais bloquer la réception/le transfert du colis,
            // mais on trace l'erreur pour pouvoir diagnostiquer (config SMTP, adresse, etc.).
            error_log("[MailService] echec envoi au demandeur (" . $infos["demandeur_email"] . ") : " . $e->getMessage());
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

        require __DIR__ . '/../views/responsable-colis/dashboard.php';
    }

//
    public function receptionColis() {

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $numero_suivi  = trim($_POST["numero_suivi"] ?? "");
            $nom_demandeur = trim($_POST["demandeur_nom"] ?? ($_POST["ocr_nom_destinataire"] ?? ""));

            if ($numero_suivi === "" || $nom_demandeur === "") {
                $_SESSION["flash_message"] = "Erreur : le numéro de suivi et le nom du demandeur sont obligatoires.";
                header("Location: /postal/reception");
                exit;
            }

            // La commande doit être reconnue : (n° suivi + demandeur) doivent correspondre
            // à un colis EN ATTENTE d'une commande déjà déclarée par l'éditeur de BC.
            $cible = $this->model->trouverColisEnAttente($numero_suivi, $nom_demandeur);
            if (!$cible) {
                $_SESSION["flash_message"] = "Erreur : aucune commande en attente ne correspond à ce numéro de suivi et ce demandeur.";
                header("Location: /postal/reception");
                exit;
            }

            // Commande reconnue : tous les colis en attente du même BC sont déclarés livrés à l'université.
            $ids = $this->model->getColisIdsEnAttenteParCommande($cible["bon_commande_id"]);
            $this->model->receptionnerColis($ids, $_SESSION["user"]->getId() ?? null);

            foreach ($ids as $idColis) {
                $this->model->ajouterHistorique([
                    "colis_id"   => $idColis,
                    "action"     => "Livré à l'université",
                    "utilisateur" => $_SESSION["user"]->getFullName() ?? "responsable_colis"
                ]);
            }

            // Un seul mail au demandeur pour la commande reconnue.
            $this->notifierDemandeur($cible["id_colis"], "reception");

            $_SESSION["flash_message"] = "Réception enregistrée avec succès : commande " . $cible["numero_commande"]
                . " déclarée livrée à l'université (" . count($ids) . " colis). Le demandeur a été notifié par mail.";
            header("Location: /postal/reception?ok=1");
            exit;
        }

        $message = null;
        if (isset($_SESSION["flash_message"])) {
            $message = $_SESSION["flash_message"];
            unset($_SESSION["flash_message"]);
        }

        require __DIR__ . '/../views/responsable-colis/reception-colis.php';
    }

    public function commandesAReceptionner() {
        $commandes = $this->model->getBonsCommandeAReceptionner();
        require __DIR__ . '/../views/responsable-colis/commandes-reception.php';
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

        require __DIR__ . '/../views/responsable-colis/commande-details.php';
    }

    public function listeColis() {

        $colis = $this->model->getTousLesColis();

        require __DIR__ . '/../views/responsable-colis/colis.php';
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

        require __DIR__ . '/../views/responsable-colis/non-identifies.php';
    }


    public function historique() {

        $historique = $this->model->getHistorique();

        require __DIR__ . '/../views/responsable-colis/historique.php';
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
