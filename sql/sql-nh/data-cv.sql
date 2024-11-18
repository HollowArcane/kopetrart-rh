--
-- PRIORITE
-- 
/* ID : 9 - 10 - 11 - 12 */

--
-- POSTE
-- 
/* ID : 9 - 10 - 11 - 12 */

--
-- GENRE
-- 
/* ID : 5 - 6 */

--
-- CONTRAT
-- 
/* ID : 5 - 6 */



--
-- BESOIN POSTE 20
--

/* DEV */
-- ID : 3
INSERT INTO besoin_poste (id_poste, id_genre, id_contrat, ageMin, finValidite, priorite, info_sup, personnelTrouver, status) 
VALUES (1, 2, 1, 30, '2025-12-31', 1, 'Poste de developper Senior',NULL,NULL);

--
-- DOSSIER : 3 -> 12
-- 
INSERT INTO dossiers (candidat, email, id_besoin_poste, date_reception, statut, cv, lettre_motivation, estTraduit) 
VALUES ('Mika', 'john.doe@example.com', 1, '2024-11-14', 'Received', NULL, NULL, NULL);

INSERT INTO dossiers (candidat, email, id_besoin_poste, date_reception, statut, cv, lettre_motivation, estTraduit) 
VALUES ('Jean', 'john.doe@example.com', 1, '2024-11-14', 'Received', NULL, NULL, NULL);


INSERT INTO dossiers (candidat, email, id_besoin_poste, date_reception, statut, cv, lettre_motivation, estTraduit) 
VALUES ('Lita', 'john.doe@example.com', 1, '2024-11-14', 'Received', NULL, NULL, NULL);


INSERT INTO dossiers (candidat, email, id_besoin_poste, date_reception, statut, cv, lettre_motivation, estTraduit) 
VALUES ('Koto', 'john.doe@example.com', 1, '2024-11-14', 'Received', NULL, NULL, NULL);


INSERT INTO dossiers (candidat, email, id_besoin_poste, date_reception, statut, cv, lettre_motivation, estTraduit) 
VALUES ('José', 'john.doe@example.com', 1, '2024-11-14', 'Received', NULL, NULL, NULL);


INSERT INTO dossiers (candidat, email, id_besoin_poste, date_reception, statut, cv, lettre_motivation, estTraduit) 
VALUES ('André', 'john.doe@example.com', 1, '2024-11-14', 'Received', NULL, NULL, NULL);

INSERT INTO dossiers (candidat, email, id_besoin_poste, date_reception, statut, cv, lettre_motivation, estTraduit) 
VALUES ('Soa', 'john.doe@example.com', 1, '2024-11-14', 'Received', NULL, NULL, NULL);


INSERT INTO dossiers (candidat, email, id_besoin_poste, date_reception, statut, cv, lettre_motivation, estTraduit) 
VALUES ('Eric', 'john.doe@example.com', 1, '2024-11-14', 'Received', NULL, NULL, NULL);


INSERT INTO dossiers (candidat, email, id_besoin_poste, date_reception, statut, cv, lettre_motivation, estTraduit) 
VALUES ('Zaka', 'john.doe@example.com', 1, '2024-11-14', 'Received', NULL, NULL, NULL);


INSERT INTO dossiers (candidat, email, id_besoin_poste, date_reception, statut, cv, lettre_motivation, estTraduit) 
VALUES ('Pierre', 'john.doe@example.com', 1, '2024-11-14', 'Received', NULL, NULL, NULL);

--
-- CV
--
INSERT INTO cv (id_dossier, status, notes, test, entretien, comparaisonValider, bonus, informer) 
VALUES (1, 'Rejected', 'Lacks relevant experience.', 'Failed', NULL, NULL, 0, NULL);

INSERT INTO cv (id_dossier, status, notes, test, entretien, comparaisonValider, bonus, informer) 
VALUES (2, 'Under Review', 'Good fit for the team.', 'Passed', 'Scheduled', 'Pending', 5, NULL);

INSERT INTO cv (id_dossier, status, notes, test, entretien, comparaisonValider, bonus, informer) 
VALUES (3, 'Interviewed', 'Impressive portfolio.', 'Passed', 'Completed', 'Approved', 20, NULL);

INSERT INTO cv (id_dossier, status, notes, test, entretien, comparaisonValider, bonus, informer) 
VALUES (4, 'Under Review', 'Needs improvement in coding tests.', 'Failed', NULL, NULL, 2, NULL);

INSERT INTO cv (id_dossier, status, notes, test, entretien, comparaisonValider, bonus, informer) 
VALUES (6, 'Accepted', 'Excellent problem-solving skills.', 'Passed', 'Completed', 'Approved', 25, NULL);

INSERT INTO cv (id_dossier, status, notes, test, entretien, comparaisonValider, bonus, informer) 
VALUES (7, 'Rejected', 'Not a cultural fit.', 'Failed', NULL, NULL, 0, NULL);

INSERT INTO cv (id_dossier, status, notes, test, entretien, comparaisonValider, bonus, informer) 
VALUES (8, 'Under Review', 'Potential for growth.', 'Passed', 'Scheduled', 'Pending', 10, NULL);

INSERT INTO cv (id_dossier, status, notes, test, entretien, comparaisonValider, bonus, informer) 
VALUES (9,'Interviewed','Strong leadership qualities.','Passed','Completed','Approved',30,NULL);

INSERT INTO cv (id_dossier, status, notes, test, entretien, comparaisonValider, bonus, informer) 
VALUES (10,'Interviewed','Strong leadership qualities.','Passed','Completed','Approved',30,NULL);