-- MariaDB sae

CREATE DATABASE sae_colis;

USE sae_colis;


-- Table Departement
CREATE TABLE departement (
    id_departement INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE,
    telephone VARCHAR(20),
    budget_total INT DEFAULT 0,
    budget_utilise INT DEFAULT 0
);

-- Table Role
CREATE TABLE role (
    id_role INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

-- Table Utilisateur (auth via CAS)
CREATE TABLE utilisateur (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    uid_cas VARCHAR(80) NOT NULL UNIQUE,
    access_token_api_cas VARCHAR(200) NOT NULL,
    fullName VARCHAR(80) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    role_id INT NOT NULL,
    departement_id INT,
    FOREIGN KEY (role_id) REFERENCES role(id_role),
    FOREIGN KEY (departement_id) REFERENCES departement(id_departement) ON DELETE SET NULL
);

-- Table Fournisseur
CREATE TABLE fournisseur (
    id_fournisseur INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    contact_nom VARCHAR(120),
    contact_email VARCHAR(120),
    contact_telephone VARCHAR(30)
);

-- Table Devis
CREATE TABLE devis (
    id_devis INT AUTO_INCREMENT PRIMARY KEY,
    date_demande DATE NOT NULL,
    objet VARCHAR(255),
    montant_estime DECIMAL(10,2),
    fichier_pdf LONGBLOB,
    statut VARCHAR(50) DEFAULT 'en_attente',
    fournisseur_id INT NOT NULL,
    createur_id INT NOT NULL,
    FOREIGN KEY (fournisseur_id) REFERENCES fournisseur(id_fournisseur),
    FOREIGN KEY (createur_id) REFERENCES utilisateur(id_utilisateur)
);

-- Table Bon de Commande
CREATE TABLE bon_commande (
    id_bon_commande INT AUTO_INCREMENT PRIMARY KEY,
    numero_commande VARCHAR(50) NOT NULL UNIQUE,
    date_commande DATE NOT NULL,
    date_estimee_livraison DATE,
    montant_estime DECIMAL(10,2) DEFAULT 0,
    statut VARCHAR(30) DEFAULT 'en_preparation',
    departement_id INT NOT NULL,
    fournisseur_id INT NOT NULL,
    createur_id INT NOT NULL,
    devis_id INT NOT NULL,
    commentaire TEXT,
    FOREIGN KEY (departement_id) REFERENCES departement(id_departement),
    FOREIGN KEY (fournisseur_id) REFERENCES fournisseur(id_fournisseur),
    FOREIGN KEY (createur_id) REFERENCES utilisateur(id_utilisateur),
    FOREIGN KEY (devis_id) REFERENCES devis(id_devis)
);

-- Table Statut Colis
CREATE TABLE statut_colis (
    id_statut INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

-- Table Colis
CREATE TABLE colis (
    id_colis INT AUTO_INCREMENT PRIMARY KEY,
    bon_commande_id INT NOT NULL,
    statut_id INT NOT NULL,
    numero_suivi VARCHAR(128),
    code_barres VARCHAR(128),
    destinataire_id INT,
    date_reception DATE,
    date_retrait DATETIME,
    commentaire TEXT,
    receptionne_par INT,
    FOREIGN KEY (bon_commande_id) REFERENCES bon_commande(id_bon_commande),
    FOREIGN KEY (statut_id) REFERENCES statut_colis(id_statut),
    FOREIGN KEY (destinataire_id) REFERENCES utilisateur(id_utilisateur),
    FOREIGN KEY (receptionne_par) REFERENCES utilisateur(id_utilisateur)
);

-- Table Notification
CREATE TABLE notification (
    id_notification INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    message_notification VARCHAR(255) NOT NULL,
    date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP,
    lu BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur)
);

CREATE TABLE historique_colis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_colis INT NOT NULL,
    action VARCHAR(255) NOT NULL,
    date_action DATETIME DEFAULT CURRENT_TIMESTAMP,
    utilisateur VARCHAR(100) DEFAULT 'postal_iut',
    FOREIGN KEY (id_colis) REFERENCES colis(id_colis)
);

-- Index
CREATE INDEX idx_utilisateur_departement ON utilisateur (departement_id);
CREATE INDEX idx_bc_numero ON bon_commande (numero_commande);
CREATE INDEX idx_colis_suivi ON colis (numero_suivi);


INSERT INTO statut_colis (libelle) VALUES
('recu_universite'),
('transfere_iut'),
('en_attente'),
('livre');

-- Departements
INSERT INTO departement (nom, telephone, budget_total, budget_utilise) VALUES
('Informatique', '01 49 40 30 01', 50000, 12000),
('Genie Civil', '01 49 40 30 02', 35000, 8000),
('GEA', '01 49 40 30 03', 40000, 15000),
('TC', '01 49 40 30 04', 30000, 5000),
('MMI', '01 49 40 30 05', 45000, 20000);

-- Roles
INSERT INTO role (libelle) VALUES
('admin'),
('postal_iut'),
('postal_univ'),
('finance'),
('directeur'),
('departement');

-- Utilisateurs
INSERT INTO utilisateur (uid_cas, access_token_api_cas, fullName, email, role_id, departement_id) VALUES
('admin1', 'token_admin_001', 'Jean Admin', 'jean.admin@univ-paris13.fr', 1, NULL),
('postal_iut1', 'token_postal_iut_001', 'Marie Postal', 'marie.postal@univ-paris13.fr', 2, NULL),
('postal_univ1', 'token_postal_univ_001', 'Pierre Courrier', 'pierre.courrier@univ-paris13.fr', 3, NULL),
('finance1', 'token_finance_001', 'Sophie Finance', 'sophie.finance@univ-paris13.fr', 4, NULL),
('directeur1', 'token_directeur_001', 'Paul Directeur', 'paul.directeur@univ-paris13.fr', 5, NULL),
('jdupont', 'token_jdupont_001', 'Jacques Dupont', 'jacques.dupont@univ-paris13.fr', 6, 1),
('mmartin', 'token_mmartin_001', 'Michel Martin', 'michel.martin@univ-paris13.fr', 6, 1),
('adurand', 'token_adurand_001', 'Alice Durand', 'alice.durand@univ-paris13.fr', 6, 2),
('lbernard', 'token_lbernard_001', 'Lucie Bernard', 'lucie.bernard@univ-paris13.fr', 6, 3),
('tmoreau', 'token_tmoreau_001', 'Thomas Moreau', 'thomas.moreau@univ-paris13.fr', 6, 5);

-- Fournisseurs
INSERT INTO fournisseur (nom, contact_nom, contact_email, contact_telephone) VALUES
('Amazon Business', 'Service Client', 'business@amazon.fr', '0800 84 77 15'),
('LDLC Pro', 'Jean Commercial', 'pro@ldlc.com', '04 27 46 60 00'),
('Dell France', 'Support Entreprise', 'support@dell.fr', '0825 387 270'),
('RS Components', 'Service Technique', 'technique@rs-components.fr', '03 44 10 15 00'),
('Farnell', 'Commercial France', 'ventes@farnell.fr', '03 44 10 14 00');

-- Devis
INSERT INTO devis (date_demande, objet, montant_estime, fichier_pdf, statut, fournisseur_id, createur_id) VALUES
('2026-01-10', 'Ordinateurs portables x5', 4500.00, NULL, 'accepte', 2, 6),
('2026-01-12', 'Ecrans 27 pouces x10', 2800.00, NULL, 'accepte', 2, 6),
('2026-01-14', 'Serveur Dell PowerEdge', 8500.00, NULL, 'accepte', 3, 7),
('2026-01-15', 'Composants electroniques', 1200.00, NULL, 'en_attente', 4, 8),
('2026-01-18', 'Materiel de bureau', 650.00, NULL, 'accepte', 1, 9);

-- Bons de commande
INSERT INTO bon_commande (numero_commande, date_commande, date_estimee_livraison, montant_estime, statut, departement_id, fournisseur_id, createur_id, devis_id, commentaire) VALUES
('BC-2026-001', '2026-01-11', '2026-01-20', 4500.00, 'livree', 1, 2, 6, 1, 'Commande urgente'),
('BC-2026-002', '2026-01-13', '2026-01-22', 2800.00, 'en_cours', 1, 2, 6, 2, NULL),
('BC-2026-003', '2026-01-15', '2026-01-25', 8500.00, 'livree', 1, 3, 7, 3, 'Serveur salle B204'),
('BC-2026-004', '2026-01-16', '2026-01-28', 1200.00, 'en_preparation', 2, 4, 8, 4, NULL),
('BC-2026-005', '2026-01-19', '2026-01-26', 650.00, 'en_cours', 3, 1, 9, 5, 'Fournitures diverses');

-- Colis
INSERT INTO colis (bon_commande_id, statut_id, numero_suivi, code_barres, destinataire_id, date_reception, date_retrait, commentaire, receptionne_par) VALUES
(1, 4, 'LP123456789FR', 'BC001-COL001', 6, '2026-01-19', '2026-01-20 10:30:00', 'Livre en main propre', 2),
(1, 4, 'LP123456790FR', 'BC001-COL002', 6, '2026-01-19', '2026-01-20 10:35:00', NULL, 2),
(2, 2, 'LP234567891FR', 'BC002-COL001', 6, '2026-01-21', NULL, 'Colis volumineux', 2),
(2, 3, 'LP234567892FR', 'BC002-COL002', 7, '2026-01-21', NULL, 'En attente retrait', 2),
(3, 4, 'DHL987654321', 'BC003-COL001', 7, '2026-01-24', '2026-01-24 14:00:00', 'Serveur - manipuler avec soin', 2),
(5, 1, 'AMZ111222333', 'BC005-COL001', 9, '2026-01-21', NULL, 'Petit colis', 3),
(5, 2, 'AMZ111222334', 'BC005-COL002', 9, '2026-01-21', NULL, NULL, 2);

-- Historique colis
INSERT INTO historique_colis (id_colis, action, date_action, utilisateur) VALUES
(1, 'Reception universite', '2026-01-19 08:00:00', 'postal_univ'),
(1, 'Transfert IUT', '2026-01-19 09:30:00', 'postal_iut'),
(1, 'Remis au destinataire', '2026-01-20 10:30:00', 'postal_iut'),
(2, 'Reception universite', '2026-01-19 08:05:00', 'postal_univ'),
(2, 'Transfert IUT', '2026-01-19 09:35:00', 'postal_iut'),
(2, 'Remis au destinataire', '2026-01-20 10:35:00', 'postal_iut'),
(3, 'Reception universite', '2026-01-21 08:00:00', 'postal_univ'),
(3, 'Transfert IUT', '2026-01-21 09:00:00', 'postal_iut'),
(4, 'Reception universite', '2026-01-21 08:05:00', 'postal_univ'),
(4, 'Transfert IUT', '2026-01-21 09:05:00', 'postal_iut'),
(4, 'En attente de retrait', '2026-01-21 09:10:00', 'postal_iut'),
(5, 'Reception universite', '2026-01-24 08:00:00', 'postal_univ'),
(5, 'Transfert IUT', '2026-01-24 10:00:00', 'postal_iut'),
(5, 'Remis au destinataire', '2026-01-24 14:00:00', 'postal_iut'),
(6, 'Reception universite', '2026-01-21 14:00:00', 'postal_univ'),
(7, 'Reception universite', '2026-01-21 14:05:00', 'postal_univ'),
(7, 'Transfert IUT', '2026-01-21 15:00:00', 'postal_iut');

-- Notifications
INSERT INTO notification (id_utilisateur, message_notification, date_envoi, lu) VALUES
(6, 'Votre colis LP123456789FR est arrive a l IUT', '2026-01-19 09:30:00', TRUE),
(6, 'Votre colis LP234567891FR est disponible', '2026-01-21 09:00:00', FALSE),
(7, 'Votre colis LP234567892FR est en attente de retrait', '2026-01-21 09:10:00', FALSE),
(7, 'Votre serveur Dell est arrive', '2026-01-24 10:00:00', TRUE),
(9, 'Colis Amazon disponible au service postal', '2026-01-21 15:00:00', FALSE);
