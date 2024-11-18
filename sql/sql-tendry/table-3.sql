
CREATE TABLE test(
    id SERIAL PRIMARY KEY,
    id_need INT NOT NULL REFERENCES need(id) ON DELETE CASCADE,
    title VARCHAR(100) NOT NULL,
    goal TEXT NOT NULL,
    requirements TEXT NOT NULL
);

-- duration is in minutes (mn)
CREATE TABLE test_part(
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    duration INT NOT NULL,
    id_test INT NOT NULL REFERENCES test(id) ON DELETE CASCADE
);

CREATE TABLE test_point_importance(
    id SERIAL PRIMARY KEY,
    label VARCHAR(10) NOT NULL
);

CREATE TABLE test_point(
    id SERIAL PRIMARY KEY,
    label VARCHAR(255) NOT NULL,
    id_importance INT NOT NULL REFERENCES test_point_importance(id) ON DELETE CASCADE,
    id_test INT NOT NULL REFERENCES test(id) ON DELETE CASCADE
);

CREATE TABLE test_criterion(
    id SERIAL PRIMARY KEY,
    label VARCHAR(255) NOT NULL,
    coefficient INT NOT NULL DEFAULT 1,
    id_test INT NOT NULL REFERENCES test(id) ON DELETE CASCADE
);

-- || TEST CANDIDATE RESULT || --
CREATE TABLE test_candidate_result(
    id SERIAL PRIMARY KEY,
    label VARCHAR(10) NOT NULL
);

-- || TEST CANDIDATE || --
CREATE TABLE test_candidate(
    id SERIAL PRIMARY KEY,
    id_test INT NOT NULL REFERENCES test(id) ON DELETE CASCADE,
    id_cv_candidate INT NOT NULL REFERENCES cv_candidate(id) ON DELETE CASCADE,
    date_received DATE NOT NULL,
    file VARCHAR(50) NOT NULL,
    date_validated DATE,
    -- denormalized
    score REAL NOT NULL DEFAULT 0,
    id_result INT NOT NULL REFERENCES test_candidate_result(id) ON DELETE CASCADE DEFAULT 3
);

CREATE TABLE test_candidate_point(
    id SERIAL PRIMARY KEY,
    id_point INT NOT NULL REFERENCES test_point(id),
    id_test_candidate INT NOT NULL REFERENCES test_candidate(id)
);

CREATE TABLE test_candidate_criteria(
    id SERIAL PRIMARY KEY,
    id_criterion INT NOT NULL REFERENCES test_criterion(id),
    id_test_candidate INT NOT NULL REFERENCES test_candidate(id),
    value INT NOT NULL
);


CREATE OR REPLACE FUNCTION check_test_candidate_need()
RETURNS TRIGGER AS $$
DECLARE
    test_need_id INT;
    cv_need_id INT;
    cv_date_received DATE;
BEGIN
    SELECT id_need INTO test_need_id
    FROM test
    WHERE id = NEW.id_test;

    SELECT id_need, date_received INTO cv_need_id, cv_date_received
    FROM cv_candidate
    WHERE id = NEW.id_cv_candidate;

    IF test_need_id != cv_need_id THEN
        RAISE EXCEPTION 'The test need ID (%) does not match the CV candidate need ID (%)',
            test_need_id, cv_need_id;
    END IF;

    IF NEW.date_received <= cv_date_received THEN
        RAISE EXCEPTION 'Test submission date (%) must be after CV submission date (%)',
            NEW.date_received, cv_date_received;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER t_test_candidate_need
    BEFORE INSERT OR UPDATE ON test_candidate
    FOR EACH ROW
    EXECUTE FUNCTION check_test_candidate_need();