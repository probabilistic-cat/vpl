CREATE DATABASE IF NOT EXISTS `vpl` CHARACTER SET `utf8mb4` COLLATE `utf8mb4_unicode_ci`;


CREATE TABLE IF NOT EXISTS `category` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT NULL DEFAULT NULL,
    `img` TEXT NULL DEFAULT NULL,
    `created` TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00',
    `modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
)
ENGINE=`InnoDB`
CHARACTER SET `utf8mb4`
COLLATE `utf8mb4_unicode_ci`;

CREATE TRIGGER `category_created` BEFORE INSERT ON `category` FOR EACH ROW
    SET NEW.`created` = NOW();


CREATE TABLE IF NOT EXISTS `subcategory` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `category_id` INT(11) UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT NULL DEFAULT NULL,
    `img` TEXT NULL DEFAULT NULL,
    `created` TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00',
    `modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `ix__subcategory__category_id`(`category_id` ASC),
    CONSTRAINT
        FOREIGN KEY `fk__subcategory__category__category_id`(`category_id`)
        REFERENCES `category`(`id`)
        ON UPDATE CASCADE ON DELETE NO ACTION
)
ENGINE=`InnoDB`
CHARACTER SET `utf8mb4`
COLLATE `utf8mb4_unicode_ci`;

CREATE TRIGGER `subcategory_created` BEFORE INSERT ON `subcategory` FOR EACH ROW
    SET NEW.`created` = NOW();


CREATE TABLE IF NOT EXISTS `product` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `subcategory_id` INT(11) UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT NULL DEFAULT NULL,
    `img` TEXT NULL DEFAULT NULL,
    `created` TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00',
    `modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `ix__product__subcategory_id`(`subcategory_id` ASC),
    CONSTRAINT
        FOREIGN KEY `fk__product__subcategory__subcategory_id`(`subcategory_id`)
        REFERENCES `subcategory`(`id`)
        ON UPDATE CASCADE ON DELETE NO ACTION
)
ENGINE=`InnoDB`
CHARACTER SET `utf8mb4`
COLLATE `utf8mb4_unicode_ci`;

CREATE TRIGGER `product_created` BEFORE INSERT ON `product` FOR EACH ROW
    SET NEW.`created` = NOW();


CREATE TABLE IF NOT EXISTS `property` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `created` TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00',
    `modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
)
ENGINE=`InnoDB`
CHARACTER SET `utf8mb4`
COLLATE `utf8mb4_unicode_ci`;

CREATE TRIGGER `property_created` BEFORE INSERT ON `property` FOR EACH ROW
    SET NEW.`created` = NOW();


CREATE TABLE IF NOT EXISTS `product_property` (
    `product_id` INT(11) UNSIGNED NOT NULL,
    `property_id` INT(11) UNSIGNED NOT NULL,
    `img` TEXT NOT NULL,
    `seq` INT(11) UNSIGNED NOT NULL,
    `layer` TINYINT(3) UNSIGNED NOT NULL,
    `created` TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00',
    `modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`product_id`, `property_id`),
    CONSTRAINT
        FOREIGN KEY `fk__product_property__product__product_id`(`product_id`)
        REFERENCES `product`(`id`)
        ON UPDATE CASCADE ON DELETE NO ACTION,
    CONSTRAINT
        FOREIGN KEY `fk__product_property__property__property_id`(`property_id`)
        REFERENCES `property`(`id`)
        ON UPDATE CASCADE ON DELETE NO ACTION
)
ENGINE=`InnoDB`
CHARACTER SET `utf8mb4`
COLLATE `utf8mb4_unicode_ci`;

CREATE TRIGGER `product_property_created` BEFORE INSERT ON `product_property` FOR EACH ROW
    SET NEW.`created` = NOW();


CREATE TABLE IF NOT EXISTS `product_info_location` (
    `code` VARCHAR(32) NOT NULL,
    `created` TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00',
    `modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`code`)
)
ENGINE=`InnoDB`
CHARACTER SET `utf8mb4`
COLLATE `utf8mb4_unicode_ci`;

CREATE TRIGGER `product_info_location_created` BEFORE INSERT ON `product_info_location` FOR EACH ROW
    SET NEW.`created` = NOW();


CREATE TABLE IF NOT EXISTS `product_info` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `product_id` INT(11) UNSIGNED NOT NULL,
    `product_info_location_code` VARCHAR(32) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `text` TEXT NULL DEFAULT NULL,
    `seq` INT(11) UNSIGNED NOT NULL,
    `is_gallery` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
    `created` TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00',
    `modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `ix__product_info__product_id`(`product_id` ASC),
    CONSTRAINT
        FOREIGN KEY `fk__product_info__product__product_id`(`product_id`)
        REFERENCES `product`(`id`)
        ON UPDATE CASCADE ON DELETE NO ACTION,
    INDEX `ix__prod_inf__prod_inf_loc_code`(`product_info_location_code` ASC),
    CONSTRAINT
        FOREIGN KEY `fk__prod_inf__prod_inf_loc__prod_inf_loc_code`(`product_info_location_code`)
        REFERENCES `product_info_location`(`code`)
        ON UPDATE CASCADE ON DELETE NO ACTION
)
ENGINE=`InnoDB`
CHARACTER SET `utf8mb4`
COLLATE `utf8mb4_unicode_ci`;

CREATE TRIGGER `product_info_created` BEFORE INSERT ON `product_info` FOR EACH ROW
    SET NEW.`created` = NOW();


CREATE TABLE IF NOT EXISTS `product_info_gallery` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `product_info_id` INT(11) UNSIGNED NOT NULL,
    `img` TEXT NOT NULL,
    `seq` INT(11) UNSIGNED NOT NULL,
    `created` TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00',
    `modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `ix__product_info_gallery__product_info_id`(`product_info_id` ASC),
    CONSTRAINT
        FOREIGN KEY `fk__prod_inf_gal__prod_inf__prod_inf_id`(`product_info_id`)
        REFERENCES `product_info`(`id`)
        ON UPDATE CASCADE ON DELETE NO ACTION
)
ENGINE=`InnoDB`
CHARACTER SET `utf8mb4`
COLLATE `utf8mb4_unicode_ci`;

CREATE TRIGGER `product_info_gallery_created` BEFORE INSERT ON `product_info_gallery` FOR EACH ROW
    SET NEW.`created` = NOW();

