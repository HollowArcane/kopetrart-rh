-- ========== CONTRACT BREACH ==========
-- all mouvement tables start with mvt_
-- all denormalized columns start with d_

-- TABLES:
    DROP TABLE department CASCADE;
    CREATE TABLE department(
        id SERIAL PRIMARY KEY,
        label VARCHAR(255) UNIQUE NOT NULL
    );

    DROP TABLE staff_status CASCADE;
    CREATE TABLE staff_status(
        id SERIAL PRIMARY KEY,
        label VARCHAR(255) UNIQUE NOT NULL
    );

    DROP TABLE staff_contract CASCADE;
    CREATE TABLE staff_contract(
        id SERIAL PRIMARY KEY,
        label VARCHAR(255) UNIQUE NOT NULL,
        renewal_available INT NOT NULL DEFAULT -1, -- -1 is for infinity
        max_period_month INT NOT NULL DEFAULT -1 -- -1 is for infinity
    );

    DROP TABLE staff_position_category CASCADE;
    CREATE TABLE staff_position_category(
        id SERIAL PRIMARY KEY,
        label VARCHAR(255) UNIQUE NOT NULL
    );

    DROP TABLE staff_position CASCADE;
    CREATE TABLE staff_position(
        id SERIAL PRIMARY KEY,
        label VARCHAR(255) UNIQUE NOT NULL,
        id_staff_position_category INT NOT NULL REFERENCES staff_position_category(id) ON DELETE CASCADE
    );

    DROP TABLE staff CASCADE;
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

    DROP TABLE mvt_staff_contract CASCADE;
    CREATE TABLE mvt_staff_contract(
        id SERIAL PRIMARY KEY,
        id_staff INT NOT NULL REFERENCES staff(id) ON DELETE CASCADE,
        id_staff_contract INT NOT NULL REFERENCES staff_contract(id) ON DELETE CASCADE,
        id_staff_position INT NOT NULL REFERENCES staff_position(id) ON DELETE CASCADE,
        id_department INT NOT NULL REFERENCES department(id) ON DELETE CASCADE,
        salary NUMERIC(14, 2) NOT NULL,
        date_start DATE NOT NULL,
        date_end DATE,
        CHECK(id_staff_contract = 2 AND date_end IS NOT NULL OR date_start < date_end)
    );

    DROP TABLE mvt_staff_promotion CASCADE;
    CREATE TABLE mvt_staff_promotion(
        id SERIAL PRIMARY KEY,
        id_staff INT NOT NULL REFERENCES staff(id) ON DELETE CASCADE,
        id_staff_position INT NOT NULL REFERENCES staff_position(id) ON DELETE CASCADE,
        id_department INT NOT NULL REFERENCES department(id) ON DELETE CASCADE,
        salary NUMERIC(14, 2) NOT NULL,
        date DATE NOT NULL
    );

-- VIEWS:
    DROP VIEW v_lib_staff CASCADE;
    CREATE OR REPLACE VIEW v_lib_staff AS
        SELECT
            s.id,
            s.first_name,
            s.last_name,
            s.date_birth,
            s.email,
            s.d_staff_status,
            s.d_salary,
            s.d_id_staff_position,
            sp.label AS staff_position,
            s.d_id_staff_contract,
            sc.label AS staff_contract,
            s.d_date_contract_start,
            s.d_date_contract_end,
            s.d_id_department,
            d.label AS department
        FROM
            staff s
        JOIN
            staff_contract sc ON s.d_id_staff_contract = sc.id
        JOIN
            staff_position sp ON s.d_id_staff_position = sp.id
        JOIN
            department d ON s.d_id_department = d.id
    ;

    DROP VIEW v_lib_staff_active CASCADE;
    CREATE OR REPLACE VIEW v_lib_staff_active AS
        SELECT
            id,
            first_name,
            last_name,
            date_birth,
            email,
            d_staff_status,
            d_salary,
            d_id_staff_position,
            staff_position,
            d_id_staff_contract,
            staff_contract,
            d_date_contract_start,
            d_date_contract_end,
            d_id_department,
            department
        FROM
            v_lib_staff
        WHERE
            d_staff_status = 1
    ;

    DROP VIEW v_mvt_staff_position_salary CASCADE;
    CREATE OR REPLACE VIEW v_mvt_staff_position_salary AS
        SELECT
            id_staff,
            id_staff_position,
            id_department,
            salary,
            date_start
        FROM
            mvt_staff_contract
        UNION ALL
        SELECT
            id_staff,
            id_staff_position,
            id_department,
            salary,
            date
        FROM
            mvt_staff_promotion
    ;

-- TRIGGERS:
    DROP PROCEDURE p_staff_salary_position;
    CREATE OR REPLACE PROCEDURE p_staff_salary_position(
            id_staff_param INT,
            OUT p_salary NUMERIC,
            OUT p_id_staff_position INT,
            OUT p_id_department INT
        )
        LANGUAGE plpgsql AS $$
        BEGIN
            SELECT
                msps.salary,
                msps.id_staff_position,
                msps.id_department
            INTO
                p_salary,
                p_id_staff_position,
                p_id_department
            FROM
                v_mvt_staff_position_salary msps
            JOIN (
                SELECT
                    _msps.id_staff,
                    MAX(_msps.date_start) AS max_date_start
                FROM
                    v_mvt_staff_position_salary _msps
                WHERE
                    _msps.id_staff = id_staff_param
                GROUP BY
                    _msps.id_staff
            ) msps2 ON msps.id_staff = msps2.id_staff AND msps.date_start = msps2.max_date_start
            WHERE
                msps.id_staff = id_staff_param;
        END;
    $$;

    DROP PROCEDURE p_staff_contract;
    CREATE OR REPLACE PROCEDURE p_staff_contract(
            id_staff_param INT,
            OUT p_date_contract_start DATE,
            OUT p_date_contract_end DATE,
            OUT p_id_staff_contract INT
        )
        LANGUAGE plpgsql AS $$
        BEGIN
            SELECT
                msc.date_start,
                msc.date_end,
                msc.id_staff_contract
            INTO
                p_date_contract_start,
                p_date_contract_end,
                p_id_staff_contract
            FROM
                mvt_staff_contract msc
            JOIN (
                SELECT
                    _msc.id_staff,
                    MAX(_msc.date_start) AS max_date_start
                FROM
                    mvt_staff_contract _msc
                WHERE
                    _msc.id_staff = id_staff_param
                GROUP BY
                    _msc.id_staff
            ) msc2 ON msc.id_staff = msc2.id_staff AND msc.date_start = msc2.max_date_start
            WHERE
                msc.id_staff = id_staff_param;
        END;
    $$;

    DROP FUNCTION fn_staff_contract;
    CREATE OR REPLACE FUNCTION fn_staff_contract()
        RETURNS TRIGGER AS $$
        DECLARE
            date_contract_start DATE;
            date_contract_end DATE;
            id_staff_contract INT;
            salary NUMERIC(14, 2);
            id_staff_position INT;
            id_department INT;
        BEGIN
            IF TG_OP = 'UPDATE' OR TG_OP = 'INSERT' THEN
                CALL p_staff_contract(NEW.id_staff, date_contract_start, date_contract_end, id_staff_contract);
                CALL p_staff_salary_position(NEW.id_staff, salary, id_staff_position, id_department);

                UPDATE staff
                SET
                    d_staff_status = 1,
                    d_salary = salary,
                    d_id_staff_position = id_staff_position,
                    d_id_department = id_department,
                    d_id_staff_contract = id_staff_contract,
                    d_date_contract_start = COALESCE(d_date_contract_start, date_contract_start),
                    d_date_contract_end = date_contract_end
                WHERE
                    id = NEW.id_staff;
            END IF;

            IF TG_OP = 'UPDATE' OR TG_OP = 'DELETE' THEN
                CALL p_staff_contract(OLD.id_staff, date_contract_start, date_contract_end, id_staff_contract);
                CALL p_staff_salary_position(OLD.id_staff, salary, id_staff_position, id_department);

                UPDATE staff
                SET
                    d_staff_status = CASE WHEN id_staff_contract IS NULL THEN NULL ELSE 1 END,
                    d_salary = salary,
                    d_id_staff_position = id_staff_position,
                    d_id_department = id_department,
                    d_id_staff_contract = id_staff_contract,
                    d_date_contract_start = COALESCE(d_date_contract_start, date_contract_start),
                    d_date_contract_end = date_contract_end
                WHERE
                    id = OLD.id_staff;
            END IF;

            RETURN NEW;
        END;
    $$ LANGUAGE plpgsql;

    CREATE OR REPLACE TRIGGER t_staff_contract
        AFTER INSERT OR UPDATE OR DELETE ON mvt_staff_contract
        FOR EACH ROW
        EXECUTE FUNCTION fn_staff_contract();

    DROP FUNCTION fn_staff_promotion;
    CREATE OR REPLACE FUNCTION fn_staff_promotion()
        RETURNS TRIGGER AS $$
        DECLARE
            salary NUMERIC(14, 2);
            id_staff_position INT;
        BEGIN
            IF TG_OP = 'UPDATE' OR TG_OP = 'INSERT' THEN
                CALL p_staff_salary_position(NEW.id_staff, salary, id_staff_position);

                UPDATE staff
                SET
                    d_salary = salary,
                    d_id_staff_position = id_staff_position
                WHERE
                    id = NEW.id_staff;
            END IF;

            IF TG_OP = 'UPDATE' OR TG_OP = 'DELETE' THEN
                CALL p_staff_salary_position(OLD.id_staff, salary, id_staff_position);

                UPDATE staff
                SET
                    d_salary = salary,
                    d_id_staff_position = id_staff_position
                WHERE
                    id = OLD.id_staff;
            END IF;

            RETURN NEW;
        END;
    $$ LANGUAGE plpgsql;

    CREATE OR REPLACE TRIGGER t_staff_promotion
        AFTER INSERT OR UPDATE OR DELETE ON mvt_staff_promotion
        FOR EACH ROW
        EXECUTE FUNCTION fn_staff_promotion();

-- CONSTANTS:
    INSERT INTO department (id, label) VALUES
    ( 1, 'Human Resources'         ),
    ( 2, 'Finance'                 ),
    ( 3, 'Engineering'             ),
    ( 4, 'Marketing'               ),
    ( 5, 'Sales'                   ),
    ( 6, 'Customer Support'        ),
    ( 7, 'IT'                      ),
    ( 8, 'Operations'              ),
    ( 9, 'Legal'                   ),
    (10, 'Research and Development');

    INSERT INTO staff_status(id, label) VALUES(1, 'Actif');
    INSERT INTO staff_status(id, label) VALUES(2, 'Inactif');

    INSERT INTO staff_contract(id, label, renewal_available, max_period_month) VALUES(1, 'CDD', 2, 24);
    INSERT INTO staff_contract(id, label, renewal_available, max_period_month) VALUES(2, 'CDI', -1, -1); -- infinitely renewable
    INSERT INTO staff_contract(id, label, renewal_available, max_period_month) VALUES(3, 'Contrat d''Essai', 2, 6);

    INSERT INTO staff_position_category(id, label) VALUES(1, 'Ouvrier'                     );
    INSERT INTO staff_position_category(id, label) VALUES(2, 'Employé'                     );
    INSERT INTO staff_position_category(id, label) VALUES(3, 'Technicien|Agent de Maîtrise');
    INSERT INTO staff_position_category(id, label) VALUES(4, 'Cadre'                       );
    INSERT INTO staff_position_category(id, label) VALUES(5, 'Dirigeant'                   );

    INSERT INTO staff_position(id, label, id_staff_position_category) VALUES( 1, 'Opérateurs'                   , 1);
    INSERT INTO staff_position(id, label, id_staff_position_category) VALUES( 2, 'Conducteurs d''Engins'        , 1);
    INSERT INTO staff_position(id, label, id_staff_position_category) VALUES( 3, 'Techniciens d''Usine'         , 1);
    INSERT INTO staff_position(id, label, id_staff_position_category) VALUES( 4, 'Secrétaires'                  , 2);
    INSERT INTO staff_position(id, label, id_staff_position_category) VALUES( 5, 'Assistants Administratifs'    , 2);
    INSERT INTO staff_position(id, label, id_staff_position_category) VALUES( 6, 'Caissiers'                    , 2);
    INSERT INTO staff_position(id, label, id_staff_position_category) VALUES( 7, 'Chefs d''Equipe'              , 3);
    INSERT INTO staff_position(id, label, id_staff_position_category) VALUES( 8, 'Superviseurs'                 , 3);
    INSERT INTO staff_position(id, label, id_staff_position_category) VALUES( 9, 'Directeurs'                   , 4);
    INSERT INTO staff_position(id, label, id_staff_position_category) VALUES(10, 'Responsables de Département'  , 4);
    INSERT INTO staff_position(id, label, id_staff_position_category) VALUES(11, 'Membre du Comité de Direction', 5);
    INSERT INTO staff_position(id, label, id_staff_position_category) VALUES(12, 'PDG'                          , 5);
