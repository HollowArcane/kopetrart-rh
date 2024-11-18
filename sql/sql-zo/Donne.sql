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



-- departement
INSERT INTO departement (libelle) VALUES
('IT'),
('HR'),
('Finance'),
('Marketing');

-- postes
INSERT INTO postes (libelle) VALUES
('Developer'),
('Manager'),
('Analyst'),
('Executive');

-- postes_depart
INSERT INTO postes_depart (id_departement, id_poste) VALUES
(9, 9),
(10, 10),
(11, 11),
(12, 12);

-- contrat
INSERT INTO contrat (libelle, minMois, maxMois) VALUES
('Full-Time', 12, 24),
('Part-Time', 6, 12),
('Internship', 3, 6),
('Contract', 1, 12);

-- genre
INSERT INTO genre (libelle) VALUES
('Male'),
('Female'),
('Other'),
('Prefer Not to Say');

-- talent
INSERT INTO talent (libelle) VALUES
('Leadership'),
('Technical Skills'),
('Communication'),
('Problem Solving');

-- priorite
INSERT INTO priorite (libelle) VALUES
('High'),
('Medium'),
('Low'),
('Urgent');

-- besoin_poste
INSERT INTO besoin_poste (id_poste, id_genre, id_contrat, ageMin, finValidite, priorite, info_sup) VALUES
(1, 1, 1, 25, '2024-12-31', 1, 'Urgent need for IT department'),
(2, 2, 2, 28, '2024-11-30', 2, 'HR role with flexible contract'),
(3, 3, 3, 21, '2025-01-31', 3, 'Internship in Finance department'),
(4, 1, 4, 30, '2024-12-15', 4, 'Short-term contract in Marketing');

-- talent_poste_besoin
INSERT INTO talent_poste_besoin (id_talent, id_besoin_poste, expMinMax, etudeMinMax) VALUES
(1, 1, '3-5 years', 'Bachelor'),
(2, 2, '2-4 years', 'Bachelor'),
(3, 3, '1-3 years', 'Associate'),
(4, 4, '5+ years', 'Master');

-- dossiers
INSERT INTO dossiers (candidat, email, id_besoin_poste, date_reception, statut, cv, lettre_motivation) VALUES
('John Doe', 'johndoe@example.com', 1, '2024-10-10', 'Pending', NULL, 'Motivated to join the team'),
('Jane Smith', 'janesmith@example.com', 2, '2024-10-15', 'Reviewed', NULL, 'Looking forward to contributing'),
('Mark Lee', 'marklee@example.com', 3, '2024-10-20', 'Interviewed', NULL, 'Excited about the opportunity'),
('Anna Kim', 'annakim@example.com', 4, '2024-10-25', 'Pending', NULL, 'Passionate about this role');

-- experience
INSERT INTO experience (AnneeMin, AnneeMax, libelle) VALUES
(1, 3, 'Junior Level'),
(3, 5, 'Mid Level'),
(5, 10, 'Senior Level'),
(10, 20, 'Expert Level');

-- cv
INSERT INTO cv (id_dossier, status, notes, test, entretien, comparaisonValider) VALUES
(1, 'Reviewed', 'Good skills',null, 'Passed', null),
(2, 'Interviewed', 'Lacks some experience', null, 'Pending', null),
(3, 'Pending', 'Excellent candidate', null, 'Passed', TRUE),
(4, 'Pending', 'Satisfactory performance',null, 'Pending', null);

-- employe
INSERT INTO employe (id_cv, id_departement, id_poste, date, id_contrat) VALUES
(1, 1, 1, '2024-01-01', 1),
(2, 2, 2, '2024-02-01', 2),
(3, 3, 3, '2024-03-01', 3),
(4, 4, 4, '2024-04-01', 4);

-- departement_manager
INSERT INTO departement_manager (id_departement, id_employe) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4);

-- promotion
INSERT INTO promotion (id_employe, id_poste, date, salaire) VALUES
(1, 2, '2024-07-01', 75000.00),
(2, 3, '2024-08-01', 60000.00),
(3, 4, '2024-09-01', 80000.00),
(4, 1, '2024-10-01', 90000.00);

-- model_communication
INSERT INTO model_communication (type, status, model) VALUES
('Email', 'Active', 'Welcome to the team'),
('SMS', 'Inactive', 'Follow up on interview'),
('Notification', 'Active', 'Offer Letter'),
('Letter', 'Pending', 'Onboarding Document');

-- communication
INSERT INTO communication (id_cv, id_model_communication) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4);

-- communication_cv
INSERT INTO communication_cv (id_cv, id_model_communication) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4);

-- classification_cv
INSERT INTO classification_cv (id_cv, experience, id_talent) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(4, 4, 4);

-- typeTest
INSERT INTO typeTest (libelle) VALUES
('Technical Test'),
('Aptitude Test'),
('Personality Test'),
('Language Proficiency Test');

-- test
INSERT INTO test (id_cv, id_typeTest, date, point, observation) VALUES
(1, 1, '2024-11-01', 88.75, 'Strong technical skills'),
(2, 2, '2024-11-02', 74.25, 'Good logical skills'),
(3, 3, '2024-11-03', 81.00, 'Excellent personality fit'),
(4, 4, '2024-11-04', 65.50, 'Needs improvement in language');

-- model_exo
INSERT INTO model_exo (contenu) VALUES
('Solve complex algorithms'),
('Logical puzzles'),
('Describe yourself'),
('Write a paragraph in English');

-- test_note
INSERT INTO test_note (id_test, id_model_exo, note) VALUES
(1, 1, 85.5),
(2, 2, 78.0),
(3, 3, 92.5),
(4, 4, 70.0);

-- entretien
INSERT INTO entretien (id_cv, date_entretien, commentaire, status) VALUES
(1, '2024-10-15', 'Excellent interview', 'Passed'),
(2, '2024-10-20', 'Good potential', 'Pending'),
(3, '2024-10-25', 'Satisfactory', 'Passed'),
(4, '2024-11-01', 'Requires additional screening', 'Pending');

-- contrat_cv
INSERT INTO contrat_cv (id_cv, id_contrat, date_debut, periode, salaire_propose, notes_sup) VALUES
(1, 1, '2024-11-01', 12, 60000.00, 'Offer confirmed'),
(2, 2, '2024-12-01', 6, 45000.00, 'Flexible contract'),
(3, 3, '2025-01-01', 3, 30000.00, 'Internship offer'),
(4, 4, '2024-11-15', 9, 50000.00, 'Probationary period');





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

-- besoin a annonce :
    -- D'abord, ajoutons de nouveaux talents spécifiques
INSERT INTO talent (libelle) VALUES
('Data Science'),
('Cloud Architecture'),
('AI Development'),
('DevOps');

-- Créons un nouveau besoin de poste
INSERT INTO besoin_poste (
    id_poste,
    id_genre,
    id_contrat,
    ageMin,
    finValidite,
    priorite,
    info_sup
) VALUES (
    1,  -- Developer position
    1,  -- Genre
    1,  -- Full-time contract
    25,
    '2024-12-31',
    1,  -- High priority
    'Need developer with modern tech stack skills'
)
RETURNING id;

-- Associons les nouveaux talents au besoin
-- Utilisons les IDs des talents qu'on vient d'insérer (ils devraient être 5,6,7,8)
INSERT INTO talent_poste_besoin (id_talent, id_besoin_poste, expMinMax, etudeMinMax)
SELECT
    t.id,
    (SELECT MAX(id) FROM besoin_poste),  -- Le besoin qu'on vient de créer
    '3-5 years',
    'Master'
FROM talent t
WHERE t.libelle IN ('Data Science', 'Cloud Architecture', 'AI Development', 'DevOps');
