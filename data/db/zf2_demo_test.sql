SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
SET NAMES 'utf8';
USE `zf2.demo.test`;
DROP TABLE IF EXISTS chat_messages;
DROP TABLE IF EXISTS image_uploads;
DROP TABLE IF EXISTS log;
DROP TABLE IF EXISTS store_orders;
DROP TABLE IF EXISTS store_products;
DROP TABLE IF EXISTS uploads;
DROP TABLE IF EXISTS uploads_sharing;
DROP TABLE IF EXISTS user;
USE `zf2.demo.test`;


CREATE TABLE user (
                      id int UNSIGNED NOT NULL AUTO_INCREMENT,
                      name text NOT NULL,
                      email varchar(255) NOT NULL,
                      password text NOT NULL,
                      birthday date DEFAULT NULL,
                      phone varchar(255) DEFAULT NULL,
                      address varchar(255) DEFAULT NULL,
                      description varchar(1000) DEFAULT NULL,
                      type tinyint DEFAULT NULL,
                      created datetime DEFAULT NULL,
                      updated datetime DEFAULT NULL,
                      PRIMARY KEY (id)
)
    ENGINE = INNODB,
    AUTO_INCREMENT = 323,
    AVG_ROW_LENGTH = 514,
    CHARACTER SET utf8,
    COLLATE utf8_general_ci;

ALTER TABLE user
    ADD UNIQUE INDEX idx_email (email);


CREATE TABLE uploads_sharing (
                                 id int NOT NULL AUTO_INCREMENT,
                                 upload_id int NOT NULL,
                                 user_id int NOT NULL,
                                 PRIMARY KEY (id)
)
    ENGINE = INNODB,
    AUTO_INCREMENT = 20,
    AVG_ROW_LENGTH = 2048,
    CHARACTER SET utf8,
    COLLATE utf8_general_ci;

ALTER TABLE uploads_sharing
    ADD UNIQUE INDEX upload_id (upload_id, user_id);


CREATE TABLE uploads (
                         id int NOT NULL AUTO_INCREMENT,
                         filename varchar(255) NOT NULL,
                         label varchar(255) NOT NULL,
                         user_id int NOT NULL,
                         PRIMARY KEY (id)
)
    ENGINE = INNODB,
    AUTO_INCREMENT = 14,
    AVG_ROW_LENGTH = 3276,
    CHARACTER SET utf8,
    COLLATE utf8_general_ci;

ALTER TABLE uploads
    ADD UNIQUE INDEX filename (filename);


CREATE TABLE store_products (
                                id int NOT NULL AUTO_INCREMENT,
                                name varchar(255) NOT NULL,
                                `desc` varchar(255) NOT NULL,
                                cost float(9, 2) NOT NULL,
                                PRIMARY KEY (id)
)
    ENGINE = INNODB,
    AUTO_INCREMENT = 7,
    AVG_ROW_LENGTH = 2730,
    CHARACTER SET utf8,
    COLLATE utf8_general_ci;


CREATE TABLE store_orders (
                              id int NOT NULL AUTO_INCREMENT,
                              store_product_id int NOT NULL,
                              qty int NOT NULL,
                              total float(9, 2) NOT NULL,
                              status enum ('new', 'completed', 'shipped', 'cancelled') DEFAULT NULL,
                              stamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                              first_name varchar(255) DEFAULT NULL,
                              last_name varchar(255) DEFAULT NULL,
                              email varchar(255) DEFAULT NULL,
                              ship_to_street varchar(255) DEFAULT NULL,
                              ship_to_city varchar(255) DEFAULT NULL,
                              ship_to_state varchar(2) DEFAULT NULL,
                              ship_to_zip int DEFAULT NULL,
                              PRIMARY KEY (id)
)
    ENGINE = INNODB,
    AUTO_INCREMENT = 16,
    AVG_ROW_LENGTH = 2730,
    CHARACTER SET utf8,
    COLLATE utf8_general_ci;


CREATE TABLE log (
                     id bigint NOT NULL AUTO_INCREMENT,
                     date datetime NOT NULL,
                     type int NOT NULL,
                     event varchar(1000) NOT NULL,
                     e varchar(1000) DEFAULT NULL,
                     PRIMARY KEY (id)
)
    ENGINE = INNODB,
    AUTO_INCREMENT = 708,
    AVG_ROW_LENGTH = 82,
    CHARACTER SET utf8,
    COLLATE utf8_general_ci;


CREATE TABLE image_uploads (
                               id int NOT NULL AUTO_INCREMENT,
                               filename varchar(255) NOT NULL,
                               thumbnail varchar(255) NOT NULL,
                               label varchar(255) NOT NULL,
                               user_id int NOT NULL,
                               PRIMARY KEY (id)
)
    ENGINE = INNODB,
    AUTO_INCREMENT = 7,
    AVG_ROW_LENGTH = 8192,
    CHARACTER SET utf8,
    COLLATE utf8_general_ci;

ALTER TABLE image_uploads
    ADD UNIQUE INDEX filename (filename);


CREATE TABLE chat_messages (
                               id int NOT NULL AUTO_INCREMENT,
                               user_id int NOT NULL,
                               message varchar(1255) NOT NULL,
                               stamp timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                               PRIMARY KEY (id)
)
    ENGINE = INNODB,
    AUTO_INCREMENT = 23,
    AVG_ROW_LENGTH = 1170,
    CHARACTER SET utf8,
    COLLATE utf8_general_ci;


INSERT INTO user VALUES
                     (1, 'admin', 'admin@mail.ru', '21232f297a57a5a743894a0e4a801fc3', '2021-09-23', '46456-8989', 'Kiev', 'By default', 1, '2021-10-03 01:12:11', '2021-10-03 01:12:12'),
                     (2, 'admin6', 'admin6@mail.ru', 'c6b853d6a7cc7ec49172937f68f446c8', '1999-03-26', '+380987776655', 'Sumy', 'Using this feature', 4, '2021-06-07 19:24:18', '2021-06-07 19:25:19'),
                     (3, 'Sim Turner', 'labadie.terrence@hotmail.com', 'aea7eecb0462d963461211375e424ce9', '1980-08-14', '997-477-2528', '26647 Harold StreetsPort Karina, CO 80283', 'Aliquam quia voluptatem enim. Ipsam velit quasi maxime quis nesciunt sequi repellendus. Et nihil id nostrum vero. Voluptas est ab molestiae.', 4, '2021-06-07 20:56:27', NULL);


INSERT INTO uploads_sharing VALUES
                                (1, 1, 1),
                                (2, 2, 1);


INSERT INTO uploads VALUES
                        (1, 'php.jpg', 'PHP', 1),
                        (2, 'node.png', 'Node JS', 2);


INSERT INTO store_products VALUES
                               (1, 'Продукт 1', 'классный продукт', 100.00),
                               (2, 'Продукт 2', 'классный продукт', 150.00);


INSERT INTO store_orders VALUES
                             (1, 1, 2, 200.00, 'new', '2021-10-26 23:11:45', '', '', '', '', '', '', 1),
                             (2, 2, 2, 300.00, 'new', '2021-10-26 23:11:45', '', '', '', '', '', '', 1);


INSERT INTO log VALUES
                    (1, '2021-10-03 16:14:27', 6, 'StoreController', NULL),
                    (2, '2021-10-03 16:14:27', 6, 'controller', NULL);


INSERT INTO image_uploads VALUES
                              (1, 'EGV_0028.jpg', 'tn_php5F2A.tmp_EGV_0028.jpg', 'EGV_0028', 8),
                              (2, 'EGV_0020.jpg', 'tn_php9801.tmp_EGV_0020.jpg', 'EGV_0020', 8);


INSERT INTO chat_messages VALUES
                              (1, 1, 'Здесь могут производиться вычисления с указанием единиц измерения, тут можно складывать величины, выраженные в одних и тех же единицах измерения, можно умножать некие величины на значения, единицы измерения которых не указаны.', '2021-05-04 15:38:46'),
                              (2, 2, 'Hisense Smart TV быстрая установка через браузер', '2021-05-04 16:04:49'),
                              (3, 1, 'Такому рецепту меня научила бабушка. А я вам предложу. Только обычные дрожжи здесь заменены на сухие активные. Они не такие капризные, испечь куличи по бабушкиному рецепту с ними гораздо проще.', '2021-05-04 16:07:03'),
                              (4, 3, 'Это позволит вам использовать $this->getServiceLocator() в контроллере.', '2021-05-04 16:41:27');


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS;