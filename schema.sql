CREATE DATABASE yeticave
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE categories (
    id      INT AUTO_INCREMENT PRIMARY KEY,
    name    CHAR(128),
    code    CHAR(64)
);

CREATE TABLE lots (
    id       	INT AUTO_INCREMENT PRIMARY KEY,
    start_time 	TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    name    	CHAR(128) NOT NULL,
    comment 	CHAR(128),
    img_url		CHAR(128),
    price 		Float,
    end_time	TIMESTAMP,
    bet_step	Float,
    user_id  	INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    winner_id   INT,
    FOREIGN KEY (winner_id) REFERENCES users(id),
    categori_id INT,
    FOREIGN KEY (categori_id) REFERENCES categories(id)
);

CREATE TABLE rate (
    id       	INT AUTO_INCREMENT PRIMARY KEY,
    start_time 	TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    bet_sum		Float,
    user_id     INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    lot_id      INT,
    FOREIGN KEY (lot_id) REFERENCES lots(id)
);

CREATE TABLE users (
    id INT 		AUTO_INCREMENT PRIMARY KEY,
    dt_add 		TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    email 		CHAR(128) NOT NULL UNIQUE,
    user_name	CHAR (128) NOT NULL UNIQUE,
    pass 		CHAR (128) NOT NULL,
    avatar 		CHAR(128),
    contact		CHAR(128),
    lot_id      INT,
    FOREIGN KEY (lot_id) REFERENCES lots(id),
    rate_id     INT,
    FOREIGN KEY (rate_id) REFERENCES rate(id)
);

CREATE UNIQUE INDEX email ON users(email);
CREATE UNIQUE INDEX user_name ON users(user_name);
CREATE UNIQUE INDEX name_categori ON categories(name);
CREATE UNIQUE INDEX name_lot ON lots(name);

INSERT INTO categories
(name, code) VALUES ('Доски и лыжи','boards'),
('Крепления','attachmment'),
('Ботинки','boots'),
('Одежда','clothing'),
('Инструменты','tools'),
('Разное','other');