-- =====================================================================
-- Migration : fonctionnalites Rayan (presence utilisateurs)
-- Cible      : base sae_colis
--
-- Ajoute le suivi de la derniere activite de chaque utilisateur, pour
-- la page "Qui est connecte" (en ligne / hors ligne).
-- Les notifications mail reutilisent la table `notification` existante.
-- =====================================================================

USE sae_colis;

ALTER TABLE utilisateur
    ADD COLUMN derniere_activite DATETIME DEFAULT NULL;
