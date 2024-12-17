CREATE DATABASE IF NOT EXISTS `db_storage`;
ALTER DATABASE `db_storage` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE `db_storage`;

CREATE TABLE IF NOT EXISTS `Roles`(
    `id_role` INT NOT NULL AUTO_INCREMENT,
    `role_name` VARCHAR(20) NOT NULL,
    CONSTRAINT `roles_pk`
    PRIMARY KEY (id_role)
);

CREATE TABLE IF NOT EXISTS `Users`(
    `id_user` INT NOT NULL AUTO_INCREMENT,
    `login` VARCHAR(20) NOT NULL UNIQUE,
    `password` VARCHAR(50) NOT NULL,
    `role_id` INT NOT NULL,
    CONSTRAINT `users_pk`
    PRIMARY KEY (`id_user`),
    CONSTRAINT `users_roles_fk`
    FOREIGN KEY (`role_id`) REFERENCES `Roles`(`id_role`) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS `Position`(
    `id_position` INT NOT NULL AUTO_INCREMENT,
    `position_name` VARCHAR(45) NOT NULL,
    CONSTRAINT `position_pk`
    PRIMARY KEY (`id_position`)
);

CREATE TABLE IF NOT EXISTS `Staff`(
    `id_staff` INT NOT NULL AUTO_INCREMENT,
    `first_name` VARCHAR(45) NOT NULL,
    `last_name` VARCHAR(45) NOT NULL,
    `middle_name` VARCHAR(45) DEFAULT NULL,
    `position_id` INT NOT NULL,
    `user_id` INT UNIQUE DEFAULT NULL,
    `address` VARCHAR(100) NOT NULL,
    `phone_number` VARCHAR(20),
    CONSTRAINT `staff_pk`
    PRIMARY KEY (`id_staff`),
    CONSTRAINT `staff_position_fk`
    FOREIGN KEY (`position_id`) REFERENCES `Position`(`id_position`) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `staff_user_fk`
    FOREIGN KEY (`user_id`) REFERENCES `Users`(`id_user`) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS `Products`(
    `id_product` INT NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(50) NOT NULL,
    `count` INT NOT NULL DEFAULT 0,
    `shelf` INT NOT NULL,
    `row` INT NOT NULL,
    `column` INT NOT NULL,
    CONSTRAINT `products_pk`
    PRIMARY KEY (`id_product`)
);

CREATE TABLE IF NOT EXISTS `Delivery`(
    `staff_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `delivery_date` DATETIME NOT NULL,
    `provider` VARCHAR(45) NOT NULL,
    `count` INT NOT NULL,
    CONSTRAINT `delivery_pk`
    PRIMARY KEY (`staff_id`, `product_id`, `delivery_date`),
    CONSTRAINT `delivery_staff_fk`
    FOREIGN KEY (`staff_id`) REFERENCES `Staff`(`id_staff`) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `delivery_products_fk`
    FOREIGN KEY (`product_id`) REFERENCES `Products`(`id_product`) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS `Shipment`(
    `staff_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `shipment_date` DATETIME NOT NULL,
    `provider` VARCHAR(45) NOT NULL,
    `count` INT NOT NULL,
    CONSTRAINT `shipment_pk`
    PRIMARY KEY (`staff_id`, `product_id`, `shipment_date`),
    CONSTRAINT `shipment_staff_fk`
    FOREIGN KEY (`staff_id`) REFERENCES `Staff`(`id_staff`) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `shipment_products_fk`
    FOREIGN KEY (`product_id`) REFERENCES `Products`(`id_product`) ON UPDATE CASCADE ON DELETE RESTRICT
);

DROP FUNCTION IF EXISTS `check_count`;
DELIMITER $$
	CREATE FUNCTION `check_count`(item_id INT, count INT) RETURNS BOOLEAN
    READS SQL DATA
    BEGIN
		SELECT P.`count` INTO @current_count FROM `Products` P WHERE P.`id_product` = item_id;
        IF @current_count - count < 0 THEN
			RETURN FALSE;
		ELSE
			RETURN TRUE;
		END IF;
    END;
$$
DELIMITER ;

DROP FUNCTION IF EXISTS `make_delivery`;
DELIMITER $$
	CREATE FUNCTION `make_delivery`(staff INT, product INT, provider_title VARCHAR(45), product_count INT) RETURNS INT
    DETERMINISTIC
    BEGIN
		INSERT INTO `Delivery`(`staff_id`, `product_id`, `delivery_date`, `provider`, `count`) 
        VALUES (staff, product, now(), provider_title, product_count);
		RETURN 0;
    END;
$$
DELIMITER ;

DROP FUNCTION IF EXISTS `make_shipment`;
DELIMITER $$
	CREATE FUNCTION `make_shipment`(staff INT, product INT, provider_title VARCHAR(45), product_count INT) RETURNS INT
    DETERMINISTIC
    BEGIN
		INSERT INTO `Shipment`(`staff_id`, `product_id`, `shipment_date`, `provider`, `count`) 
        VALUES (staff, product, now(), provider_title, product_count);
		RETURN 0;
    END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `insert_delivery`;
DELIMITER $$
	CREATE TRIGGER `insert_delivery` 
        BEFORE INSERT ON `Delivery`
        FOR EACH ROW
	BEGIN
		IF NEW.`count` <= 0
        THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "count can not be less then 0";
        END IF;
        UPDATE `Products` P SET P.`count` = P.`count` + NEW.`count` WHERE NEW.`product_id` = P.`id_product`;
	END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `update_delivery_prevent`;
DELIMITER $$
	CREATE TRIGGER `update_delivery_prevent` 
        BEFORE UPDATE ON `Delivery`
        FOR EACH ROW
	BEGIN
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Data can not be updated";
	END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `delete_delivery_prevent`;
DELIMITER $$
	CREATE TRIGGER `delete_delivery_prevent` 
        BEFORE DELETE ON `Delivery`
        FOR EACH ROW
	BEGIN
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Data can not be deleted";
	END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `insert_shipment`;
DELIMITER $$
	CREATE TRIGGER `insert_shipment` 
        BEFORE INSERT ON `Shipment`
        FOR EACH ROW
	BEGIN
		IF NEW.`count` <= 0
        THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "count can not be less then 0";
        END IF;
        
        IF NOT check_count(NEW.`product_id`, NEW.`count`)
        THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Not enough products of this type";
        END IF;
        UPDATE `Products` P SET P.`count` = P.`count` - NEW.`count` WHERE NEW.`product_id` = P.`id_product`;
	END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `update_shipment_prevent`;
DELIMITER $$
	CREATE TRIGGER `update_shipment_prevent` 
        BEFORE UPDATE ON `Shipment`
        FOR EACH ROW
	BEGIN
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Data can not be updated";
	END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `delete_shipment_prevent`;
DELIMITER $$
	CREATE TRIGGER `delete_shipment_prevent` 
        BEFORE DELETE ON `Shipment`
        FOR EACH ROW
	BEGIN
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Data can not be deleted";
	END;
$$
DELIMITER ;

DROP PROCEDURE IF EXISTS `get_product_list`;
DELIMITER $$
	CREATE PROCEDURE `get_product_list`()
	BEGIN
		SELECT `id_product`, `title`, `count` FROM `Products`;
	END;
$$
DELIMITER ;

DROP PROCEDURE IF EXISTS `get_delivery_statistic`;
DELIMITER $$
	CREATE PROCEDURE `get_delivery_statistic`()
	BEGIN
		SELECT P.`title` AS `Название`, count(P.`title`) AS `Количество`
		FROM `Delivery` D 
		RIGHT JOIN `Products` P 
		ON D.`product_id` = P.`id_product` 
		LEFT JOIN `Staff` S 
		ON D.`staff_id` = S.`id_staff` 
		GROUP BY P.`title`;
	END;
$$ 
DELIMITER ;

DROP PROCEDURE IF EXISTS `get_shipment_statistic`;
DELIMITER $$
	CREATE PROCEDURE `get_shipment_statistic`()
	BEGIN
		SELECT P.`title` AS `Название`, count(P.`title`) AS `Количество`
		FROM `Shipment` Sh 
		RIGHT JOIN `Products` P 
		ON Sh.`product_id` = P.`id_product` 
		LEFT JOIN `Staff` S 
		ON Sh.`staff_id` = S.`id_staff` 
		GROUP BY P.`title`;
	END;
$$
DELIMITER ;

insert into roles(role_name) values('admin');
insert into users(login, `password`, role_id) values('admin', 'admin', 1);
insert into `position`(position_name) value ('admin');
insert into staff(first_name,last_name,position_id,user_id, address) values('admin', 'admin', 1, 1, "change later");
