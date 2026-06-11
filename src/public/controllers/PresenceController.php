<?php
require_once __DIR__ . "/../models/PresenceModel.php";

/**
 * Page "Qui est connecte" : liste des utilisateurs et leur etat (en ligne /
 * hors ligne) selon leur derniere activite. Reservee a l'administrateur.
 *
 */
class PresenceController {

    private PresenceModel $model;

    public function __construct() {
        $this->model = new PresenceModel();
    }

    public function index() {
        $utilisateurs = $this->model->getPresence();
        $nbEnLigne    = $this->model->compterEnLigne();
        $seuil        = PresenceModel::SEUIL_MINUTES;

        require __DIR__ . "/../views/presence/index.php";
    }
}
