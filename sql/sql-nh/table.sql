-- INTERESTS
CREATE TABLE interests (
    id SERIAL PRIMARY KEY,
    label VARCHAR(50) NOT NULL
);

-- QUALITIES
CREATE TABLE qualities (
    id SERIAL PRIMARY KEY,
    label VARCHAR(50) NOT NULL
);

-- EDUCATIONS
CREATE TABLE educations (
    id SERIAL PRIMARY KEY,
    label VARCHAR(50) NOT NULL
);

-- DENORMALIZED CV TABLE
CREATE TABLE denormalized_cv (
    id SERIAL PRIMARY KEY,
    id_cv INT NOT NULL REFERENCES cv(id),
    candidat_name VARCHAR(255) NOT NULL,
    poste VARCHAR NOT NULL,
    date_depot_dossier DATE NOT NULL,
    adequate INT DEFAULT NULL,      -- boolean
    potentiel INT DEFAULT NULL      -- boolean
);

--
-- DENORMALIZED CV TABLE
--
CREATE TABLE denormalized_cv_interests (
    id SERIAL PRIMARY KEY,
    id_cv_denormalized INT NOT NULL REFERENCES denormalized_cv(id),
    id_interest INT NOT NULL REFERENCES interests (id)
);

CREATE TABLE denormalized_cv_qualities (
    id SERIAL PRIMARY KEY,
    id_cv_denormalized INT NOT NULL REFERENCES denormalized_cv(id),
    id_quality INT NOT NULL REFERENCES qualities (id)
);

CREATE TABLE denormalized_cv_education (
    id SERIAL PRIMARY KEY,
    id_cv_denormalized INT NOT NULL REFERENCES denormalized_cv(id),
    id_education INT NOT NULL REFERENCES educations (id)
);

CREATE TABLE denormalized_cv_experiences (
    id SERIAL PRIMARY KEY,
    id_cv_denormalized INT NOT NULL REFERENCES denormalized_cv(id),
    label VARCHAR(50) NOT NULL,
    month_duration DECIMAL(10, 2) NOT NULL
);