DROP DATABASE recrutement;

CREATE DATABASE recrutement;
-- SQL Script to Create Schema
\c recrutement;

-- SQL Script to Create Schema

CREATE TABLE role(
    id SERIAL PRIMARY KEY,
    libelle VARCHAR(25)
);

CREATE TABLE admin(
    id_admin SERIAL PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(255),
    id_profil INTEGER NOT NULL REFERENCES role(id)
);


-- 1. departement
CREATE TABLE departement (
    id SERIAL PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL
);

-- 2. postes
CREATE TABLE postes (
    id SERIAL PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL
);

-- 3. postes_depart
CREATE TABLE postes_depart (
    id SERIAL PRIMARY KEY,
    id_departement INTEGER NOT NULL REFERENCES departement(id),
    id_poste INTEGER NOT NULL REFERENCES postes(id)
);

-- 4. contrat
CREATE TABLE contrat (
    id SERIAL PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL,
    minMois INTEGER NOT NULL,
    maxMois INTEGER NOT NULL
);


-- 8. genre
CREATE TABLE genre (
    id SERIAL PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
);

-- 9. talent
CREATE TABLE talent (
    id SERIAL PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL
);

-- 10. priorite
CREATE TABLE priorite (
    id SERIAL PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL
);

-- 11. besoin_poste
CREATE TABLE besoin_poste (
    id SERIAL PRIMARY KEY,
    id_poste INTEGER NOT NULL REFERENCES postes(id),
    id_genre INTEGER REFERENCES genre(id),
    id_contrat INTEGER NOT NULL REFERENCES contrat(id),
    ageMin INTEGER,
    finValidite DATE NOT NULL,
    priorite INTEGER REFERENCES priorite(id),
    info_sup TEXT,
    personnelTrouver BOOLEAN DEFAULT FALSE,
    status BOOLEAN DEFAULT FALSE
);

-- 12. talent_poste_besoin
CREATE TABLE talent_poste_besoin (
    id SERIAL PRIMARY KEY,
    id_talent INTEGER NOT NULL REFERENCES talent(id),
    id_besoin_poste INTEGER NOT NULL REFERENCES besoin_poste(id),
    expMin INTEGER,
    expMax INTEGER,
    etude INTEGER
);

-- 13. dossiers
CREATE TABLE dossiers (
    id SERIAL PRIMARY KEY,
    candidat VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    id_besoin_poste INTEGER NOT NULL REFERENCES besoin_poste(id),
    date_reception DATE NOT NULL,
    statut VARCHAR(50) NOT NULL,
    cv BYTEA,
    lettre_motivation TEXT
);

ALTER TABLE dossiers
ALTER COLUMN cv TYPE TEXT;

ALTER TABLE dossiers
add COLUMN estTraduit  BOOLEAN DEFAULT FALSE;

-- 15. cv
CREATE TABLE cv (
    id SERIAL PRIMARY KEY,
    id_dossier INTEGER NOT NULL REFERENCES dossiers(id),
    status VARCHAR(50),
    notes TEXT,
    test VARCHAR(50),
    entretien VARCHAR(50),
    comparaisonValider VARCHAR(50),
    bonus INTEGER,
    informer BOOLEAN
);
-- 5. employe
CREATE TABLE employe (
    id SERIAL PRIMARY KEY,
    id_cv INTEGER NOT NULL REFERENCES cv(id),
    id_departement INTEGER NOT NULL REFERENCES departement(id),
    id_poste INTEGER NOT NULL REFERENCES postes(id),
    date DATE NOT NULL,
    id_contrat INTEGER NOT NULL REFERENCES contrat(id)
);

-- 6. departement_manager
CREATE TABLE departement_manager (
    id SERIAL PRIMARY KEY,
    id_departement INTEGER NOT NULL REFERENCES departement(id),
    id_employe INTEGER NOT NULL REFERENCES employe(id)
);

-- 7. promotion
CREATE TABLE promotion (
    id SERIAL PRIMARY KEY,
    id_employe INTEGER NOT NULL REFERENCES employe(id),
    id_poste INTEGER NOT NULL REFERENCES postes(id),
    date DATE NOT NULL,
    salaire NUMERIC(10, 2) NOT NULL,
    status BOOLEAN DEFAULT FALSE
);

CREATE TABLE model_communication (
    id SERIAL PRIMARY KEY,
    type VARCHAR(50) NOT NULL,
    status VARCHAR(50),
    model TEXT
);

-- 19. communication
CREATE TABLE communication (
    id SERIAL PRIMARY KEY,
    id_cv INTEGER NOT NULL REFERENCES cv(id),
    id_model_communication INTEGER NOT NULL REFERENCES model_communication(id),
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 16. communication_cv
CREATE TABLE communication_cv (
    id SERIAL PRIMARY KEY,
    id_cv INTEGER NOT NULL REFERENCES cv(id),
    id_model_communication INTEGER NOT NULL REFERENCES model_communication(id)
);

-- 17. classification_cv
CREATE TABLE classification_cv (
    id SERIAL PRIMARY KEY,
    id_cv INTEGER NOT NULL REFERENCES cv(id),
    experience INTEGER ,
    id_talent INTEGER REFERENCES talent(id)
);

CREATE TABLE test(
    id SERIAL PRIMARY KEY,
    id_need INT NOT NULL REFERENCES besoin_poste(id) ON DELETE CASCADE,
    title VARCHAR(100) NOT NULL,
    goal TEXT NOT NULL,
    requirements TEXT NOT NULL
);

-- duration is in minutes (mn)
CREATE TABLE test_part(
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    duration INT NOT NULL,
    id_test INT NOT NULL REFERENCES test(id) ON DELETE CASCADE
);

CREATE TABLE test_point_importance(
    id SERIAL PRIMARY KEY,
    label VARCHAR(10) NOT NULL
);

CREATE TABLE test_point(
    id SERIAL PRIMARY KEY,
    label VARCHAR(255) NOT NULL,
    id_importance INT NOT NULL REFERENCES test_point_importance(id) ON DELETE CASCADE,
    id_test INT NOT NULL REFERENCES test(id) ON DELETE CASCADE
);

CREATE TABLE test_criterion(
    id SERIAL PRIMARY KEY,
    label VARCHAR(255) NOT NULL,
    coefficient INT NOT NULL DEFAULT 1,
    id_test INT NOT NULL REFERENCES test(id) ON DELETE CASCADE
);

-- || TEST CANDIDATE RESULT || --
CREATE TABLE test_candidate_result(
    id SERIAL PRIMARY KEY,
    label VARCHAR(10) NOT NULL
);

-- || TEST CANDIDATE || --
CREATE TABLE test_candidate(
    id SERIAL PRIMARY KEY,
    id_test INT NOT NULL REFERENCES test(id) ON DELETE CASCADE,
    id_cv_candidate INT NOT NULL REFERENCES cv(id) ON DELETE CASCADE,
    date_received DATE NOT NULL,
    file VARCHAR(50) NOT NULL,
    date_validated DATE,
    -- denormalized
    score REAL NOT NULL DEFAULT 0,
    id_result INT NOT NULL REFERENCES test_candidate_result(id) ON DELETE CASCADE DEFAULT 3,
    status BOOLEAN DEFAULT FALSE,
    is_communication_send BOOLEAN DEFAULT FALSE
);

CREATE TABLE test_candidate_point(
    id SERIAL PRIMARY KEY,
    id_point INT NOT NULL REFERENCES test_point(id),
    id_test_candidate INT NOT NULL REFERENCES test_candidate(id)
);

CREATE TABLE test_candidate_criteria(
    id SERIAL PRIMARY KEY,
    id_criterion INT NOT NULL REFERENCES test_criterion(id),
    id_test_candidate INT NOT NULL REFERENCES test_candidate(id),
    value INT NOT NULL
);

-- 24. entretien
CREATE TABLE entretien (
    id SERIAL PRIMARY KEY,
    id_cv INTEGER NOT NULL REFERENCES cv(id),
    date_entretien DATE NOT NULL,
    commentaire TEXT,
    status VARCHAR(50)
);
ALTER TABLE entretien
ADD COLUMN informer BOOLEAN;
ALTER TABLE entretien
ADD COLUMN valide BOOLEAN;


-- 25. contrat_cv
CREATE TABLE contrat_cv (
    id SERIAL PRIMARY KEY,
    id_cv INTEGER NOT NULL REFERENCES cv(id),
    id_contrat INTEGER NOT NULL REFERENCES contrat(id),
    date_debut DATE NOT NULL,
    periode INTEGER NOT NULL,
    salaire_propose NUMERIC(10, 2) NOT NULL,
    notes_sup TEXT
);

-- Création de la table moyenne_comm
CREATE TABLE moyenne_comm (
    id SERIAL PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL
);

-- Création de la table annonce
CREATE TABLE annonce (
    id SERIAL PRIMARY KEY,
    id_besoin_poste INTEGER NOT NULL REFERENCES besoin_poste(id),
    is_validate BOOLEAN DEFAULT FALSE
);

-- Création de la table annonce_communication
CREATE TABLE annonce_communication (
    id SERIAL PRIMARY KEY,
    id_annonce INTEGER NOT NULL REFERENCES annonce(id),
    id_moyenne_comm INTEGER NOT NULL REFERENCES moyenne_comm(id),
    date DATE NOT NULL
);
