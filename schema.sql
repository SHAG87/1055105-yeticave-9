CREATE DATABASE yeticave
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE categories
(
    id   TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(128) NOT NULL UNIQUE,
    code VARCHAR(64)  NOT NULL UNIQUE
);

CREATE TABLE users
(
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    date_create TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    email       VARCHAR(128) NOT NULL UNIQUE,
    user_name   VARCHAR(128) NOT NULL,
    password    VARCHAR(128) NOT NULL,
    avatar      VARCHAR(512),
    contact     VARCHAR(256) NOT NULL,
    INDEX user_email (email)
);

CREATE TABLE lots
(
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    start_time  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    name        VARCHAR(256) NOT NULL UNIQUE,
    description text         NOT NULL,
    img_url     VARCHAR(512) NOT NULL,
    price       INT UNSIGNED NOT NULL,
    end_time    TIMESTAMP    NOT NULL,
    bet_step    INT UNSIGNED NOT NULL,
    owner_id    INT  UNSIGNED        NOT NULL,
    winner_id   INT UNSIGNED,
    category_id TINYINT UNSIGNED      NOT NULL,
    FOREIGN KEY (winner_id) REFERENCES users(id),
    FOREIGN KEY (owner_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id),
    INDEX lot_index (name, category_id)

);

CREATE TABLE bets
(
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    start_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    bet_sum    INT NOT NULL,
    user_id    INT UNSIGNED NOT NULL,
    lot_id     INT UNSIGNED NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (lot_id) REFERENCES lots(id),
    INDEX user_lot (user_id, lot_id)
);