-- ========== CONTRACT BREACH ==========
-- all mouvement tables start with mvt_
-- all denormalized columns start with d_
-- all trigger starting with t_b_ are before triggers

-- TABLES:
    DROP TABLE contract_breach_type CASCADE;
    CREATE TABLE contract_breach_type(
        id SERIAL PRIMARY KEY,
        label VARCHAR(25) NOT NULL UNIQUE
    );

    DROP TABLE contract_breach CASCADE;
    CREATE TABLE contract_breach(
        id SERIAL PRIMARY KEY,
        id_mvt_staff_contract INT UNIQUE NOT NULL REFERENCES mvt_staff_contract(id) ON DELETE CASCADE,
        id_contract_breach_type INT NOT NULL REFERENCES contract_breach_type(id) ON DELETE CASCADE,
        comment TEXT NOT NULL,
        id_role INT NOT NULL REFERENCES role(id) ON DELETE CASCADE,
        date_source DATE NOT NULL,
        date_target DATE,
        salary_bonus NUMERIC(18, 2) NOT NULL,
        date_validated DATE NOT NULL, -- needed to compute additional salary when time for contract_breach has come
        is_validated BOOLEAN DEFAULT FALSE,
        CHECK(id_contract_breach_type = 1 AND salary_bonus <= 0 OR salary_bonus >= 0),
        CHECK(date_validated >= date_source),
        CHECK(date_target IS NULL OR date_target >= date_source),
        CHECK(date_target <= date_validated)
    );

-- VIEWS:
    DROP VIEW v_mvt_staff_status;
    CREATE OR REPLACE VIEW v_mvt_staff_status AS
        SELECT
            msc.id_staff,
            2 AS id_staff_status, -- Inactif
            cb.date_validated AS date_status
        FROM
            contract_breach cb
        JOIN
            mvt_staff_contract msc ON cb.id_mvt_staff_contract = msc.id
        WHERE
            is_validated = TRUE
        UNION ALL
        SELECT
            msc.id_staff,
            1 AS id_staff_status, -- Actif
            msc.date_start AS date_status
        FROM
            mvt_staff_contract msc;

    DROP VIEW v_staff_contract_breach;
    CREATE OR REPLACE VIEW v_staff_contract_breach AS
        SELECT
            cb.id,
            msc.id_staff,
            cb.id_contract_breach_type,
            s.first_name,
            s.last_name,
            s.d_date_contract_start,
            cb.date_validated,
            cb.is_validated,
            s.d_id_department,
            d.label AS department,
            s.d_id_staff_position,
            sp.label AS staff_position,
            s.d_id_staff_contract,
            sc.label AS staff_contract,
            cb.date_target,
            cb.id_role
        FROM
            contract_breach cb
        JOIN
            mvt_staff_contract msc ON cb.id_mvt_staff_contract = msc.id
        JOIN
            staff s ON msc.id_staff = s.id
        JOIN
            department d ON s.d_id_department = d.id
        JOIN
            staff_position sp ON s.d_id_staff_position = sp.id
        JOIN
            staff_contract sc ON s.d_id_staff_contract = sc.id;

-- TRIGGERS:
    DROP PROCEDURE p_b_contract_breach;
    CREATE OR REPLACE PROCEDURE p_b_contract_breach(
            id_mvt_staff_contract INT,
            OUT p_id_staff INT,
            OUT p_id_staff_contract INT
        )
        LANGUAGE plpgsql AS $$
        BEGIN
            SELECT
                id_staff_contract
                id_staff
            INTO
                p_id_staff_contract,
                p_id_staff
            FROM
                mvt_staff_contract
            WHERE
                id = id_mvt_staff_contract;
        END;
    $$;

    DROP FUNCTION IF EXISTS f_b_salary_bonus;
    CREATE OR REPLACE FUNCTION f_b_salary_bonus(
            date_contract_breach_demand DATE,
            date_contract_breach_expected DATE,
            id_contract_breach_type INT,
            var_id_staff INT
        )
        RETURNS NUMERIC(18, 2)
        LANGUAGE plpgsql AS $$
        DECLARE
            p_salary_bonus NUMERIC(18, 2);
        BEGIN
            SELECT
                CASE
                    WHEN ((date_contract_breach_expected - date_contract_breach_demand) * INTERVAL '1 day') < spcn.duration THEN
                        EXTRACT(EPOCH FROM JUSTIFY_DAYS(((date_contract_breach_demand - date_contract_breach_expected) * INTERVAL '1 day') + spcn.duration)) * s.d_salary / 30  / 86400 *
                            CASE
                                WHEN id_contract_breach_type = 1 THEN -1
                                WHEN id_contract_breach_type = 4 THEN  0
                                ELSE 1
                            END
                    ELSE 0
                END AS salary_bonus
            INTO
                p_salary_bonus
            FROM
                staff s
            JOIN
                staff_position sp ON s.d_id_staff_position = sp.id
            JOIN
                staff_position_category spc ON sp.id_staff_position_category = spc.id
            JOIN
                staff_position_category_notice spcn ON spcn.id_staff_position_category = spc.id
            JOIN (
                SELECT
                    _s.id AS id_staff,
                    _sp.id_staff_position_category,
                    MAX(_spcn.seniority_bound) AS seniority_bound
                FROM
                    staff _s
                JOIN
                    staff_position _sp ON _s.d_id_staff_position = _sp.id
                JOIN
                    staff_position_category _spc ON _sp.id_staff_position_category = _spc.id
                JOIN
                    staff_position_category_notice _spcn ON _spcn.id_staff_position_category = _spc.id
                WHERE
                    _spcn.seniority_bound <= ((date_contract_breach_demand - _s.d_date_contract_start) * INTERVAL '1 day')
                GROUP BY
                    _s.id,
                    _sp.id_staff_position_category
            ) AS m ON s.id = m.id_staff AND spcn.seniority_bound = m.seniority_bound
            WHERE
                s.id = var_id_staff;

            RETURN p_salary_bonus;
        END;
    $$;

    DROP FUNCTION IF EXISTS fn_contract_breach;
    CREATE OR REPLACE FUNCTION fn_b_contract_breach()
            RETURNS TRIGGER AS $$
        DECLARE
            var_id_staff INT;
            date_start DATE;
            date_end DATE;
            id_mvt_staff_contract INT := NEW.id_mvt_staff_contract;
            id_staff_contract INT;
        BEGIN
            SELECT id_staff
            INTO var_id_staff
            FROM mvt_staff_contract
            WHERE id = id_mvt_staff_contract;

            CALL p_staff_contract(var_id_staff, date_start, date_end, id_mvt_staff_contract, id_staff_contract);

            RAISE NOTICE 'Debug Info: Start Date: %, End Date: %, Contract ID: %',
                date_start, date_end, id_mvt_staff_contract;

            IF id_staff_contract <> 2 AND NEW.id_contract_breach_type = 4 THEN
                RAISE EXCEPTION 'Conventional contract breach can only occur on CDI contracts.';
            END IF;

            IF NEW.salary_bonus IS NULL THEN
                NEW.salary_bonus := f_b_salary_bonus(NEW.date_source, NEW.date_validated, NEW.id_contract_breach_type, var_id_staff);
                RAISE NOTICE 'Computed Salary Bonus: %', NEW.salary_bonus;
            END IF;

            RETURN NEW;
        END;
    $$ LANGUAGE plpgsql;

    CREATE OR REPLACE TRIGGER t_b_contract_breach
        BEFORE INSERT OR UPDATE ON contract_breach
        FOR EACH ROW
        EXECUTE FUNCTION fn_b_contract_breach();


    DROP FUNCTION fn_contract_breach;
    CREATE OR REPLACE FUNCTION fn_contract_breach()
        RETURNS TRIGGER AS $$
        DECLARE
            var_id_staff INT;
            id_staff_status INT;
        BEGIN
            IF TG_OP = 'UPDATE' OR TG_OP = 'INSERT' THEN
                SELECT
                    id_staff
                INTO
                    var_id_staff
                FROM
                    mvt_staff_contract
                WHERE
                    id = NEW.id_mvt_staff_contract;

                RAISE NOTICE 'id_staff: %', var_id_staff;
                CALL p_staff_status(var_id_staff, id_staff_status);

                UPDATE staff
                SET
                    d_staff_status = id_staff_status
                WHERE
                    id = var_id_staff;
            END IF;

            IF TG_OP = 'UPDATE' OR TG_OP = 'DELETE' THEN
                SELECT
                    id_staff
                INTO
                    var_id_staff
                FROM
                    mvt_staff_contract
                WHERE
                    id = OLD.id_mvt_staff_contract;

                RAISE NOTICE 'id_staff: %', var_id_staff;
                CALL p_staff_status(var_id_staff, id_staff_status);

                UPDATE staff
                SET
                    d_staff_status = id_staff_status
                WHERE
                    id = var_id_staff;
            END IF;

            RETURN NEW;
        END;
    $$ LANGUAGE plpgsql;

    CREATE OR REPLACE TRIGGER t_contract_breach
        AFTER INSERT OR UPDATE OR DELETE ON contract_breach
        FOR EACH ROW
        EXECUTE FUNCTION fn_contract_breach();

-- FUNCTIONS:
    DROP FUNCTION fn_staff_contract_breach_notice_date(var_id_staff INT, today DATE);
    CREATE OR REPLACE FUNCTION fn_staff_contract_breach_notice_date(
            var_id_staff INT,
            today DATE
        )
        RETURNS TABLE(date_notice DATE, duration INTERVAL) AS $$
        BEGIN
            RETURN QUERY
            SELECT
                (today + spcn.duration)::DATE AS date_notice,
                spcn.duration
            FROM
                staff s
            JOIN
                staff_position sp ON s.d_id_staff_position = sp.id
            JOIN
                staff_position_category spc ON sp.id_staff_position_category = spc.id
            JOIN
                staff_position_category_notice spcn ON spcn.id_staff_position_category = spc.id
            JOIN (
                SELECT
                    _s.id AS id_staff,
                    _sp.id_staff_position_category,
                    MAX(_spcn.seniority_bound) AS seniority_bound
                FROM
                    staff _s
                JOIN
                    staff_position _sp ON _s.d_id_staff_position = _sp.id
                JOIN
                    staff_position_category _spc ON _sp.id_staff_position_category = _spc.id
                JOIN
                    staff_position_category_notice _spcn ON _spcn.id_staff_position_category = _spc.id
                WHERE
                    _spcn.seniority_bound <= ((today - _s.d_date_contract_start) * INTERVAL '1 day')
                GROUP BY
                    _s.id,
                    _sp.id_staff_position_category
            ) AS m ON s.id = m.id_staff AND spcn.seniority_bound = m.seniority_bound
            WHERE
                s.id = var_id_staff;
        END;
    $$ LANGUAGE plpgsql;

-- CONSTANTS:
    INSERT INTO contract_breach_type(id, label) VALUES(1, 'Démission');
    INSERT INTO contract_breach_type(id, label) VALUES(2, 'Licenciement');
    INSERT INTO contract_breach_type(id, label) VALUES(3, 'Mise à la Retraite');
    INSERT INTO contract_breach_type(id, label) VALUES(4, 'Rupture Conventionelle');


SELECT * FROM fn_staff_contract_breach_notice_date(1, '2027-01-01');
insert into contract_breach (id_mvt_staff_contract, id_contract_breach_type, date_source, date_validated, comment, salary_bonus, id_role) values (2, 2, '2024-11-27', '2024-12-02', 'asfoa`k^*kwrioha', 10000, 1);

SELECT f_b_salary_bonus('2024-11-27', '2024-12-05', 2, 1);
