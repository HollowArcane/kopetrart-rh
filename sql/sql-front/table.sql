CREATE DATABASE kopetrart_rh_front;
\c kopetrart_rh_front;

CREATE TABLE login(
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(64) NOT NULL,
    email VARCHAR(50) NOT NULL,
    UNIQUE(username, password)
);

CREATE TABLE message(
    id SERIAL PRIMARY KEY,
    id_login_sender INT REFERENCES login(id),
    id_login_target INT REFERENCES login(id),
    created_at TIMESTAMP NOT NULL,
    content TEXT NOT NULL
);



