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
    bon_commande_id INT NULL,
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

-- LES DATA QUI SUIVENT EN BAS NE SONT PAS NECESSAIRES JUSTE FICTIVES









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
('responsable_colis'),
('demandeur'),
('editeur_bc');

-- Utilisateurs
INSERT INTO utilisateur (uid_cas, access_token_api_cas, fullName, email, role_id, departement_id) VALUES
('admin1', 'token_admin_001', 'Jean Admin', 'jean.admin@univ-paris13.fr', 1, NULL),
('postal_iut1', 'token_postal_iut_001', 'Marie Postal', 'marie.postal@univ-paris13.fr', 2, NULL),
('postal_univ1', 'token_postal_univ_001', 'Pierre Courrier', 'pierre.courrier@univ-paris13.fr', 2, NULL),
('finance1', 'token_finance_001', 'Sophie Finance', 'sophie.finance@univ-paris13.fr', 4, NULL),
('directeur1', 'token_directeur_001', 'Paul Directeur', 'paul.directeur@univ-paris13.fr', 4, NULL),
('jdupont', 'token_jdupont_001', 'Jacques Dupont', 'jacques.dupont@univ-paris13.fr', 3, 1),
('mmartin', 'token_mmartin_001', 'Michel Martin', 'michel.martin@univ-paris13.fr', 3, 1),
('adurand', 'token_adurand_001', 'Alice Durand', 'alice.durand@univ-paris13.fr', 3, 2),
('lbernard', 'token_lbernard_001', 'Lucie Bernard', 'lucie.bernard@univ-paris13.fr', 3, 3),
('tmoreau', 'token_tmoreau_001', 'Thomas Moreau', 'thomas.moreau@univ-paris13.fr', 3, 5);

-- Fournisseurs
INSERT INTO fournisseur (nom, contact_nom, contact_email, contact_telephone) VALUES
('Amazon Business', 'Service Client', 'business@amazon.fr', '0800 84 77 15'),
('LDLC Pro', 'Jean Commercial', 'pro@ldlc.com', '04 27 46 60 00'),
('Dell France', 'Support Entreprise', 'support@dell.fr', '0825 387 270'),
('RS Components', 'Service Technique', 'technique@rs-components.fr', '03 44 10 15 00'),
('Farnell', 'Commercial France', 'ventes@farnell.fr', '03 44 10 14 00');

-- Devis : cycle propre = En attente de verification -> Valide / Rejete -> Signe (commande creee)
-- d1..d5 = signes (ont genere les BC 1..5) ; d6 a verifier ; d7 valide (a signer) ; d8 rejete.
INSERT INTO devis (date_demande, objet, montant_estime, fichier_pdf, statut, fournisseur_id, createur_id) VALUES
('2026-01-10', 'Ordinateurs portables x5', 4500.00, NULL, 'signe_directeur', 2, 6),
('2026-01-12', 'Ecrans 27 pouces x10', 2800.00, NULL, 'signe_directeur', 2, 6),
('2026-01-14', 'Serveur Dell PowerEdge', 8500.00, NULL, 'signe_directeur', 3, 7),
('2026-01-15', 'Mobilier de bureau', 1200.00, NULL, 'signe_directeur', 4, 8),
('2026-01-18', 'Materiel de bureau', 650.00, NULL, 'signe_directeur', 1, 9),
('2026-01-20', 'Casques audio x8', 720.00, NULL, 'en_attente', 4, 6),
('2026-01-21', 'Disques SSD x12', 1560.00, NULL, 'valide_finance', 2, 7),
('2026-01-22', 'Imprimante 3D', 2300.00, NULL, 'rejete_finance', 5, 8);

-- Bons de commande : un par devis signe. Le statut suit le flux des colis et est
-- recalcule automatiquement par l'application (En attente -> Livre a l'universite -> Transfere a l'IUT -> Receptionne).
INSERT INTO bon_commande (numero_commande, date_commande, date_estimee_livraison, montant_estime, statut, departement_id, fournisseur_id, createur_id, devis_id, commentaire) VALUES
('BC-2026-001', '2026-01-11', '2026-01-20', 4500.00, 'livre', 1, 2, 6, 1, 'Commande urgente'),
('BC-2026-002', '2026-01-13', '2026-01-22', 2800.00, 'transfere_iut', 1, 2, 6, 2, NULL),
('BC-2026-003', '2026-01-15', '2026-01-25', 8500.00, 'recu_universite', 1, 3, 7, 3, 'Serveur salle B204'),
('BC-2026-004', '2026-01-16', '2026-01-28', 1200.00, 'en_attente', 2, 4, 8, 4, NULL),
('BC-2026-005', '2026-01-19', '2026-01-26', 650.00, 'en_attente', 3, 1, 9, 5, 'Fournitures diverses');

-- Colis : statut_id 3=En attente, 1=Livre a l'universite, 2=Transfere a l'IUT, 4=Receptionne.
-- destinataire_id = le demandeur (createur du bon de commande).
INSERT INTO colis (bon_commande_id, statut_id, numero_suivi, code_barres, destinataire_id, date_reception, date_retrait, commentaire, receptionne_par) VALUES
(1, 4, 'LP123456789FR', 'BC001-COL001', 6, '2026-01-19', '2026-01-22 10:30:00', 'Livre en main propre', 2),
(1, 4, 'LP123456790FR', 'BC001-COL002', 6, '2026-01-19', '2026-01-22 10:35:00', NULL, 2),
(2, 2, 'LP234567891FR', 'BC002-COL001', 6, '2026-01-21', NULL, 'Colis volumineux', 2),
(2, 2, 'LP234567892FR', 'BC002-COL002', 6, '2026-01-21', NULL, NULL, 2),
(3, 1, 'DHL987654321',  'BC003-COL001', 7, '2026-01-24', NULL, 'Serveur - manipuler avec soin', 2),
(4, 3, 'LP444555666FR', 'BC004-COL001', 8, NULL, NULL, 'En attente de reception', NULL),
(4, 3, 'LP444555667FR', 'BC004-COL002', 8, NULL, NULL, NULL, NULL),
(5, 3, 'AMZ111222333',  'BC005-COL001', 9, NULL, NULL, 'Petit colis', NULL);

-- Historique colis : seules les vraies transitions du flux (la vue Historique
-- du responsable n'affiche que les transferts IUT et les receptions destinataire).
INSERT INTO historique_colis (id_colis, action, date_action, utilisateur) VALUES
(1, 'Livré à l''université',              '2026-01-19 08:00:00', 'Marie Postal'),
(1, 'Transféré à l''IUT',                 '2026-01-21 09:30:00', 'Marie Postal'),
(1, 'Réceptionné par le destinataire',    '2026-01-22 10:30:00', 'Jacques Dupont'),
(2, 'Livré à l''université',              '2026-01-19 08:05:00', 'Marie Postal'),
(2, 'Transféré à l''IUT',                 '2026-01-21 09:35:00', 'Marie Postal'),
(2, 'Réceptionné par le destinataire',    '2026-01-22 10:35:00', 'Jacques Dupont'),
(3, 'Livré à l''université',              '2026-01-21 08:00:00', 'Marie Postal'),
(3, 'Transféré à l''IUT',                 '2026-01-21 09:00:00', 'Marie Postal'),
(4, 'Livré à l''université',              '2026-01-21 08:05:00', 'Marie Postal'),
(4, 'Transféré à l''IUT',                 '2026-01-21 09:05:00', 'Marie Postal'),
(5, 'Livré à l''université',              '2026-01-24 08:00:00', 'Marie Postal');

-- Notifications (coherentes avec l'etat des colis)
INSERT INTO notification (id_utilisateur, message_notification, date_envoi, lu) VALUES
(6, 'Votre colis LP123456789FR a été réceptionné', '2026-01-22 10:30:00', TRUE),
(6, 'Votre colis LP234567891FR a été transféré à l''IUT', '2026-01-21 09:35:00', FALSE),
(7, 'Votre colis DHL987654321 est arrivé à l''université', '2026-01-24 08:00:00', FALSE);
