-- =====================================================================
-- Migration : memorisation du tutoriel (premiere visite par compte)
-- Ajoute un drapeau "tuto vu" sur chaque utilisateur.
-- Ainsi le tuto se montre une seule fois par compte, et "docker compose
-- down -v" (base recreee) le remet a zero comme une premiere visite.
-- =====================================================================

USE sae_colis;

ALTER TABLE utilisateur
    ADD COLUMN tuto_vu TINYINT(1) NOT NULL DEFAULT 0;
