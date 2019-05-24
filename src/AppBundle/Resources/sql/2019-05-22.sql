CREATE TRIGGER `main_page_images_created` BEFORE INSERT ON `main_page_images` FOR EACH ROW
    SET NEW.`created` = NOW();



CREATE TABLE IF NOT EXISTS `manufacturer` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `img` TEXT NULL DEFAULT NULL,
    `created` TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00',
    `modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
)
ENGINE=`InnoDB`
CHARACTER SET `utf8mb4`
COLLATE `utf8mb4_unicode_ci`;

CREATE TRIGGER `manufacturer_created` BEFORE INSERT ON `manufacturer` FOR EACH ROW
    SET NEW.`created` = NOW();


CREATE TABLE IF NOT EXISTS `product_manufacturer` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `product_id` INT(11) UNSIGNED NOT NULL,
    `manufacturer_id` INT(11) UNSIGNED NOT NULL,
    `seq` SMALLINT(5) UNSIGNED NOT NULL,
    `created` TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00',
    `modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `ix__product_manuf__product_id`(`product_id` ASC),
    CONSTRAINT
        FOREIGN KEY `fk__product_manuf__product__product_id`(`product_id`)
        REFERENCES `product`(`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    INDEX `ix__product_manuf__manufacturer_id`(`manufacturer_id` ASC),
    CONSTRAINT
        FOREIGN KEY `fk__product_manuf__manuf__manufacturer_id`(`manufacturer_id`)
        REFERENCES `manufacturer`(`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
)
ENGINE=`InnoDB`
CHARACTER SET `utf8mb4`
COLLATE `utf8mb4_unicode_ci`;

CREATE TRIGGER `product_manufacturer_created` BEFORE INSERT ON `product_manufacturer` FOR EACH ROW
    SET NEW.`created` = NOW();