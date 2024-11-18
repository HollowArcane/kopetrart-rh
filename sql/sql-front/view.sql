CREATE OR REPLACE VIEW v_message AS
SELECT
    m.id,
    m.content,
    m.id_login_sender,
    CASE
        WHEN l1.username IS NULL
            THEN 'AI'
        ELSE l1.username
    END AS user_sender,
    m.id_login_target,
    CASE
        WHEN l2.username IS NULL
            THEN 'AI'
        ELSE l2.username
    END AS user_target,
    m.created_at
FROM
    message m
LEFT JOIN
    login l1 ON m.id_login_sender = l1.id
LEFT JOIN
    login l2 ON m.id_login_target = l2.id
;


