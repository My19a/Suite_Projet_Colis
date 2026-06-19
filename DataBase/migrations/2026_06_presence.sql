-- =====================================================================
-- Migration : presence des utilisateurs (page "Utilisateurs connectes")
-- Ajoute le suivi de la derniere activite de chaque utilisateur.
-- =====================================================================

USE sae_colis;

ALTER TABLE utilisateur
    ADD COLUMN derniere_activite DATETIME DEFAULT NULL;
