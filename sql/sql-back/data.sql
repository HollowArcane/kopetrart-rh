-- UTILISATEURS
INSERT INTO role (id, libelle) VALUES
(1, 'PDG'),
(2, 'Responsable RH'),
(3, 'Responsable equipe'),
(4, 'Responsable communication'),
(5, 'Charge de recrutement');


CREATE EXTENSION IF NOT EXISTS pgcrypto;




INSERT INTO admin (username, password, id_profil) VALUES
-- PDG
('PDG', crypt('pdg',gen_salt('bf')), 1),
-- Responsables RH
('RH', crypt('rh',gen_salt('bf')), 2),
-- Responsables d'équipe
('RE', crypt('re',gen_salt('bf')), 3),
-- Responsables communication
('RC', crypt('rc',gen_salt('bf')), 4),
-- Chargés de recrutement
('CR', crypt('cr',gen_salt('bf')), 5);


-- postes
INSERT INTO postes (libelle) VALUES
('Developer'),
('Manager'),
('Analyst'),
('Executive');

-- genre
INSERT INTO genre (libelle) VALUES
('Male'),
('Female');

--Contrat
INSERT INTO contrat (libelle, minMois, maxMois) VALUES
('CDD', 12, 24),
('CDI', 6, 12);
-- priorite
INSERT INTO priorite (libelle) VALUES
('High'),
('Medium'),
('Low'),
('Urgent');


-- talent
INSERT INTO talent (libelle) VALUES
('Leadership'),
('PHP'),
('Communication'),
('Problem Solving');


-- departement
INSERT INTO departement (libelle) VALUES
('IT'),
('HR'),
('Finance'),
('Marketing');


-- postes_depart
INSERT INTO postes_depart (id_departement, id_poste) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4);

-- Insérer quelques données de test pour moyenne_comm
INSERT INTO moyenne_comm (libelle) VALUES
('LinkedIn'),
('Site web entreprise'),
('Indeed'),
('Facebook'),
('Instagram'),
('Email marketing'),
('Job boards specialises'),
('Journaux locaux'),
('Agences de recrutement'),
('Reseaux professionnels');


-- PARTIE TEST
INSERT INTO test_candidate_result(id, label) VALUES(1, 'Valide');
INSERT INTO test_candidate_result(id, label) VALUES(2, 'Echec');
INSERT INTO test_candidate_result(id, label) VALUES(3, 'En Attente');

INSERT INTO test_point_importance(id, label) VALUES(1, 'Blocant');
INSERT INTO test_point_importance(id, label) VALUES(2, 'Important');
INSERT INTO test_point_importance(id, label) VALUES(3, 'Bonus');
