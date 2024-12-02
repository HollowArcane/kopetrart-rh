--
-- ABSENCE
--
CREATE TABLE absence (
    id SERIAL PRIMARY KEY,
    id_staff INT NOT NULL REFERENCES staff(id),
    number_day_absence DECIMAL(10, 2) NOT NULL,  -- 1 day, 0.5 day (half a day)
    date_absence DATE NOT NULL
);

--
-- OVERTIME TYPE
--
CREATE TABLE overtime_type (
    id SERIAL PRIMARY KEY,
    label VARCHAR(255) NOT NULL,
    rate DECIMAL(10, 2) NOT NULL
);

--
-- OVERTIME SHIFT
--
CREATE TABLE overtime_shift (
    id SERIAL PRIMARY KEY,
    label VARCHAR(255) NOT NULL
);

--
-- STAFF OVERTIME
--
CREATE TABLE staff_overtime (
    id SERIAL PRIMARY KEY,
    id_staff INT NOT NULL REFERENCES staff(id),
    id_overtime_type INT NOT NULL REFERENCES overtime_type(id),
    id_overtime_shift INT NOT NULL REFERENCES overtime_shift(id),
    date_overtime DATE NOT NULL,
    quantity_overtime DECIMAL(10, 2) NOT NULL
);

--
-- EMPLOYEE COMPENSATION (indemnité)
--
CREATE TABLE staff_compensation (
    id SERIAL PRIMARY KEY,
    id_staff INT NOT NULL REFERENCES staff(id),
    motif VARCHAR(255) NOT NULL,
    date_compensation DATE NOT NULL,
    amount DECIMAL(10, 2) NOT NULL
);

--
-- SMIG
--
CREATE TABLE smig (
    id SERIAL PRIMARY KEY,
    previous_amount DECIMAL(10, 2) NOT NULL,        -- current amount of SMIG
    next_amount DECIMAL(10, 2) DEFAULT NULL,        -- new SMIG
    date_start DATE NOT NULL,
    date_annonce_next_amount DATE DEFAULT NULL,     -- annonce of the new SMIG
    date_effective_next_amount DATE DEFAULT NULL    -- `entrée en vigueur` of the next SMIG
);

--
--  PERFORMANCE BONUS
--
CREATE TABLE performance_bonus (
    id SERIAL PRIMARY KEY,
    id_staff INT NOT NULL REFERENCES staff(id),
    date_bonus DATE NOT NULL,
    performance DECIMAL(10, 2) NOT NULL -- in percentage (e.g: 150%)
);

--
-- IMPOT DUE
--
CREATE TABLE impot_due (
    id SERIAL PRIMARY KEY,
    id_staff INT NOT NULL REFERENCES staff(id),
    date_due DATE NOT NULL,
    amount DECIMAL(10, 2) NOT NULL
);

--
-- SALARY ADVANCE
--
CREATE TABLE salary_advance (
    id SERIAL PRIMARY KEY,
    id_staff INT NOT NULL REFERENCES staff(id),
    date_advance DATE NOT NULL,
    amount DECIMAL(10, 2) NOT NULL
);
