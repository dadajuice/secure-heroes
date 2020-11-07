CREATE TABLE IF NOT EXISTS "user"
(
    user_id SERIAL PRIMARY KEY,
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    cellphone VARCHAR(255) NULL,
    root_level BOOLEAN DEFAULT FALSE,
    date_time TIMESTAMP DEFAULT now()
);

CREATE TABLE IF NOT EXISTS authentication
(
    user_id INT PRIMARY KEY NOT NULL,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    validator VARCHAR(255) NOT NULL,
    date_time TIMESTAMP DEFAULT now(),
    last_connection TIMESTAMP DEFAULT NULL,
    last_activity TIMESTAMP DEFAULT NULL,
    multi_factor_mask INTEGER DEFAULT 0,
    google_authenticator_secret VARCHAR(255) DEFAULT NULL,
    locked BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES "user" (user_id)
        ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS hero
(
    hero_id SERIAL PRIMARY KEY,
    real_name VARCHAR(255) NOT NULL,
    alias VARCHAR(255) NOT NULL,
    power_level INTEGER DEFAULT 0
);