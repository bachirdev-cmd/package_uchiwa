DO $$
BEGIN
   IF NOT EXISTS (
      SELECT FROM pg_database WHERE datname = 'app_daf'
   ) THEN
      CREATE DATABASE app_daf;
   END IF;
END
$$;


\c app_daf;

CREATE TABLE citoyen (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    date_naissance DATE NOT NULL,
    lieu_naissance VARCHAR(150) NOT NULL,
    cni VARCHAR(20) UNIQUE NOT NULL,
    cni_recto_url TEXT NOT NULL, 
    cni_verso_url TEXT NOT NULL, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE log (
    id SERIAL PRIMARY KEY,
    date DATE NOT NULL,                    
    heure TIME NOT NULL,                   
    localisation VARCHAR(255) NOT NULL,  
    ip_address VARCHAR(45) NOT NULL,  
    statut VARCHAR(10) CHECK (statut IN ('SUCCES', 'ERROR')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_citoyen_cni ON citoyen(cni);

INSERT INTO citoyen (nom, prenom, date_naissance, lieu_naissance, cni, cni_recto_url, cni_verso_url)
VALUES 
('Diop', 'Mamadou', '1990-05-12', 'Dakar', 'CNI1234567890', 
 'https://drive.google.com/uc?id=1A2B3C4D5E6F7G8H9I0J_recto', 
 'https://drive.google.com/uc?id=1A2B3C4D5E6F7G8H9I0J_verso'),

('Fall', 'Awa', '1985-09-23', 'Thiès', 'CNI9876543210',
 'https://www.dropbox.com/s/example/cni9876543210_recto.jpg?dl=1',
 'https://www.dropbox.com/s/example/cni9876543210_verso.jpg?dl=1'),

('Ba', 'Cheikh', '1993-02-01', 'Saint-Louis', 'CNI4567891230',
 'https://images.unsplash.com/photo-1592194996308-7b43878e84a6',
 'https://images.unsplash.com/photo-1592195000206-3e8a86c8ef1e');