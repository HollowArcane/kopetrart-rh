-- ========== CONTRACT BREACH ==========
-- all mouvement tables start with mvt_
-- all denormalized columns start with d_

-- TABLES:
    DROP TABLE contract_breach_type;
    CREATE TABLE contract_breach_type(
        id SERIAL PRIMARY KEY,
        label VARCHAR(25) NOT NULL UNIQUE
    );

    DROP TABLE contract_breach;
    CREATE TABLE contract_breach(
        id SERIAL PRIMARY KEY,
        id_mvt_staff_contract INT UNIQUE NOT NULL REFERENCES mvt_staff_contract(id) ON DELETE CASCADE,
        id_contract_breach_type INT NOT NULL REFERENCES contract_breach_type(id) ON DELETE CASCADE,
        date_expected DATE NOT NULL,
        comment TEXT NOT NULL,
        id_admin_source INT NOT NULL REFERENCES admin(id_admin) ON DELETE CASCADE,
        date_source DATE NOT NULL,
        id_admin_target INT REFERENCES admin(id_admin) ON DELETE CASCADE,
        date_target DATE,
        salary_bonus NUMERIC(18, 2) NOT NULL,
        date_validated DATE,
        CHECK((SELECT id_staff_contract FROM mvt_staff_contract WHERE id=mvt_staff_contract) = 2 OR id_contract_breach_type <> 4)
    );

-- VIEWS:
    CREATE OR REPLACE VIEW v_mvt_staff_status AS
        SELECT
            msc.id_staff,
            2 AS id_staff_status, -- Inactif
            cb.date_validated AS date_status
        FROM
            contract_breach cb
        JOIN
            mvt_staff_contract msc ON cb.id_mvt_staff_contract = msc.id
        WHERE date_validated IS NOT NULL
        UNION ALL
        SELECT
            msc.id_staff,
            1 AS id_staff_status, -- Actif
            msc.date_start AS date_status
        FROM
            mvt_staff_contract msc;
-- TRIGGERS:

-- CONSTANTS:
    INSERT INTO contract_breach_type(id, label) VALUES(1, 'Démission');
    INSERT INTO contract_breach_type(id, label) VALUES(2, 'Licenciement');
    INSERT INTO contract_breach_type(id, label) VALUES(3, 'Mise à la Retraite');
    INSERT INTO contract_breach_type(id, label) VALUES(4, 'Rupture Conventionelle');
