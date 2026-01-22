<?php

require_once __DIR__ . '/../../vendor/autoload.php';

class PdfGenerator
{
    private $pdf;

    public function __construct()
    {
        $this->pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
        $this->pdf->SetMargins(20, 20, 20);
    }

    public function genererDevis($devis)
    {
        $this->pdf->SetCreator('SAE Suivi Colis');
        $this->pdf->SetAuthor('IUT Villetaneuse');
        $this->pdf->SetTitle('Devis #' . $devis['id_devis']);
        $this->pdf->AddPage();

        $this->ajouterEntete($devis);
        $this->ajouterDemandeur($devis);
        $this->ajouterFournisseur($devis);
        $this->ajouterDetails($devis);
        $this->ajouterStatut($devis);
        $this->ajouterBudget($devis);
        $this->ajouterPiedDePage();

        $this->pdf->Output('Devis_' . str_pad($devis['id_devis'], 4, '0', STR_PAD_LEFT) . '.pdf', 'I');
        exit;
    }

    private function ajouterEntete($devis)
    {
        // Logo JPEG (pas de probleme alpha channel)
        $logoPath = __DIR__ . '/../assets/img/logo-iutv.jpg';
        if (file_exists($logoPath)) {
            $this->pdf->Image($logoPath, 20, 15, 35, 0, 'JPEG');
        }

        // Titre
        $this->pdf->SetFont('helvetica', 'B', 20);
        $this->pdf->SetXY(65, 20);
        $this->pdf->Cell(0, 10, 'DEMANDE DE DEVIS', 0, 1, 'L');

        $this->pdf->SetFont('helvetica', '', 11);
        $this->pdf->SetXY(65, 32);
        $this->pdf->Cell(0, 6, 'Universite Sorbonne Paris Nord', 0, 1, 'L');
        $this->pdf->SetXY(65, 38);
        $this->pdf->Cell(0, 6, '99 Avenue Jean-Baptiste Clement, 93430 Villetaneuse', 0, 1, 'L');

        // Numero et date
        $this->pdf->SetXY(140, 20);
        $this->pdf->SetFont('helvetica', 'B', 12);
        $this->pdf->Cell(50, 8, 'Devis N° ' . str_pad($devis['id_devis'], 4, '0', STR_PAD_LEFT), 1, 1, 'C', false);
        $this->pdf->SetXY(140, 28);
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->Cell(50, 7, 'Date: ' . date('d/m/Y', strtotime($devis['date_demande'])), 1, 1, 'C', false);

        // Ligne separatrice
        $this->pdf->SetY(55);
        $this->pdf->SetDrawColor(37, 99, 235);
        $this->pdf->SetLineWidth(0.8);
        $this->pdf->Line(20, 55, 190, 55);
    }

    private function ajouterDemandeur($devis)
    {
        $this->pdf->SetY(62);
        $this->pdf->SetFont('helvetica', 'B', 12);
        $this->pdf->SetTextColor(37, 99, 235);
        $this->pdf->Cell(0, 8, 'DEMANDEUR', 0, 1, 'L');
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetFont('helvetica', '', 11);
        $this->pdf->Cell(0, 6, 'Nom: ' . ($devis['demandeur_nom'] ?? 'N/A'), 0, 1, 'L');
        $this->pdf->Cell(0, 6, 'Email: ' . ($devis['demandeur_email'] ?? 'N/A'), 0, 1, 'L');
        $this->pdf->Cell(0, 6, 'Departement: ' . ($devis['departement_nom'] ?? 'N/A'), 0, 1, 'L');
    }

    private function ajouterFournisseur($devis)
    {
        $this->pdf->SetY(95);
        $this->pdf->SetFont('helvetica', 'B', 12);
        $this->pdf->SetTextColor(37, 99, 235);
        $this->pdf->Cell(0, 8, 'FOURNISSEUR', 0, 1, 'L');
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetFont('helvetica', '', 11);
        $this->pdf->Cell(0, 6, 'Societe: ' . ($devis['fournisseur_nom'] ?? 'N/A'), 0, 1, 'L');
        $this->pdf->Cell(0, 6, 'Contact: ' . ($devis['fournisseur_contact'] ?? 'N/A'), 0, 1, 'L');
        $this->pdf->Cell(0, 6, 'Email: ' . ($devis['fournisseur_email'] ?? 'N/A'), 0, 1, 'L');
        $this->pdf->Cell(0, 6, 'Telephone: ' . ($devis['fournisseur_telephone'] ?? 'N/A'), 0, 1, 'L');
    }

    private function ajouterDetails($devis)
    {
        $this->pdf->SetY(135);
        $this->pdf->SetFont('helvetica', 'B', 12);
        $this->pdf->SetTextColor(37, 99, 235);
        $this->pdf->Cell(0, 8, 'DETAILS DE LA DEMANDE', 0, 1, 'L');
        $this->pdf->SetTextColor(0, 0, 0);

        // En-tete tableau
        $this->pdf->SetFont('helvetica', 'B', 10);
        $this->pdf->SetFillColor(37, 99, 235);
        $this->pdf->SetTextColor(255, 255, 255);
        $this->pdf->Cell(130, 10, 'Objet', 1, 0, 'C', true);
        $this->pdf->Cell(40, 10, 'Montant', 1, 1, 'C', true);

        // Contenu
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetFillColor(245, 247, 250);
        $this->pdf->MultiCell(130, 12, $devis['objet'] ?? 'N/A', 1, 'L', true, 0);
        $this->pdf->Cell(40, 12, number_format($devis['montant_estime'] ?? 0, 2, ',', ' ') . ' EUR', 1, 1, 'R', true);

        // Total
        $this->pdf->SetFont('helvetica', 'B', 11);
        $this->pdf->Cell(130, 10, 'TOTAL TTC', 1, 0, 'R', false);
        $this->pdf->SetFillColor(37, 99, 235);
        $this->pdf->SetTextColor(255, 255, 255);
        $this->pdf->Cell(40, 10, number_format($devis['montant_estime'] ?? 0, 2, ',', ' ') . ' EUR', 1, 1, 'C', true);
    }

    private function ajouterStatut($devis)
    {
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetY(190);
        $this->pdf->SetFont('helvetica', 'B', 11);
        $this->pdf->Cell(40, 8, 'Statut actuel: ', 0, 0, 'L');
        $this->pdf->SetFont('helvetica', '', 11);

        $statutLabels = [
            'en_attente' => 'En attente de validation',
            'valide_finance' => 'Valide par le service financier',
            'rejete_finance' => 'Rejete par le service financier',
            'signe_directeur' => 'Signe par le directeur'
        ];
        $this->pdf->Cell(0, 8, $statutLabels[$devis['statut']] ?? ucfirst($devis['statut']), 0, 1, 'L');
    }

    private function ajouterBudget($devis)
    {
        if (!$devis['departement_nom']) return;

        $this->pdf->SetY(205);
        $this->pdf->SetFont('helvetica', 'B', 12);
        $this->pdf->SetTextColor(37, 99, 235);
        $this->pdf->Cell(0, 8, 'SITUATION BUDGETAIRE - ' . strtoupper($devis['departement_nom']), 0, 1, 'L');
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetFont('helvetica', '', 10);

        $budgetRestant = ($devis['budget_total'] ?? 0) - ($devis['budget_utilise'] ?? 0);
        $this->pdf->Cell(60, 6, 'Budget total: ' . number_format($devis['budget_total'] ?? 0, 2, ',', ' ') . ' EUR', 0, 0, 'L');
        $this->pdf->Cell(60, 6, 'Utilise: ' . number_format($devis['budget_utilise'] ?? 0, 2, ',', ' ') . ' EUR', 0, 0, 'L');
        $this->pdf->Cell(50, 6, 'Restant: ' . number_format($budgetRestant, 2, ',', ' ') . ' EUR', 0, 1, 'L');
    }

    private function ajouterPiedDePage()
    {
        $this->pdf->SetY(260);
        $this->pdf->SetFont('helvetica', 'I', 9);
        $this->pdf->SetTextColor(128, 128, 128);
        $this->pdf->Cell(0, 5, 'Document genere automatiquement le ' . date('d/m/Y a H:i'), 0, 1, 'C');
        $this->pdf->Cell(0, 5, 'SAE Suivi Colis - IUT Villetaneuse', 0, 1, 'C');
    }
}
