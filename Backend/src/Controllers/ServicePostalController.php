<?php

class ServicePostalController
{
    private $model;

    public function __construct()
    {
        $this->model = Model::getModel();
    }

    // Page principale du service postal
    public function index()
    {
        // Pour l'instant, pas d'authentification stricte
        // Tu pourras ajouter la vérification CAS plus tard
        session_start();
        
        // Récupère toutes les données nécessaires
        $colisRecusAujourdhui = $this->model->getColisRecusAujourdhui();
        $colisTransferes = $this->model->getColisTransferes();
        $colisEnAttente = $this->model->getColisEnAttente();
        $colisRecus = $this->model->getColisRecusUniv();
        $colisTransferesHist = $this->model->getColisTransferesHistorique();

        // Charge la vue
        include 'src/Views/ServicePostal/index.php';
    }

    // Transfère un colis (appelé en AJAX)
    public function transferer()
    {
        session_start();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $idColis = $data['id_colis'] ?? null;
            
            if ($idColis) {
                // Pour l'instant, utilise un ID utilisateur fictif (1)
                // Plus tard, tu utiliseras l'ID de l'utilisateur connecté via CAS
                $userId = 1;
                
                $success = $this->model->transfererColis($idColis, $userId);
                
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => $success,
                    'message' => $success ? 'Colis transféré avec succès' : 'Erreur lors du transfert'
                ]);
                exit;
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Requête invalide']);
        exit;
    }
}
