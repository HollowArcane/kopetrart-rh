-- ========== CONTRACT ==========
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

    DROP TABLE staff_position_category_notice;
    CREATE TABLE staff_position_category_notice(
        id SERIAL PRIMARY KEY,
        id_staff_position_category INT NOT NULL REFERENCES staff_position_category(id) ON DELETE CASCADE,
        duration INTERVAL NOT NULL, -- durée de préavis
        seniority_bound INTERVAL NOT NULL -- seuil d'ancienneté
    );

    DROP TABLE staff_position CASCADE;
    CREATE TABLE staff_position(
        id SERIAL PRIMARY KEY,
        label VARCHAR(255) UNIQUE NOT NULL,
        id_staff_position_category INT NOT NULL REFERENCES staff_position_category(id) ON DELETE CASCADE
    );

    DROP TABLE staff_position_task CASCADE;
    CREATE TABLE staff_position_task(
        id SERIAL PRIMARY KEY,
        id_staff_position INT NOT NULL REFERENCES staff_position(id) ON DELETE CASCADE,
        label VARCHAR(255) NOT NULL
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
        d_id_mvt_staff_contract INT REFERENCES staff_contract(id) ON DELETE SET NULL, -- references mvt_staff_contract.id
        d_staff_status INT REFERENCES staff_status(id) ON DELETE SET NULL, -- references mvt_staff_contract and mvt_contract_breach, null means has not been hired yet
        d_salary NUMERIC(14, 2), -- references mvt_staff_contract.salary or mvt_staff_promotion.salary
        d_id_staff_position INT REFERENCES staff_position(id) ON DELETE SET NULL, -- references mvt_staff_contract.position or mvt_staff_promotion.position
        d_id_department INT REFERENCES department(id) ON DELETE SET NULL, -- references mvt_staff_contract.id_department or mvt_staff_promotion.id_department
        d_id_staff_contract INT REFERENCES staff_contract(id) ON DELETE SET NULL, -- references mvt_staff_contract.id_staff_contract
        d_date_contract_start DATE, -- references mvt_staff_contract.date_start
        d_date_contract_end DATE, -- references mvt_staff_contract.date_end
        d_date_fired DATE -- references contract_breach.date_validated
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
            s.d_id_mvt_staff_contract,
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
            d_id_mvt_staff_contract,
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

    DROP VIEW v_lib_mvt_staff_promotion CASCADE;
    CREATE OR REPLACE VIEW v_lib_mvt_staff_promotion AS
        SELECT
            msp.id,
            msp.id_staff,
            s.first_name,
            s.last_name,
            s.date_birth,
            s.email,
            s.d_staff_status,
            msp.id_department,
            d.label AS department,
            msp.id_staff_position,
            sp.label AS staff_position,
            msp.salary,
            msp.date
        FROM
            mvt_staff_promotion msp
        JOIN
            staff s ON msp.id_staff = s.id
        JOIN
            department d ON msp.id_department = d.id
        JOIN
            staff_position sp ON msp.id_staff_position = sp.id
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
            OUT p_id_mvt_staff_contract INT,
            OUT p_id_staff_contract INT
        )
        LANGUAGE plpgsql AS $$
        BEGIN
            SELECT
                msc.date_start,
                msc.date_end,
                msc.id,
                msc.id_staff_contract
            INTO
                p_date_contract_start,
                p_date_contract_end,
                p_id_mvt_staff_contract,
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

    DROP PROCEDURE p_staff_status;
    CREATE OR REPLACE PROCEDURE p_staff_status(
            id_staff_param INT,
            OUT p_staff_status INT
        )
        LANGUAGE plpgsql AS $$
        BEGIN
            SELECT
                mss.id_staff_status
            INTO
                p_staff_status
            FROM
                v_mvt_staff_status mss
            WHERE
                mss.id_staff = id_staff_param
            UNION ALL
            SELECT
                NULL;
        END;
    $$;


    DROP FUNCTION fn_staff_contract;
    CREATE OR REPLACE FUNCTION fn_staff_contract()
        RETURNS TRIGGER AS $$
        DECLARE
            date_contract_start DATE;
            date_contract_end DATE;
            id_mvt_staff_contract INT;
            id_staff_contract INT;
            salary NUMERIC(14, 2);
            id_staff_position INT;
            id_staff_status INT;
            id_department INT;
        BEGIN
            IF TG_OP = 'UPDATE' OR TG_OP = 'INSERT' THEN
                CALL p_staff_contract(NEW.id_staff, date_contract_start, date_contract_end, id_mvt_staff_contract, id_staff_contract);
                CALL p_staff_salary_position(NEW.id_staff, salary, id_staff_position, id_department);
                CALL p_staff_status(NEW.id_staff, id_staff_status);

                UPDATE staff
                SET
                    d_staff_status = id_staff_status,
                    d_date_fired = NULL,
                    d_id_mvt_staff_contract = id_mvt_staff_contract,
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
                CALL p_staff_contract(OLD.id_staff, date_contract_start, date_contract_end, id_mvt_staff_contract, id_staff_contract);
                CALL p_staff_salary_position(OLD.id_staff, salary, id_staff_position, id_department);
                CALL p_staff_status(OLD.id_staff, id_staff_status);

                UPDATE staff
                SET
                    d_staff_status = id_staff_status,
                    d_date_fired = NULL,
                    d_id_mvt_staff_contract = id_mvt_staff_contract,
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
            id_department INT;
        BEGIN
            IF TG_OP = 'UPDATE' OR TG_OP = 'INSERT' THEN
                CALL p_staff_salary_position(NEW.id_staff, salary, id_staff_position, id_department);

                UPDATE staff
                SET
                    d_salary = salary,
                    d_id_staff_position = id_staff_position,
                    d_id_department = id_department
                WHERE
                    id = NEW.id_staff;
            END IF;

            IF TG_OP = 'UPDATE' OR TG_OP = 'DELETE' THEN
                CALL p_staff_salary_position(OLD.id_staff, salary, id_staff_position, id_department);

                UPDATE staff
                SET
                    d_salary = salary,
                    d_id_staff_position = id_staff_position,
                    d_id_department = id_department
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



    INSERT INTO staff_position_category_notice(id_staff_position_category, duration, seniority_bound) VALUES(1, INTERVAL '7 days' , INTERVAL '0 days');
    INSERT INTO staff_position_category_notice(id_staff_position_category, duration, seniority_bound) VALUES(2, INTERVAL '7 days' , INTERVAL '0 days');
    INSERT INTO staff_position_category_notice(id_staff_position_category, duration, seniority_bound) VALUES(3, INTERVAL '1 month', INTERVAL '0 days');
    INSERT INTO staff_position_category_notice(id_staff_position_category, duration, seniority_bound) VALUES(4, INTERVAL '1 month', INTERVAL '0 days');
    INSERT INTO staff_position_category_notice(id_staff_position_category, duration, seniority_bound) VALUES(5, INTERVAL '1 day'  , INTERVAL '0 days');

    INSERT INTO staff_position_category_notice(id_staff_position_category, duration, seniority_bound) VALUES(1, INTERVAL '1 month' , INTERVAL '6 months');
    INSERT INTO staff_position_category_notice(id_staff_position_category, duration, seniority_bound) VALUES(2, INTERVAL '1 month' , INTERVAL '6 months');
    INSERT INTO staff_position_category_notice(id_staff_position_category, duration, seniority_bound) VALUES(3, INTERVAL '1 month' , INTERVAL '6 months');
    INSERT INTO staff_position_category_notice(id_staff_position_category, duration, seniority_bound) VALUES(4, INTERVAL '3 months', INTERVAL '6 months');
    INSERT INTO staff_position_category_notice(id_staff_position_category, duration, seniority_bound) VALUES(5, INTERVAL '3 months', INTERVAL '6 months');

    INSERT INTO staff_position_category_notice(id_staff_position_category, duration, seniority_bound) VALUES(1, INTERVAL '2 months', INTERVAL '2 years');
    INSERT INTO staff_position_category_notice(id_staff_position_category, duration, seniority_bound) VALUES(2, INTERVAL '2 months', INTERVAL '2 years');
    INSERT INTO staff_position_category_notice(id_staff_position_category, duration, seniority_bound) VALUES(3, INTERVAL '2 months', INTERVAL '2 years');
    INSERT INTO staff_position_category_notice(id_staff_position_category, duration, seniority_bound) VALUES(4, INTERVAL '3 months', INTERVAL '2 years');
    INSERT INTO staff_position_category_notice(id_staff_position_category, duration, seniority_bound) VALUES(5, INTERVAL '2 months', INTERVAL '2 years');



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

    -- Tâches pour Opérateurs (id = 1)
    INSERT INTO staff_position_task(id_staff_position, label) VALUES
        (1, 'Exécution des tâches de production selon les instructions'),
        (1, 'Respect des normes de qualité et de sécurité'),
        (1, 'Utilisation et manipulation des équipements de production'),
        (1, 'Signalement des anomalies et des dysfonctionnements');

    -- Tâches pour Conducteurs d'Engins (id = 2)
    INSERT INTO staff_position_task(id_staff_position, label) VALUES
        (2, 'Conduite et manipulation des engins de chantier ou industriels'),
        (2, 'Vérification quotidienne de l''état des équipements'),
        (2, 'Respect des règles de sécurité et de circulation'),
        (2, 'Maintenance de premier niveau des engins');

    -- Tâches pour Techniciens d'Usine (id = 3)
    INSERT INTO staff_position_task(id_staff_position, label) VALUES
        (3, 'Maintenance préventive et corrective des équipements'),
        (3, 'Diagnostic et résolution des pannes techniques'),
        (3, 'Contrôle qualité des productions'),
        (3, 'Mise à jour des documents techniques');

    -- Tâches pour Secrétaires (id = 4)
    INSERT INTO staff_position_task(id_staff_position, label) VALUES
        (4, 'Gestion des agendas et organisation des réunions'),
        (4, 'Accueil physique et téléphonique'),
        (4, 'Rédaction et mise en forme de documents'),
        (4, 'Classement et archivage des documents');

    -- Tâches pour Assistants Administratifs (id = 5)
    INSERT INTO staff_position_task(id_staff_position, label) VALUES
        (5, 'Gestion administrative des dossiers'),
        (5, 'Préparation des documents comptables'),
        (5, 'Suivi des commandes et approvisionnements'),
        (5, 'Assistance aux différents services');

    -- Tâches pour Caissiers (id = 6)
    INSERT INTO staff_position_task(id_staff_position, label) VALUES
        (6, 'Encaissement et gestion des règlements'),
        (6, 'Tenue et contrôle de la caisse'),
        (6, 'Accueil et service des clients'),
        (6, 'Établissement des tickets et justificatifs');

    -- Tâches pour Chefs d'Equipe (id = 7)
    INSERT INTO staff_position_task(id_staff_position, label) VALUES
        (7, 'Coordination et supervision des équipes'),
        (7, 'Répartition et planification des tâches'),
        (7, 'Reporting à la hiérarchie'),
        (7, 'Gestion des ressources et des compétences');

    -- Tâches pour Superviseurs (id = 8)
    INSERT INTO staff_position_task(id_staff_position, label) VALUES
        (8, 'Pilotage opérationnel des services'),
        (8, 'Optimisation des processus de travail'),
        (8, 'Gestion des indicateurs de performance'),
        (8, 'Animation et développement des équipes');

    -- Tâches pour Directeurs (id = 9)
    INSERT INTO staff_position_task(id_staff_position, label) VALUES
        (9, 'Définition de la stratégie du département'),
        (9, 'Gestion budgétaire et financière'),
        (9, 'Représentation de l''entreprise'),
        (9, 'Développement des ressources humaines');

    -- Tâches pour Responsables de Département (id = 10)
    INSERT INTO staff_position_task(id_staff_position, label) VALUES
        (10, 'Mise en œuvre de la stratégie departementale'),
        (10, 'Gestion des projets et des ressources'),
        (10, 'Coordination inter-services'),
        (10, 'Reporting à la direction générale');

    -- Tâches pour Membre du Comité de Direction (id = 11)
    INSERT INTO staff_position_task(id_staff_position, label) VALUES
        (11, 'Participation aux décisions stratégiques'),
        (11, 'Pilotage global de l''entreprise'),
        (11, 'Analyse des performances globales'),
        (11, 'Définition des orientations à long terme');

    -- Tâches pour PDG (id = 12)
    INSERT INTO staff_position_task(id_staff_position, label) VALUES
        (12, 'Définition de la vision stratégique'),
        (12, 'Représentation légale et institutionnelle'),
        (12, 'Arbitrage des décisions majeures'),
        (12, 'Développement et croissance de l''entreprise');


INSERT INTO staff(first_name, last_name, email, date_birth) VALUES('John', 'Doe', 'john@gmail.com', '2004-01-01');
