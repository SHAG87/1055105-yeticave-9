/*
 В Данном файле представлены решения задач из module4-task2
 */

INSERT INTO categories
(name, code) VALUES ('Доски и лыжи','boards'),
                    ('Крепления','attachmment'),
                    ('Ботинки','boots'),
                    ('Одежда','clothing'),
                    ('Инструменты','tools'),
                    ('Разное','other');

INSERT INTO users (email, user_name, password, contact) VALUES
('Vladimir@mail.ru','Vladimir','password',001),
('Dmitry@yandex.ru','Dima','qw123qw',002);

INSERT INTO lots
(name, category_id, price, img_url, owner_id, description, end_time, bet_step) VALUES
('2014 Rossignol District Snowboard', 1 , 10999, 'img/lot-1.jpg', 1, 'Snowboard', '2019-06-30', 1000),
('DC Ply Mens 2016/2017 Snowboard', 1, 15999,'img/lot-2.jpg', 1, 'Snowboard', '2019-05-30', 1000),
('Крепления Union Contact Pro 2015 года размер L/XL', 2, 8000, 'img/lot-3.jpg', 2, 'Крепления', '2019-04-30', 1000),
('Ботинки для сноуборда DC Mutiny Charocal', 3, 10999, 'img/lot-4.jpg', 1, 'Ботинки', '2019-06-30', 1000),
('Куртка для сноуборда DC Mutiny Charocal', 4, 7500, 'img/lot-5.jpg', 2, 'Куртка', '2019-06-30', 1000),
('Маска Oakley Canopy', 6, 5400, 'img/lot-6.jpg', 2, 'Маска', '2019-06-30', 1000);

INSERT INTO bets
(bet_sum, user_id, lot_id) VALUES
(500, 1, 2),
(4873, 2, 4);

/*
получить все категории
 */
SELECT * FROM categories;

/*получить самые новые, открытые лоты. Каждый лот должен включать название,
стартовую цену, ссылку на изображение, ЦЕНУ, название категории;
Здесь я объединил 3 таблицы
 */
SELECT l.name, price, img_url, c.NAME, bet_sum FROM lots l
                                                        JOIN bets b ON b.lot_id=l.id
                                                        JOIN categories c ON l.category_id = c.id
WHERE winner_id IS NULL ORDER BY l.start_time DESC;

/*
показать лот по его id. Получите также название категории, к которой принадлежит лот;
 */
SELECT * FROM lots l
                  JOIN categories c ON l.category_id = c.id
WHERE l.id = 1;

/*
обновить название лота по его идентификатору;
 */
UPDATE lots SET NAME ="2015 Rossignol District Snowboard"
WHERE id = 1;

/*
получить список самых свежих ставок для лота по его идентификатору.
 */
SELECT bet_sum  FROM bets
WHERE id = '1' ORDER BY bet_sum DESC