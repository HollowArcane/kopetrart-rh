CREATE OR REPLACE VIEW v_denormalized_cv AS
SELECT 
    cv.id AS cv_id,
    cv.id_cv,
    cv.candidat_name,
    cv.poste,
    cv.date_depot_dossier,
    STRING_AGG(DISTINCT i.label, ', ') FILTER (WHERE i.id IS NOT NULL) AS interests,
    STRING_AGG(DISTINCT q.label, ', ') FILTER (WHERE q.id IS NOT NULL) AS qualities,
    STRING_AGG(DISTINCT e.label, ', ') FILTER (WHERE e.id IS NOT NULL) AS educations,
    STRING_AGG(DISTINCT ex.label || ' (' || ex.month_duration || ' months)', ', ') FILTER (WHERE ex.id IS NOT NULL) AS experiences,
    cv.adequate,
    cv.potentiel
FROM 
    denormalized_cv cv

LEFT JOIN denormalized_cv_interests dci ON cv.id = dci.id_cv_denormalized
LEFT JOIN interests i ON dci.id_interest = i.id
LEFT JOIN denormalized_cv_qualities dcq ON cv.id = dcq.id_cv_denormalized
LEFT JOIN qualities q ON dcq.id_quality = q.id
LEFT JOIN denormalized_cv_education dce ON cv.id = dce.id_cv_denormalized
LEFT JOIN educations e ON dce.id_education = e.id
LEFT JOIN denormalized_cv_experiences ex ON cv.id = ex.id_cv_denormalized
GROUP BY 
    cv.id, cv.id_cv, cv.candidat_name, cv.poste, cv.date_depot_dossier, cv.adequate, cv.potentiel;