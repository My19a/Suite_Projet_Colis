<?php
require_once __DIR__ . "/../models/PresenceModel.php";

/**
 * Page "Utilisateurs connectes" : liste des utilisateurs et leur etat
 * (en ligne / hors ligne) selon leur derniere activite. Reservee a l'admin.
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
