CREATE OR REPLACE FUNCTION get_matching_employees(p_besoin_poste_id INTEGER DEFAULT NULL)
RETURNS TABLE (
    id_employe INTEGER,
    id_departement INTEGER,
    departement VARCHAR(255),
    id_poste INTEGER,
    poste VARCHAR(255),
    date_embauche DATE,
    salaire_propose DECIMAL,
    candidat VARCHAR(255),
    email_candidat VARCHAR(255),
    type_contrat VARCHAR(255),
    nom_manager VARCHAR(255),
    email_manager VARCHAR(255)
) AS $$
BEGIN
    IF p_besoin_poste_id IS NULL THEN
        -- Retourner tous les employés si aucun besoin_poste_id n'est spécifié
        RETURN QUERY
        SELECT
            e.id,
            e.id_departement,
            vlp.departement,
            e.id_poste,
            vlp.poste,
            vlp.date_embauche,
            vlp.salaire_propose,
            vlp.candidat,
            vlp.email_candidat,
            vlp.type_contrat,
            vlp.nom_manager,
            vlp.email_manager
        FROM v_liste_personnel vlp
        JOIN employe e ON e.id = vlp.id_employe
        ORDER BY vlp.id_employe;
    ELSE
        -- Appliquer le filtre des talents si un besoin_poste_id est spécifié
        RETURN QUERY
        WITH required_talents AS (
            SELECT array_agg(id_talent) as talent_ids
            FROM talent_poste_besoin
            WHERE id_besoin_poste = p_besoin_poste_id
        ),
        employee_talents AS (
            SELECT
                e.id as id_employe,
                e.id_departement,
                vlp.departement,
                e.id_poste,
                vlp.poste,
                vlp.date_embauche,
                vlp.salaire_propose,
                vlp.candidat,
                vlp.email_candidat,
                vlp.type_contrat,
                vlp.nom_manager,
                vlp.email_manager,
                array_agg(ccv.id_talent) as talent_ids
            FROM employe e
            INNER JOIN classification_cv ccv ON ccv.id_cv = e.id_cv
            INNER JOIN v_liste_personnel vlp ON vlp.id_employe = e.id
            GROUP BY
                e.id,
                e.id_departement,
                vlp.departement,
                e.id_poste,
                vlp.poste,
                vlp.date_embauche,
                vlp.salaire_propose,
                vlp.candidat,
                vlp.email_candidat,
                vlp.type_contrat,
                vlp.nom_manager,
                vlp.email_manager
        )
        SELECT
            et.id_employe,
            et.id_departement,
            et.departement,
            et.id_poste,
            et.poste,
            et.date_embauche,
            et.salaire_propose,
            et.candidat,
            et.email_candidat,
            et.type_contrat,
            et.nom_manager,
            et.email_manager
        FROM employee_talents et, required_talents rt
        WHERE rt.talent_ids <@ et.talent_ids
        ORDER BY et.id_employe;
    END IF;
END;
$$ LANGUAGE plpgsql;

