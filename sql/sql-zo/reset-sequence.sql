CREATE OR REPLACE FUNCTION reset_all_sequences() 
RETURNS void AS 
$$
DECLARE
    tbl RECORD;
    seq_name TEXT;
BEGIN
    -- Loop through each table in the current schema
    FOR tbl IN 
        SELECT table_name, column_name, 
               pg_get_serial_sequence(table_name, column_name) AS seq_name
        FROM information_schema.columns 
        WHERE column_default LIKE 'nextval%'
    LOOP
        -- Reset the sequence to max value + 1
        EXECUTE format('SELECT setval(%L, (SELECT COALESCE(MAX(%I), 0) + 1 FROM %I))', 
                       tbl.seq_name, tbl.column_name, tbl.table_name);
    END LOOP;
END;
$$
LANGUAGE plpgsql;