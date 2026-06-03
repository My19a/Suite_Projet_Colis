-- =====================================================================
-- Migration : Systeme de tickets et d'assistance
-- Auteur     : Rayan KEBBICHE
-- Cible      : base sae_colis (MariaDB / MySQL 8)
--
-- Permet a n'importe quel utilisateur de signaler un probleme,
-- d'echanger des messages dessus, et a l'administrateur de suivre
-- le statut (ouvert / en_cours / resolu).
-- =====================================================================

USE sae_colis;

-- Un ticket = un probleme signale par un utilisateur
CREATE TABLE IF NOT EXISTS ticket (
    id_ticket     INT AUTO_INCREMENT PRIMARY KEY,
    sujet         VARCHAR(150) NOT NULL,
    description   TEXT NOT NULL,
    categorie     VARCHAR(50) NOT NULL DEFAULT 'general',
    priorite      ENUM('basse', 'normale', 'haute') NOT NULL DEFAULT 'normale',
    statut        ENUM('ouvert', 'en_cours', 'resolu') NOT NULL DEFAULT 'ouvert',
    createur_id   INT NOT NULL,
    assigne_id    INT DEFAULT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_maj      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (createur_id) REFERENCES utilisateur(id_utilisateur) ON DELETE CASCADE,
    FOREIGN KEY (assigne_id)  REFERENCES utilisateur(id_utilisateur) ON DELETE SET NULL
);

-- Le fil de discussion d'un ticket (message du demandeur ou du support)
CREATE TABLE IF NOT EXISTS ticket_message (
    id_message INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id  INT NOT NULL,
    auteur_id  INT NOT NULL,
    message    TEXT NOT NULL,
    date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES ticket(id_ticket) ON DELETE CASCADE,
    FOREIGN KEY (auteur_id) REFERENCES utilisateur(id_utilisateur) ON DELETE CASCADE
);

CREATE INDEX idx_ticket_createur     ON ticket (createur_id);
CREATE INDEX idx_ticket_statut       ON ticket (statut);
CREATE INDEX idx_ticket_msg_ticket   ON ticket_message (ticket_id);

-- =====================================================================
-- Donnees de demonstration (facultatif, a retirer en production)
-- =====================================================================
INSERT INTO ticket (sujet, description, categorie, priorite, statut, createur_id)
SELECT 'Impossible de relire le PDF du devis',
       'Apres validation d''un devis, le bouton "Voir le PDF" renvoie une erreur 404.',
       'devis', 'haute', 'ouvert', u.id_utilisateur
FROM utilisateur u WHERE u.uid_cas = 'jdupont' LIMIT 1;
