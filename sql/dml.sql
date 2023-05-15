# mysql -u root -p < dml.sql
# Data Manipulation Language

USE DavidBotton;


INSERT INTO users (username, password)
VALUES
    ('admin', '$2y$10$KtT669.u7p.ngC1rYWz7xeuN3xMh7OeaQln9Ki3o3u2fOxvlj.p.W'),   -- admin
    ('user1', '$2y$10$HqJt7t6ao8RG8e7TYiQfn1uT7GzTbyDfgzst9Yy4/JuO3g3BHLH2Ty'),   -- user1
    ('user2', '$2y$10$RJ9bB6uz9YMGpn2xv1KlOeN5nUdH/0pI/c.wQ3y5G3HcQ5Zhx2g.a');   -- user2



INSERT INTO client (name, email) VALUES
    ('Client1', 'client1@example.com'),
    ('Client2', 'client2@example.com');

INSERT INTO rendezvous (user_id, client_id, name, description, date, start_hour, end_hour) VALUES
    (1, 1, 'Rendezvous1', 'Description1', '2023-05-15', '09:00:00', '10:00:00'),
    (2, 2, 'Rendezvous2', 'Description2', '2023-05-16', '10:00:00', '11:00:00');

INSERT INTO conge (date) VALUES
                             ('2023-05-17'),
                             ('2023-05-18');