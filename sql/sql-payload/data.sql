--
-- CONSTANT
--
INSERT INTO overtime_type (id, label, rate)
VALUES
(1, '8 premiere heures', 1.3),
(2, '12 heures restant', 1.5),
(3, 'week-end', 1.4),
(4, 'jours ferie', 2);

INSERT INTO overtime_shift (id, label)
VALUES
(1, 'Jour'),
(2, 'Nuit');

INSERT INTO smig (id, previous_amount, date_start)
VALUES (1, 250000.00, '2024-27-11');    /* current smig */

/* adding a new amount for the smig */
UPDATE smig SET next_amount = 350000.00 WHERE id = 1;
UPDATE smig SET date_annonce_next_amount = '2025-02-10' WHERE id = 1;
UPDATE smig SET date_effective_next_amount = '2025-05-10' WHERE id = 1;

UPDATE staff SET d_salary = 250000.00 WHERE id = 3;

--
-- STAFF
--
INSERT INTO staff (id, first_name, last_name, email, date_birth, d_staff_status, d_salary, d_id_staff_position, d_id_department, d_id_staff_contract, d_date_contract_start, d_date_contract_end)
VALUES (1, 'John', 'Doe', 'john.doe@example.com', '1990-01-01', 1, 300000.00, 1, 1, 1, '2024-01-01', '2025-01-01');

INSERT INTO staff (id, first_name, last_name, email, date_birth, d_staff_status, d_salary, d_id_staff_position, d_id_department, d_id_staff_contract, d_date_contract_start, d_date_contract_end)
VALUES (2, 'Jane', 'Smith', 'jane.smith@example.com', '1985-05-15', 1, 600000.00, 2, 2, 2, '2024-02-01', NULL);

-- SMIG employee
INSERT INTO staff (id, first_name, last_name, email, date_birth, d_staff_status, d_salary, d_id_staff_position, d_id_department, d_id_staff_contract, d_date_contract_start, d_date_contract_end)
VALUES (3, 'Bob', 'Martin', 'bob.martin@example.com', '1990-01-15', 2, 250000, 3, 3, 2, '2024-02-01', NULL);

-- senior employee
INSERT INTO staff (id, first_name, last_name, email, date_birth, d_staff_status, d_salary, d_id_staff_position, d_id_department, d_id_staff_contract, d_date_contract_start, d_date_contract_end) 
VALUES (4, 'Jean', 'Jean', 'jean.jean@example.com', '1975-01-15', 2, 3000000, 3, 3, 2, '2015-02-01', NULL);  
