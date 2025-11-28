-- v1 MariaDB sae, 

CREATE DATABASE IF NOT EXISTS suivi_colis_sae;

-- Table Departement
CREATE TABLE IF NOT EXISTS departement (
    id_departement INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE,
    telephone VARCHAR(20),
    budget_total INT DEFAULT 0,
    budget_utilise INT DEFAULT 0
);

-- Table Role
CREATE TABLE IF NOT EXISTS role (
    id_role INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

-- Table Utilisateur (auth this-> CAS)
CREATE TABLE IF NOT EXISTS utilisateur (
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
CREATE TABLE IF NOT EXISTS fournisseur (
    id_fournisseur INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    contact_nom VARCHAR(120),
    contact_email VARCHAR(120),
    contact_telephone VARCHAR(30)
);

-- Table Bon de Commande
CREATE TABLE IF NOT EXISTS bon_commande (
    id_bon_commande INT AUTO_INCREMENT PRIMARY KEY,
    numero_commande VARCHAR(50) NOT NULL UNIQUE,
    date_commande DATE NOT NULL,
    date_estimee_livraison DATE,
    montant_estime DECIMAL(10,2) DEFAULT 0,
    statut VARCHAR(30) DEFAULT 'en preparation', -- au pire on mettra des types enum donc des INT au lieu de str :)
    departement_id INT NOT NULL,
    fournisseur_id INT NOT NULL,
    createur_id INT NOT NULL,
    commentaire TEXT,
    FOREIGN KEY (departement_id) REFERENCES departement(id_departement),
    FOREIGN KEY (fournisseur_id) REFERENCES fournisseur(id_fournisseur),
    FOREIGN KEY (createur_id) REFERENCES utilisateur(id_utilisateur)
);

-- Table Lignes de Bon de Commande
CREATE TABLE IF NOT EXISTS bon_commande_ligne (
    id_ligne INT AUTO_INCREMENT PRIMARY KEY,
    bon_commande_id INT NOT NULL,
    designation VARCHAR(255) NOT NULL,
    quantite INT NOT NULL DEFAULT 1,
    prix_unitaire DECIMAL(10,2) DEFAULT 0,
    FOREIGN KEY (bon_commande_id) REFERENCES bon_commande(id_bon_commande)
);

-- Table Statut Colis
CREATE TABLE IF NOT EXISTS statut_colis (
    id_statut INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

-- Table Colis
CREATE TABLE IF NOT EXISTS colis (
    id_colis INT AUTO_INCREMENT PRIMARY KEY,
    bon_commande_id INT NOT NULL,
    statut_id INT NOT NULL,
    code_barres VARCHAR(128),
    numero_suivi VARCHAR(128),
    destinataire_nom VARCHAR(120),
    destinataire_bureau VARCHAR(80),
    date_reception DATE,
    date_retrait DATETIME,
    commentaire TEXT,
    FOREIGN KEY (bon_commande_id) REFERENCES bon_commande(id_bon_commande),
    FOREIGN KEY (statut_id) REFERENCES statut_colis(id_statut)
);

CREATE TABLE IF NOT EXISTS notification (
    id_notification INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    message VARCHAR(255) NOT NULL,
    date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP,
    lu BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS devis (
    id_devis INT AUTO_INCREMENT PRIMARY KEY,
    date_demande DATE NOT NULL,
    objet VARCHAR(255),
    montant_estime DECIMAL(10,2),
    fichier_pdf LONGBLOB,
    statut VARCHAR(50) DEFAULT 'en_attente',
    fournisseur_id INT NOT NULL,
    CONSTRAINT fk_devis_fournisseur FOREIGN KEY (fournisseur_id) REFERENCES fournisseur(id_fournisseur)
);

CREATE INDEX idx_utilisateur_departement ON utilisateur (departement_id);
CREATE INDEX idx_bc_numero ON bon_commande (numero_commande);
CREATE INDEX idx_colis_suivi ON colis (numero_suivi);
