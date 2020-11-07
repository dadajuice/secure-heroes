CREATE EXTENSION IF NOT EXISTS pgcrypto;

CREATE FUNCTION encrypt(plaintext TEXT) RETURNS TEXT
    LANGUAGE plpgsql AS $$
    DECLARE
        encryption_key TEXT;
        authentication_key TEXT;
    BEGIN

        -- Will raise exception if not defined
        encryption_key := current_setting('heroes.encryption_key')::text;

        BEGIN
            authentication_key := current_setting('heroes.authentication_key')::text;
        EXCEPTION
            WHEN OTHERS THEN authentication_key := null;
        END;

        IF authentication_key IS NOT NULL THEN
            plaintext := pgp_sym_encrypt(plaintext, authentication_key)::text;
        END IF;
        RETURN encode(pgp_sym_encrypt(plaintext, encryption_key), 'base64');
    END;
    $$;

CREATE FUNCTION decrypt(cipher TEXT) RETURNS TEXT
    LANGUAGE plpgsql AS $$
    DECLARE
        encryption_key TEXT;
        authentication_key TEXT;
        plaintext TEXT;
    BEGIN
        BEGIN
            authentication_key := current_setting('heroes.authentication_key')::text;
        EXCEPTION
            WHEN OTHERS THEN authentication_key := null;
        END;

        BEGIN
            encryption_key := current_setting('heroes.encryption_key')::text;
            plaintext := pgp_sym_decrypt(decode(cipher, 'base64')::bytea, encryption_key)::text;
            IF authentication_key IS NOT NULL THEN
                plaintext := pgp_sym_decrypt(plaintext::bytea, authentication_key)::text;
            END IF;
            RETURN plaintext;
        EXCEPTION
            WHEN OTHERS THEN RETURN '###ENCRYPTED###';
        END;
    END;
    $$;