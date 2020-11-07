set session "heroes.encryption_key" = 'hubert';

INSERT INTO hero (real_name, alias, power_level) VALUES (encrypt('Bruce Wayne'), 'Batman', 10);
INSERT INTO hero (real_name, alias, power_level) VALUES (encrypt('Clark Kent'), 'Superman', 9);
INSERT INTO hero (real_name, alias, power_level) VALUES (encrypt('Anthony Starr'), 'Homelander', 9);
INSERT INTO hero (real_name, alias, power_level) VALUES (encrypt('Tony Stark'), 'Iron Man', 6);
INSERT INTO hero (real_name, alias, power_level) VALUES (encrypt('Matthiew Murdock'), 'Daredevil', 4);
INSERT INTO hero (real_name, alias, power_level) VALUES (encrypt('Peter Parker'), 'Spiderman', 7);