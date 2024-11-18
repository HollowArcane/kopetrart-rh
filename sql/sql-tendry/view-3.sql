CREATE OR REPLACE VIEW v_test AS
SELECT
    t.id,
    t.id_need,
    n.libelle AS need,
    t.title,
    t.goal,
    t.requirements,
    SUM(tp.duration) AS duration
FROM
    test t
JOIN
    besoin_poste b ON t.id_need = b.id
JOIN
    postes n ON b.id_poste = n.id
JOIN
    test_part tp ON tp.id_test = t.id
GROUP BY
    t.id,
    t.id_need,
    n.libelle,
    t.title,
    t.goal,
    t.requirements
;



CREATE OR REPLACE VIEW v_test_candidate AS
SELECT
    tc.id,
    tc.id_test,
    t.title AS test,
    tc.id_cv_candidate,
    c.candidat AS candidate_first_name,
    '' AS candidate_last_name,
    t.id_need,
    n.libelle AS need,
    tc.date_received,
    tc.file,
    tc.score,
    tc.id_result,
    r.label AS result,
    tc.date_validated,
    tc.is_communication_send
FROM
    test_candidate tc
JOIN
    cv cc ON tc.id_cv_candidate = cc.id
JOIN
    dossiers c ON cc.id_dossier = c.id
JOIN
    test t ON tc.id_test = t.id
JOIN
    besoin_poste b ON t.id_need = b.id
join
    postes n on b.id_poste = n.id
JOIN
    test_candidate_result r ON tc.id_result = r.id
;


CREATE OR REPLACE VIEW v_test_candidate_result AS
SELECT
    tc.id,
    SUM(tcc.value * tcr.coefficient) AS mark,
    SUM(tcr.coefficient) AS coefficient,
    (
        SELECT
            COALESCE(COUNT(*), 0)
        FROM
            test_candidate_point tcp
        JOIN
            test_point tp ON tcp.id_point = tp.id
        JOIN
            test_point_importance tpi ON tp.id_importance = tpi.id
        WHERE
            tpi.id=1
    ) AS blocant,
    (
        SELECT
            COALESCE(COUNT(*), 0)
        FROM
            test_candidate_point tcp
        JOIN
            test_point tp ON tcp.id_point = tp.id
        JOIN
            test_point_importance tpi ON tp.id_importance = tpi.id
        WHERE
            tpi.id=3
    ) AS bonus
FROM
    test_candidate tc
JOIN
    test_candidate_criteria tcc ON tc.id = tcc.id_test_candidate
JOIN
    test_criterion tcr ON tcc.id_criterion = tcr.id
GROUP BY
    tc.id
;
