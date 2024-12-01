--
-- STAFF
--
CREATE TABLE staff(
    id SERIAL PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255),
    email VARCHAR(255) NOT NULL,
    UNIQUE(first_name, last_name),
    date_birth DATE NOT NULL,
    -- denormalization
    d_staff_status INT REFERENCES staff_status(id) ON DELETE SET NULL, -- references mvt_staff_contract, null means has not been hired yet
    d_salary NUMERIC(14, 2), -- references mvt_staff_contract.salary or mvt_staff_promotion.salary
    d_id_staff_position INT REFERENCES staff_position(id) ON DELETE SET NULL, -- references mvt_staff_contract.position or mvt_staff_promotion.position
    d_id_department INT REFERENCES department(id) ON DELETE SET NULL, -- references mvt_staff_contract.id_department or mvt_staff_promotion.id_department
    d_id_staff_contract INT REFERENCES staff_contract(id) ON DELETE SET NULL, -- references mvt_staff_contract.id_staff_contract
    d_date_contract_start DATE, -- references mvt_staff_contract.date_start
    d_date_contract_end DATE -- references mvt_staff_contract.date_end
);

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