CREATE TABLE historique_colis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    colis_id INT NOT NULL,
    action VARCHAR(255) NOT NULL,
    date_action DATETIME DEFAULT CURRENT_TIMESTAMP,
    utilisateur_id INT NULL,
    
    FOREIGN KEY (colis_id) REFERENCES colis(id_colis),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id_utilisateur)
);
