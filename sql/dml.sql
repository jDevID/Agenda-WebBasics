# mysql -u root -p < dml.sql
# Data Manipulation Language

USE DavidBotton;

INSERT INTO users (username, password)
VALUES ('abc', '$2y$10$eULLEYT58mRfJa61jhczSOtjeTsEV59JceBEfuCJanx0HGGWkKRQa'); -- password = abc


INSERT INTO client (id, name, email)
VALUES (1, 'John Smith', 'john@example.com'),
       (2, 'Jane Doe', 'jane@example.com'),
       (3, 'David Johnson', 'david@example.com'),
       (4, 'Emily Wilson', 'emily@example.com'),
       (5, 'Michael Brown', 'michael@example.com'),
       (6, 'Olivia Davis', 'olivia@example.com'),
       (7, 'Daniel Martinez', 'daniel@example.com'),
       (8, 'Sophia Anderson', 'sophia@example.com');

INSERT INTO rendezvous (user_id, client_id, name, description, date, start_hour, end_hour)
VALUES (1, 1, 'John Smith', 'Discuss project details', '2023-05-16', '09:00:00', '10:00:00'),
       (1, 2, 'Jane Doe', 'Review marketing strategy', '2023-05-20', '09:00:00', '10:00:00'),
       (1, 3, 'David Johnson', 'Provide software demo', '2023-05-22', '09:00:00', '10:00:00'),
       (1, 4, 'Emily Wilson', 'Discuss budget allocation', '2023-05-25', '09:00:00', '10:00:00'),
       (1, 5, 'Michael Brown', 'Finalize partnership agreement', '2023-05-26', '09:00:00', '10:00:00'),
       (1, 6, 'Olivia Davis', 'Brainstorm new ideas', '2023-05-29', '09:00:00', '10:00:00'),
       (1, 7, 'Daniel Martinez', 'Present sales report', '2023-06-05', '09:00:00', '10:00:00'),
       (1, 8, 'Sophia Anderson', 'Discuss HR policies', '2023-06-10', '09:00:00', '10:00:00'),
       (1, 1, 'John Smith', 'Review project progress', '2023-06-20', '09:00:00', '10:00:00'),
       (1, 2, 'Jane Doe', 'Discuss upcoming events', '2023-06-25', '09:00:00', '10:00:00');

INSERT INTO conge (date)
VALUES ('2023-05-17'),
       ('2023-05-18'),
       ('2023-05-19'),
       ('2023-05-21'),
       ('2023-05-23'),
       ('2023-05-24');
