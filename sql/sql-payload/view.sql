--
--v_weekly_overtime
--
    CREATE OR REPLACE VIEW v_weekly_overtime AS
    SELECT 
        id_staff,
        DATE_TRUNC('week', date_overtime) AS week_start, -- Start of the week
        (DATE_TRUNC('week', date_overtime) + INTERVAL '6 days')::DATE AS week_end, -- End of the week
        SUM(CASE WHEN id_overtime_type = 1 THEN quantity_overtime ELSE 0 END) AS total_first_8_hours,
        SUM(CASE WHEN id_overtime_type = 2 THEN quantity_overtime ELSE 0 END) AS total_last_12_hours,
        SUM(CASE WHEN id_overtime_type = 3 THEN quantity_overtime ELSE 0 END) AS total_weekend,
        SUM(CASE WHEN id_overtime_type = 4 THEN quantity_overtime ELSE 0 END) AS total_holiday,
        SUM(quantity_overtime) AS total_overtime 
    FROM 
        staff_overtime
    GROUP BY 
        id_staff,
        DATE_TRUNC('week', date_overtime)
    ORDER BY 
        week_start;

--
-- v_weekly_overtime
--
    CREATE OR REPLACE VIEW v_monthly_overtime AS
    SELECT 
        id_staff,
        DATE_TRUNC('month', date_overtime) AS period_start, -- First day of the month
        (DATE_TRUNC('month', date_overtime) + INTERVAL '1 month - 1 day')::DATE AS period_end, -- Last day of the month
        SUM(CASE WHEN id_overtime_type = 1 THEN quantity_overtime ELSE 0 END) AS total_first_8_hours,
        SUM(CASE WHEN id_overtime_type = 2 THEN quantity_overtime ELSE 0 END) AS total_last_12_hours,
        SUM(CASE WHEN id_overtime_type = 3 THEN quantity_overtime ELSE 0 END) AS total_weekend,
        SUM(CASE WHEN id_overtime_type = 4 THEN quantity_overtime ELSE 0 END) AS total_holiday,
        SUM(quantity_overtime) AS total_overtime -- Total for all types
    FROM 
        staff_overtime
    GROUP BY 
        DATE_TRUNC('month', date_overtime),
        id_staff
    ORDER BY 
        period_start;

--
-- v_weekly_overtime_amount
--
    CREATE OR REPLACE VIEW v_weekly_overtime_amount AS
    WITH weekly_overtime AS (
        SELECT 
            so.id_staff,
            s.d_salary,
            DATE_TRUNC('week', so.date_overtime) AS week_start,
            (DATE_TRUNC('week', so.date_overtime) + INTERVAL '6 days')::DATE AS week_end,
            SUM(CASE WHEN so.id_overtime_type = 1 THEN so.quantity_overtime ELSE 0 END) AS total_first_8_hours,
            SUM(CASE WHEN so.id_overtime_type = 2 THEN so.quantity_overtime ELSE 0 END) AS total_last_12_hours,
            SUM(CASE WHEN so.id_overtime_type = 3 THEN so.quantity_overtime ELSE 0 END) AS total_weekend,
            SUM(CASE WHEN so.id_overtime_type = 4 THEN so.quantity_overtime ELSE 0 END) AS total_holiday
        FROM 
            staff_overtime so
        JOIN 
            staff s ON so.id_staff = s.id
        GROUP BY 
            so.id_staff, 
            s.d_salary,
            DATE_TRUNC('week', so.date_overtime)
    ),
    overtime_calculations AS (
        SELECT 
            wo.id_staff,
            wo.week_start,
            wo.week_end,
            wo.d_salary,
            (wo.d_salary / 173.33) AS hourly_rate,  -- only non-agricole secteurs
            ot1.rate AS first_8_hours_rate,
            ot2.rate AS last_12_hours_rate,
            ot3.rate AS weekend_rate,
            ot4.rate AS holiday_rate,
            
            -- Calculate overtime amount for each type
            ROUND((wo.d_salary / 173.33) * wo.total_first_8_hours * ot1.rate, 2) AS first_8_hours_amount,
            ROUND((wo.d_salary / 173.33) * wo.total_last_12_hours * ot2.rate, 2) AS last_12_hours_amount,
            ROUND((wo.d_salary / 173.33) * wo.total_weekend * ot3.rate, 2) AS weekend_amount,
            ROUND((wo.d_salary / 173.33) * wo.total_holiday * ot4.rate, 2) AS holiday_amount
        FROM 
            weekly_overtime wo
        JOIN 
            overtime_type ot1 ON ot1.id = 1  -- First 8 hours
        JOIN 
            overtime_type ot2 ON ot2.id = 2  -- Last 12 hours
        JOIN 
            overtime_type ot3 ON ot3.id = 3  -- Weekend
        JOIN 
            overtime_type ot4 ON ot4.id = 4  -- Holiday
    )
    SELECT 
        id_staff,
        week_start,
        week_end,
        d_salary AS salary,
        hourly_rate,
        first_8_hours_amount,
        last_12_hours_amount,
        weekend_amount,
        holiday_amount,
        ROUND(first_8_hours_amount + last_12_hours_amount + weekend_amount + holiday_amount, 2) AS total_overtime_amount
    FROM 
        overtime_calculations
    ORDER BY 
        id_staff, 
        week_start;

--
-- v_monthly_overtime_amount
--
    CREATE OR REPLACE VIEW v_monthly_overtime_amount AS
    WITH monthly_overtime AS (
        SELECT
            id_staff,
            DATE_TRUNC('month', week_start) AS month_start,
            MAX(salary) AS salary,  -- Assumes salary does not change mid-month
            SUM(first_8_hours_amount) AS monthly_first_8_hours_amount,
            SUM(last_12_hours_amount) AS monthly_last_12_hours_amount,
            SUM(weekend_amount) AS monthly_weekend_amount,
            SUM(holiday_amount) AS monthly_holiday_amount,
            SUM(total_overtime_amount) AS monthly_total_overtime_amount
        FROM 
            v_weekly_overtime_amount
        GROUP BY 
            id_staff, DATE_TRUNC('month', week_start)
    )
    SELECT
        id_staff,
        month_start,
        salary,
        monthly_first_8_hours_amount,
        monthly_last_12_hours_amount,
        monthly_weekend_amount,
        monthly_holiday_amount,
        monthly_total_overtime_amount
    FROM 
        monthly_overtime
    ORDER BY 
        id_staff, month_start;

--
-- v_detention_on_absence
--
    CREATE OR REPLACE VIEW v_detention_on_absence AS
    WITH monthly_absences AS (
        SELECT 
            id_staff,
            DATE_TRUNC('month', date_absence) AS month_start,
            (DATE_TRUNC('month', date_absence) + INTERVAL '1 month - 1 day')::DATE AS month_end,
            SUM(number_day_absence) AS total_absence_days
        FROM 
            absence
        GROUP BY 
            id_staff, 
            DATE_TRUNC('month', date_absence)
    ),
    salary_calculations AS (
        SELECT 
            ma.id_staff,
            ma.month_start,
            ma.month_end,
            s.d_salary,
            ma.total_absence_days,
            -- Calculate daily rate based on the number of days in the month
            CASE 
                WHEN EXTRACT(MONTH FROM ma.month_start) IN (1, 3, 5, 7, 8, 10, 12) THEN 
                    s.d_salary / 31.0
                WHEN EXTRACT(MONTH FROM ma.month_start) IN (4, 6, 9, 11) THEN 
                    s.d_salary / 30.0
                WHEN EXTRACT(MONTH FROM ma.month_start) = 2 THEN 
                    CASE 
                        WHEN EXTRACT(YEAR FROM ma.month_start) % 4 = 0 AND 
                            (EXTRACT(YEAR FROM ma.month_start) % 100 != 0 OR 
                            EXTRACT(YEAR FROM ma.month_start) % 400 = 0) 
                        THEN s.d_salary / 29.0
                        ELSE s.d_salary / 28.0
                    END
            END AS daily_rate,
            COALESCE((
                SELECT day_vacation_left 
                FROM fn_staff_vacation_status(CURRENT_DATE) AS vacation_status 
                WHERE vacation_status.id_staff = ma.id_staff
            ), 0) AS day_vacation_left
        FROM 
            monthly_absences ma
        JOIN 
            staff s ON ma.id_staff = s.id
    )
    SELECT 
        sc.id_staff,
        sc.month_start,
        sc.month_end,
        sc.d_salary,
        sc.total_absence_days,
        sc.daily_rate,
        sc.day_vacation_left,
        CASE 
            WHEN sc.day_vacation_left < sc.total_absence_days THEN 
                ROUND((sc.total_absence_days - sc.day_vacation_left) * sc.daily_rate, 2)
            ELSE 
                0
        END AS detention_amount
    FROM 
        salary_calculations sc
    WHERE 
        sc.total_absence_days > 0
    ORDER BY 
        sc.id_staff, 
        sc.month_start;

--
-- v_rappel_salary
--
    CREATE OR REPLACE VIEW v_rappel_salary AS
    WITH latest_smig_changes AS (
        SELECT 
            s.id AS id_staff,
            s.d_salary AS current_salary,
            smg.previous_amount AS prev_smig_amount,
            smg.next_amount AS new_smig_amount,
            smg.date_start AS smig_date_start,
            smg.date_effective_next_amount AS date_effective
        FROM staff s
        CROSS JOIN (
            SELECT * FROM smig 
            WHERE date_effective_next_amount IS NOT NULL 
            ORDER BY date_effective_next_amount DESC 
            LIMIT 1
        ) smg
        WHERE s.d_salary = smg.previous_amount
    ),
    salary_cumul AS (
        SELECT 
            id_staff,
            current_salary,
            new_smig_amount,
            date_effective,
            EXTRACT(MONTH FROM AGE(date_effective, smig_date_start)) AS months_before_adjustment,
            (new_smig_amount - current_salary) * EXTRACT(MONTH FROM AGE(date_effective, smig_date_start)) AS salary_cumul,
            new_smig_amount + (
                (new_smig_amount - current_salary) * EXTRACT(MONTH FROM AGE(date_effective, smig_date_start))
            ) AS salary_on_date_effective
        FROM latest_smig_changes
    )
    SELECT 
        id_staff,
        ROUND(salary_cumul, 2) AS salary_cumul,
        ROUND(salary_on_date_effective, 2) AS salary_on_date_effective,
        date_effective
    FROM salary_cumul;

--
-- v_get_seniority_bonus_per_month
--
    /*
        0–5 years: 0% bonus
        5–10 years: 5% bonus
        10–15 years: 10% bonus
        15+ years: 15% bonus
    */
    CREATE OR REPLACE VIEW v_get_seniority_bonus_per_month AS
    WITH seniority_years AS (
        SELECT
            id AS staff_id,
            first_name,
            last_name,
            d_salary,
            d_date_contract_start,
            d_date_contract_end,
            CASE
                WHEN d_date_contract_end IS NOT NULL THEN
                    DATE_PART('year', AGE(d_date_contract_end, d_date_contract_start))
                ELSE
                    DATE_PART('year', AGE(CURRENT_DATE, d_date_contract_start))
            END AS years_of_service
        FROM staff
    ),
    bonus_calculation AS (
        SELECT
            staff_id,
            first_name,
            last_name,
            d_salary,
            years_of_service,
            CASE
                WHEN years_of_service < 5 THEN 0
                WHEN years_of_service BETWEEN 5 AND 9 THEN 0.05 * d_salary
                WHEN years_of_service BETWEEN 10 AND 14 THEN 0.10 * d_salary
                ELSE 0.15 * d_salary
            END AS seniority_bonus
        FROM seniority_years
    )
    SELECT
        staff_id,
        first_name,
        last_name,
        d_salary,
        years_of_service,
        seniority_bonus
    FROM bonus_calculation;

--
-- v_get_performance_bonus
-- 
    /*
        Performance Bonus = d_salary × (performance % /100)
        150% =>  to 0.15 * d_salary
        100% =>  no additional bonus
        100% => no bonus or a deduction 
    */
    CREATE OR REPLACE VIEW v_get_performance_bonus AS
    WITH performance_data AS (
        SELECT
            pb.id AS performance_bonus_id,
            s.id AS staff_id,
            s.first_name,
            s.last_name,
            s.d_salary,
            pb.date_bonus,
            pb.performance,
            CASE
                WHEN pb.performance >= 100 THEN (s.d_salary * (pb.performance / 1000))
                ELSE 0      -- adjust if you want deductions for performance below 100%
            END AS performance_bonus
        FROM performance_bonus pb
        JOIN staff s ON pb.id_staff = s.id
    )
    SELECT
        performance_bonus_id,
        staff_id,
        first_name,
        last_name,
        d_salary,
        date_bonus,
        performance,
        performance_bonus
    FROM performance_data;


-- SELECT
--     s.id,
--     cb.date_validated,
--     SUM(cb.salary_bonus) AS salary_bonus,
-- FROM 
--     staff s
-- JOIN mvt_staff_contract msc ON s.id = msc.id_staff
-- JOIN contract_breach cb ON s.id = cb.id_staff