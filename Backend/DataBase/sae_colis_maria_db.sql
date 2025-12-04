-- v2 MariaDB sae
CREATE DATABASE suivi_colis_sae
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

-- Index
CREATE INDEX idx_utilisateur_departement ON utilisateur (departement_id);
CREATE INDEX idx_bc_numero ON bon_commande (numero_commande);
CREATE INDEX idx_colis_suivi ON colis (numero_suivi);


