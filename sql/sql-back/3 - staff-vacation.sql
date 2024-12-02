-- ========== STAFF VACATIONS ==========
-- all mouvement tables start with mvt_
-- all denormalized columns start with d_
-- all trigger starting with t_b_ are before triggers

-- TABLES:
    DROP TABLE staff_vacation;
    CREATE TABLE staff_vacation(
        id SERIAL PRIMARY KEY,
        id_staff INT NOT NULL REFERENCES staff(id) ON DELETE CASCADE,
        date_start DATE NOT NULL,
        date_end DATE NOT NULL,
        date_validated DATE,
        CHECK(date_start < date_end)
    );

-- VIEWS:
    DROP VIEW v_lib_staff_vacation;
    CREATE OR REPLACE VIEW v_lib_staff_vacation AS
        SELECT
            sv.id,
            s.id AS id_staff,
            s.first_name,
            s.last_name,
            s.date_birth,
            s.email,
            s.d_staff_status,
            s.d_salary,
            s.d_id_staff_position,
            s.staff_position,
            s.d_id_staff_contract,
            s.staff_contract,
            s.d_id_mvt_staff_contract,
            s.d_date_contract_start,
            s.d_date_contract_end,
            s.d_id_department,
            s.department,
            sv.date_start,
            sv.date_end,
            sv.date_validated
        FROM
            staff_vacation sv
        JOIN
            v_lib_staff s ON sv.id_staff = s.id;

-- FUNCTIONS:
    DROP FUNCTION fn_staff_vacation_status(var_today DATE);
    CREATE OR REPLACE FUNCTION fn_staff_vacation_status(var_today DATE)
        RETURNS TABLE(id_staff INT, day_vacation_taken INT, months_working INT, day_vacation_left INT) AS $$
        BEGIN
            RETURN QUERY
            SELECT
                s.id AS id_staff,
                CAST(COALESCE(sv.day_vacation_taken, 0) AS INT) AS day_vacation_taken,
                CAST(EXTRACT(YEAR FROM var_today) * 12 + EXTRACT(MONTH FROM var_today) - EXTRACT(YEAR FROM s.d_date_contract_start) * 12 - EXTRACT(MONTH FROM d_date_contract_start) AS INT) AS months_working,
                CAST(FLOOR(2.5 * (EXTRACT(YEAR FROM var_today) * 12 + EXTRACT(MONTH FROM var_today) - EXTRACT(YEAR FROM s.d_date_contract_start) * 12 - EXTRACT(MONTH FROM d_date_contract_start))) - COALESCE(sv.day_vacation_taken, 0) AS INT) AS day_vacation_left
            FROM
                staff s
            LEFT JOIN(
                SELECT
                    _m.id_staff,
                    SUM(date_end - date_start + 1) AS day_vacation_taken
                FROM (
                    SELECT
                        _sv.id_staff,
                        _sv.date_start,
                        _sv.date_end
                    FROM
                        staff_vacation _sv
                    WHERE
                        _sv.date_end <= var_today
                    UNION ALL
                    SELECT
                        _sv2.id_staff,
                        _sv2.date_start,
                        var_today
                    FROM
                        staff_vacation _sv2
                    WHERE
                            _sv2.date_start <= var_today
                        AND
                            _sv2.date_end > var_today
                ) _m
                GROUP BY
                    _m.id_staff
            ) sv ON sv.id_staff = s.id;
        END;
    $$ LANGUAGE plpgsql;

-- TRIGGERS:
    DROP FUNCTION fn_b_staff_vacation;
    CREATE OR REPLACE FUNCTION fn_b_staff_vacation()
        RETURNS TRIGGER AS $$
        DECLARE
            day_left INT;
        BEGIN
            SELECT
                day_vacation_left
            INTO
                day_left
            FROM
                fn_staff_vacation_status(NEW.date_start);

            RAISE NOTICE 'day_left = %', day_left;

            IF TG_OP = 'UPDATE' THEN
                IF OLD.date_end <= NEW.date_end THEN
                    day_left := day_left + (OLD.date_end - OLD.date_start + 1);
                ELSEIF OLD.date_start <= NEW.date_end THEN
                    day_left := dayLeft + (NEW.date_end - OLD.date_start + 1);
                END IF;
            END IF;

            IF day_left < (NEW.date_end - NEW.date_start + 1)  THEN
                RAISE EXCEPTION 'Staff has not worked hard enough to deserve those vacations';
            END IF;

            RETURN NEW;
        END;
    $$ LANGUAGE plpgsql;

    CREATE OR REPLACE TRIGGER t_b_staff_contract
        BEFORE INSERT OR UPDATE ON staff_vacation
        FOR EACH ROW
        EXECUTE FUNCTION fn_b_staff_vacation();

-- CONSTANTS:
INSERT INTO staff_vacation(id_staff, date_start, date_end) VALUES(1, '2023-01-10', '2023-01-14');
INSERT INTO staff_vacation(id_staff, date_start, date_end) VALUES(1, '2023-01-28', '2023-02-2');

UPDATE staff_vacation SET date_validated = '2023-01-01';

SELECT * FROM fn_staff_vacation_status('2023-01-11');
