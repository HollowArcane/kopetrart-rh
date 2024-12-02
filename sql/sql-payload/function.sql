--
-- fn_monthly_base_salary
--
    CREATE OR REPLACE FUNCTION fn_get_monthly_base_salary(p_id_staff INT, p_date_reference DATE)
    RETURNS NUMERIC AS $$
    DECLARE
        monthly_salary NUMERIC;
        absence_deduction NUMERIC := 0; 
        base_salary NUMERIC;
    BEGIN
        SELECT d_salary INTO base_salary
        FROM staff
        WHERE id = p_id_staff;

        -- check if the staff has any absence in the given month and fetch the deduction
        SELECT COALESCE(SUM(detention_amount), 0) INTO absence_deduction
        FROM v_detention_on_absence
        WHERE id_staff = p_id_staff 
        AND month_start = DATE_TRUNC('month', p_date_reference);

        monthly_salary := base_salary - absence_deduction;

        RETURN monthly_salary;
    END;
    $$ LANGUAGE plpgsql;

--
-- fn_salary_brut
--
    CREATE OR REPLACE FUNCTION fn_get_salary_brut(
        p_id_staff INT, 
        p_reference_month DATE DEFAULT CURRENT_DATE
    )
    RETURNS TABLE (
        res_staff_id INT,
        res_first_name VARCHAR(255),
        res_last_name VARCHAR(255),
        res_base_salary NUMERIC(14,2),
        res_total_compensation NUMERIC(14,2),
        res_rappel_salary NUMERIC(14,2),
        res_total_overtime NUMERIC(14,2),
        res_seniority_bonus NUMERIC(14,2),
        res_performance_bonus NUMERIC(14,2),
        res_monthly_gross_salary NUMERIC(14,2),
        res_reference_month DATE
    ) AS $$
    BEGIN
        RETURN QUERY
        WITH monthly_components AS (
            SELECT 
                s.id AS var_id_staff,
                s.first_name AS var_first_name,
                s.last_name AS var_last_name,
                
                -- Base Salary 
                fn_get_monthly_base_salary(p_id_staff, p_reference_month) AS var_base_salary,
                
                -- Compensations (sum of all compensations in the month)
                COALESCE(
                    (SELECT SUM(amount) 
                    FROM staff_compensation 
                    WHERE id_staff = s.id 
                    AND DATE_TRUNC('month', date_compensation) = DATE_TRUNC('month', p_reference_month)
                    ), 
                    0
                ) AS var_total_compensation,
                
                -- Salary Rappel (SMIG adjustment)
                COALESCE(
                    (SELECT salary_cumul 
                    FROM v_rappel_salary 
                    WHERE id_staff = s.id 
                    AND DATE_TRUNC('month', date_effective) = DATE_TRUNC('month', p_reference_month)
                    ), 
                    0
                ) AS var_rappel_salary,
                
                -- Overtime Amount
                COALESCE(
                    (SELECT monthly_total_overtime_amount 
                    FROM v_monthly_overtime_amount 
                    WHERE id_staff = s.id 
                    AND month_start = DATE_TRUNC('month', p_reference_month)
                    ), 
                    0
                ) AS var_total_overtime,
                
                -- Seniority Bonus
                COALESCE(
                    (SELECT seniority_bonus 
                    FROM v_get_seniority_bonus_per_month 
                    WHERE staff_id = s.id
                    ), 
                    0
                ) AS var_seniority_bonus,
                
                -- Performance Bonus
                COALESCE(
                    (SELECT SUM(performance_bonus) 
                    FROM v_get_performance_bonus 
                    WHERE staff_id = s.id 
                    AND DATE_TRUNC('month', date_bonus) = DATE_TRUNC('month', p_reference_month)
                    ), 
                    0
                ) AS var_performance_bonus
            FROM 
                staff s
            WHERE 
                s.id = p_id_staff
        )

        SELECT 
            var_id_staff AS res_staff_id,
            var_first_name AS res_first_name,
            var_last_name AS res_last_name,
            var_base_salary AS res_base_salary,
            var_total_compensation AS res_total_compensation,
            var_rappel_salary AS res_rappel_salary,
            var_total_overtime AS res_total_overtime,
            var_seniority_bonus AS res_seniority_bonus,
            var_performance_bonus AS res_performance_bonus,
            ROUND(
                CASE 
                    WHEN var_base_salary = 250000.00 
                        THEN 
                            (SELECT (next_amount - previous_amount) FROM smig) + 
                            var_base_salary + 
                            var_total_compensation + 
                            var_rappel_salary + 
                            var_total_overtime + 
                            var_seniority_bonus + 
                            var_performance_bonus
                    ELSE 
                        var_base_salary + 
                        var_total_compensation + 
                        var_rappel_salary + 
                        var_total_overtime + 
                        var_seniority_bonus + 
                        var_performance_bonus
                END, 2) AS res_monthly_gross_salary,
            p_reference_month AS res_reference_month
        FROM 
            monthly_components;
    END;
    $$ LANGUAGE plpgsql;

--
-- fn_cnaps_and_ostie
--
    CREATE OR REPLACE FUNCTION fn_cnaps_and_ostie(
        p_id_staff INT, 
        p_date_reference DATE
    )
    RETURNS TABLE (
        res_staff_id INT,
        res_first_name VARCHAR(255),
        res_last_name VARCHAR(255),
        res_monthly_gross_salary NUMERIC(14, 2),
        res_cnaps_amount NUMERIC(14, 2),
        res_ostie_amount NUMERIC(14, 2),
        res_total_contributions NUMERIC(14, 2),
        res_reference_month DATE
    ) AS $$
    BEGIN
        RETURN QUERY
        SELECT 
            fsb.res_staff_id,
            fsb.res_first_name,
            fsb.res_last_name,
            fsb.res_monthly_gross_salary,
            -- Calculate CNAPS: 1% of gross salary capped at 20,000.00
            CASE 
                WHEN ROUND(fsb.res_monthly_gross_salary * 0.01, 2) > 20000.00 THEN 20000.00
                ELSE ROUND(fsb.res_monthly_gross_salary * 0.01, 2)
            END AS res_cnaps_amount,
            -- Calculate OSTIE: 1% of gross salary
            ROUND(fsb.res_monthly_gross_salary * 0.01, 2) AS res_ostie_amount,
            -- Total contributions: CNAPS + OSTIE
            ROUND(
                CASE 
                    WHEN ROUND(fsb.res_monthly_gross_salary * 0.01, 2) > 20000.00 THEN 20000.00
                    ELSE ROUND(fsb.res_monthly_gross_salary * 0.01, 2)
                END + ROUND(fsb.res_monthly_gross_salary * 0.01, 2), 2
            ) AS res_total_contributions,
            fsb.res_reference_month
        FROM 
            fn_get_salary_brut(p_id_staff, p_date_reference) AS fsb;
    END;
    $$ LANGUAGE plpgsql;


--
-- fn_revenue_imposable
--
    CREATE OR REPLACE FUNCTION fn_revenue_imposable(
        p_id_staff INT,
        p_date_reference DATE
    )
    RETURNS NUMERIC AS $$
    DECLARE
        salary_brut NUMERIC(14, 2);
        total_contributions NUMERIC(14, 2);
        revenue_imposable NUMERIC(14, 2);
    BEGIN
        SELECT res_monthly_gross_salary
        INTO salary_brut
        FROM fn_get_salary_brut(p_id_staff, p_date_reference);

        SELECT res_total_contributions
        INTO total_contributions
        FROM fn_cnaps_and_ostie(p_id_staff, p_date_reference);

        revenue_imposable := salary_brut - total_contributions;

        RETURN revenue_imposable;
    END;
    $$ LANGUAGE plpgsql;

--
-- fn_total_retenue
--
    CREATE OR REPLACE FUNCTION fn_total_retenue(
        p_id_staff INT,
        p_date_reference DATE
    )
    RETURNS NUMERIC AS $$
    DECLARE
        total_contributions NUMERIC(14, 2);
        impot_due_amount NUMERIC(14, 2);
        total_retenue NUMERIC(14, 2);
    BEGIN
        -- cnaps_and_ostie
        SELECT res_total_contributions
        INTO total_contributions
        FROM fn_cnaps_and_ostie(p_id_staff, p_date_reference);

        -- Impot generale sur le revenue 
        SELECT COALESCE(SUM(amount), 0)
        INTO impot_due_amount
        FROM impot_due
        WHERE id_staff = p_id_staff
        AND DATE_TRUNC('month', date_due) = DATE_TRUNC('month', p_date_reference);

        total_retenue := total_contributions + impot_due_amount;

        RETURN total_retenue;
    END;
    $$ LANGUAGE plpgsql;

--
-- fn_salary_net
--
    CREATE OR REPLACE FUNCTION fn_salary_net(
        p_id_staff INT,
        p_date_reference DATE
    )
    RETURNS NUMERIC AS $$
    DECLARE
        salary_brut NUMERIC(14, 2);
        total_retenue NUMERIC(14, 2);
        salary_net NUMERIC(14, 2);
    BEGIN
        SELECT res_monthly_gross_salary
        INTO salary_brut
        FROM fn_get_salary_brut(p_id_staff, p_date_reference);

        SELECT fn_total_retenue(p_id_staff, p_date_reference)
        INTO total_retenue;

        salary_net := salary_brut - total_retenue;

        RETURN salary_net;
    END;
    $$ LANGUAGE plpgsql;

--
-- fn_salary_net_a_payer
--
    CREATE OR REPLACE FUNCTION fn_salary_net_a_payer(
        p_id_staff INT,
        p_date_reference DATE
    )
    RETURNS NUMERIC AS $$
    DECLARE
        salary_net NUMERIC(14, 2);
        total_advance NUMERIC(14, 2);
        net_a_payer NUMERIC(14, 2);
    BEGIN
        -- Get the net salary using fn_salary_net
        SELECT fn_salary_net(p_id_staff, p_date_reference)
        INTO salary_net;

        -- Calculate the total advance for the given month
        SELECT COALESCE(SUM(amount), 0)
        INTO total_advance
        FROM salary_advance
        WHERE id_staff = p_id_staff
        AND DATE_TRUNC('month', date_advance) = DATE_TRUNC('month', p_date_reference);

        -- Calculate the net à payer
        net_a_payer := salary_net - total_advance;

        RETURN net_a_payer;
    END;
    $$ LANGUAGE plpgsql;
