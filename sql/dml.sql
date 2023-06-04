-- tous les mdp sont : Dupontelle4000
INSERT INTO users (username, password, role)
VALUES ('Dupontelle', '$2y$10$L.L4ELm41LLpz3JKe44GtuERl.asjUUloFubDhBsOjOkDGImqpOiG', 'admin');
INSERT INTO users (username, password, role)
VALUES ('Xavier Cha', '$2y$10$L.L4ELm41LLpz3JKe44GtuERl.asjUUloFubDhBsOjOkDGImqpOiG', 'client');
INSERT INTO users (username, password, role)
VALUES ('Billy Bo', '$2y$10$L.L4ELm41LLpz3JKe44GtuERl.asjUUloFubDhBsOjOkDGImqpOiG', 'client');
INSERT INTO users (username, password, role)
VALUES ('Karine Jo', '$2y$10$L.L4ELm41LLpz3JKe44GtuERl.asjUUloFubDhBsOjOkDGImqpOiG', 'client');

INSERT INTO conge (date)
VALUES ('2023-06-03'),
       ('2023-06-04'),
       ('2023-06-10'),
       ('2023-06-11'),
       ('2023-06-17'),
       ('2023-06-18'),
       ('2023-06-24'),
       ('2023-06-25');

INSERT INTO rendezvous (user_id, description, date, start_hour, end_hour)
VALUES (3, 'Billy Bo  s\'est blessé au ski en tombant ', '2023-06-05', '09:00:00', '10:00:00'),
       (3, 'Billy Bo  s\'est blessé au ski en tombant le retour', '2023-06-12', '14:00:00', '15:00:00'),
       (4, 'Première visite au centre Jo K.', '2023-06-19', '16:00:00', '17:00:00');