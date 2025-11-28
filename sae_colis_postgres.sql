-- version PostgreSQL
-- `CREATE DATABASE suivi_colis_sae;` puis this-> `\c suivi_colis_sae`.

-- Table departement
CREATE TABLE IF NOT EXISTS departement (
    id_departement integer PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE,
    telephone VARCHAR(20),
    budget_total INT DEFAULT 0,
    budget_utilise INT DEFAULT 0
);

-- Table role
CREATE TABLE IF NOT EXISTS role (
    id_role integer PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

-- Table utilisateur (auth CAS)
CREATE TABLE IF NOT EXISTS utilisateur (
    id_utilisateur integer PRIMARY KEY,
    uid_cas VARCHAR(80) NOT NULL UNIQUE,
    access_token_api_cas VARCHAR(200) NOT NULL,
    full_name VARCHAR(80) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    role_id INT NOT NULL,
    departement_id INT,
    FOREIGN KEY (role_id) REFERENCES role(id_role),
    FOREIGN KEY (departement_id) REFERENCES departement(id_departement) ON DELETE SET NULL
);

-- Table fournisseur
CREATE TABLE IF NOT EXISTS fournisseur (
    id_fournisseur integer PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    contact_nom VARCHAR(120),
    contact_email VARCHAR(120),
    contact_telephone VARCHAR(30)
);

-- Table bon_commande
CREATE TABLE IF NOT EXISTS bon_commande (
    id_bon_commande integer PRIMARY KEY,
    numero_commande VARCHAR(50) NOT NULL UNIQUE,
    date_commande DATE NOT NULL,
    date_estimee_livraison DATE,
    montant_estime NUMERIC(10,2) DEFAULT 0,
    statut VARCHAR(30) DEFAULT 'en preparation',
    departement_id INT NOT NULL,
    fournisseur_id INT NOT NULL,
    createur_id INT NOT NULL,
    commentaire TEXT,
    FOREIGN KEY (departement_id) REFERENCES departement(id_departement),
    FOREIGN KEY (fournisseur_id) REFERENCES fournisseur(id_fournisseur),
    FOREIGN KEY (createur_id) REFERENCES utilisateur(id_utilisateur)
);

-- Table bon_commande_ligne
CREATE TABLE IF NOT EXISTS bon_commande_ligne (
    id_ligne integer PRIMARY KEY,
    bon_commande_id INT NOT NULL,
    designation VARCHAR(255) NOT NULL,
    quantite INT NOT NULL DEFAULT 1,
    prix_unitaire NUMERIC(10,2) DEFAULT 0,
    FOREIGN KEY (bon_commande_id) REFERENCES bon_commande(id_bon_commande)
);

-- Table statut_colis
CREATE TABLE IF NOT EXISTS statut_colis (
    id_statut integer PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

-- Table colis
CREATE TABLE IF NOT EXISTS colis (
    id_colis integer PRIMARY KEY,
    bon_commande_id INT NOT NULL,
    statut_id INT NOT NULL,
    code_barres VARCHAR(128),
    numero_suivi VARCHAR(128),
    destinataire_nom VARCHAR(120),
    destinataire_bureau VARCHAR(80),
    date_reception DATE,
    date_retrait TIMESTAMP WITHOUT TIME ZONE,
    commentaire TEXT,
    FOREIGN KEY (bon_commande_id) REFERENCES bon_commande(id_bon_commande),
    FOREIGN KEY (statut_id) REFERENCES statut_colis(id_statut)
);

-- Table notification
CREATE TABLE IF NOT EXISTS notification (
    id_notification integer PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    message VARCHAR(255) NOT NULL,
    date_envoi TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    lu BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur) ON DELETE CASCADE
);

-- Table devis
CREATE TABLE IF NOT EXISTS devis (
    id_devis integer PRIMARY KEY,
    date_demande DATE NOT NULL,
    objet VARCHAR(255),
    montant_estime NUMERIC(10,2),
    fichier_pdf BYTEA,
    statut VARCHAR(50) DEFAULT 'en_attente',
    fournisseur_id INT NOT NULL,
    CONSTRAINT fk_devis_fournisseur FOREIGN KEY (fournisseur_id) REFERENCES fournisseur(id_fournisseur)
);

-- Index
CREATE INDEX IF NOT EXISTS idx_utilisateur_departement ON utilisateur (departement_id);
CREATE INDEX IF NOT EXISTS idx_bc_numero ON bon_commande (numero_commande);
CREATE INDEX IF NOT EXISTS idx_colis_suivi ON colis (numero_suivi);
