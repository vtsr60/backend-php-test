INSERT INTO users (username, password) VALUES
('user1', '$2y$10$AEWihqMnxhmjMd1RL91UH.ua./1APPTehGK8cZW3Sp47mYQnOtzz2'),
('user2', '$2y$10$iOcW6f.emucjbcWYElbzy.rCQECbCdL08.s6Y5M3sBytOWgCSsEK.'),
('user3', '$2y$10$w7Fm7bOoNRu8efd8cobFWOavGFu74WXeUBrhXjScRrrK73A1impgC');

INSERT INTO todos (user_id, description) VALUES
(1, 'Vivamus tempus'),
(1, 'lorem ac odio'),
(1, 'Ut congue odio'),
(1, 'Sodales finibus'),
(1, 'Accumsan nunc vitae'),
(2, 'Lorem ipsum'),
(2, 'In lacinia est'),
(2, 'Odio varius gravida');