<?php
require_once __DIR__ . "/../models/DepartementModels.php";

class DepartementController {

    private $model;

    public function __construct() {
        $this->model = new DepartementModels();
    }

    private function getUserId(): int {
        return $_SESSION['user']->getId();
    }

    private function getDepartementId(): ?int {
        return $_SESSION['user']->getDepartementId() ?? 1;
    }

    private function getUserInfo(): array {
        $user = $_SESSION['user'];
        $departement = $this->model->getDepartementNom($this->getDepartementId());
        return [
            'nom' => $user->getFullName(),
            'departement' => $departement
        ];
    }

    public function dashboard() {
        $departement_id = $this->getDepartementId();
        $userInfo = $this->getUserInfo();

        $stats = [
            "colis_total"   => $this->model->countColisTotal($departement_id),
            "en_attente"    => $this->model->countColisEnAttente($departement_id),
            "retire"        => $this->model->countColisRetires($departement_id),
        ];

        $budget = $this->model->getBudgetDepartement($departement_id);

        if ($budget) {
            $budget['budget_restant'] = $budget['budget_total'] - $budget['budget_utilise'];
        }

        $colis = $this->model->getDerniersColis($departement_id);

        require __DIR__ . "/../views/departement/dashboard.php";
    }

    public function creerDevis() {
        $fournisseurs = $this->model->getFournisseurs();
        $erreurs = $_SESSION['devis_erreurs'] ?? [];
        $ancien  = $_SESSION['devis_ancien'] ?? [];
        unset($_SESSION['devis_erreurs'], $_SESSION['devis_ancien']);
        require __DIR__ . '/../views/departement/creer-devis.php';
    }

    public function envoyerDevis() {
        $objet          = trim($_POST["objet"] ?? '');
        $montant        = $_POST["montant_estime"] ?? '';
        $fournisseur_id = $_POST["fournisseur_id"] ?? '';
        $commentaire    = $_POST["commentaire"] ?? null;

        // Validation cote serveur (ne jamais faire confiance au navigateur)
        $erreurs = [];
        if ($objet === '' || mb_strlen($objet) < 3) {
            $erreurs['objet'] = "L'objet est obligatoire (3 caracteres minimum).";
        }
        if (!is_numeric($montant) || (float) $montant <= 0) {
            $erreurs['montant'] = "Le montant doit etre un nombre superieur a 0.";
        }
        if (!ctype_digit((string) $fournisseur_id)) {
            $erreurs['fournisseur'] = "Veuillez choisir un fournisseur.";
        }

        if (!empty($erreurs)) {
            $_SESSION['devis_erreurs'] = $erreurs;
            $_SESSION['devis_ancien']  = ['objet' => $objet, 'montant' => $montant, 'fournisseur_id' => $fournisseur_id];
            header("Location: /departement/creer-devis");
            exit;
        }

        $this->model->insertDevis(
            $objet,
            $montant,
            $fournisseur_id,
            $this->getUserId()
        );

        header("Location: /departement/dashboard");
        exit;
    }

    public function mesDevis() {
        $idUtilisateur = $this->getUserId();
        $devis = $this->model->getMesDevis($idUtilisateur);
        require __DIR__ . "/../views/departement/mes-devis.php";
    }

    public function mesBonsCommande() {
        $departement_id = $this->getDepartementId();
        $bons = $this->model->getMesBonsCommande($departement_id);
        require __DIR__ . '/../views/departement/mes-bons-commande.php';
    }

    public function mesColis() {
        $departement_id = $this->getDepartementId();
        $colis = $this->model->getColisDepartement($departement_id);
        require __DIR__ . '/../views/departement/mes-colis.php';
    }

    public function budget() {
        $departement_id = $this->getDepartementId();
        $budget = $this->model->getBudgetDepartement($departement_id);
        $depenses = $this->model->getDepensesDepartement($departement_id);
        require __DIR__ . "/../views/departement/budget.php";
    }

    public function fournisseurs() {
        $fournisseurs = $this->model->getFournisseursAutorises();
        require __DIR__ . "/../views/departement/fournisseurs.php";
    }
}
