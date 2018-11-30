CREATE TABLE IF NOT EXISTS `product_info_middle` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `product_id` INT(11) UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `text` TEXT NULL DEFAULT NULL,
    `seq` SMALLINT(5) UNSIGNED NOT NULL,
    `is_gallery` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
    `created` TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00',
    `modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `ix__product_info_m__product_id`(`product_id` ASC),
    CONSTRAINT
        FOREIGN KEY `fk__product_info_m__product__product_id`(`product_id`)
        REFERENCES `product`(`id`)
        ON UPDATE CASCADE ON DELETE NO ACTION
)
ENGINE=`InnoDB`
CHARACTER SET `utf8mb4`
COLLATE `utf8mb4_unicode_ci`;

CREATE TRIGGER `product_info_middle_created` BEFORE INSERT ON `product_info_middle` FOR EACH ROW
    SET NEW.`created` = NOW();


CREATE TABLE IF NOT EXISTS `product_info_bottom` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `product_id` INT(11) UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `text` TEXT NULL DEFAULT NULL,
    `seq` SMALLINT(5) UNSIGNED NOT NULL,
    `is_gallery` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
    `created` TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00',
    `modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `ix__product_info_b__product_id`(`product_id` ASC),
    CONSTRAINT
        FOREIGN KEY `fk__product_info_b__product__product_id`(`product_id`)
        REFERENCES `product`(`id`)
        ON UPDATE CASCADE ON DELETE NO ACTION
)
ENGINE=`InnoDB`
CHARACTER SET `utf8mb4`
COLLATE `utf8mb4_unicode_ci`;

CREATE TRIGGER `product_info_bottom_created` BEFORE INSERT ON `product_info_bottom` FOR EACH ROW
    SET NEW.`created` = NOW();


CREATE TABLE IF NOT EXISTS `product_info_middle_gallery` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `product_info_middle_id` INT(11) UNSIGNED NOT NULL,
    `img` TEXT NOT NULL,
    `seq` SMALLINT(5) UNSIGNED NOT NULL,
    `created` TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00',
    `modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `ix__product_info_m_gal__product_info_m_id`(`product_info_middle_id` ASC),
    CONSTRAINT
        FOREIGN KEY `fk__prod_inf_m_gal__prod_inf_m__prod_inf_m_id`(`product_info_middle_id`)
        REFERENCES `product_info_middle`(`id`)
        ON UPDATE CASCADE ON DELETE NO ACTION
)
ENGINE=`InnoDB`
CHARACTER SET `utf8mb4`
COLLATE `utf8mb4_unicode_ci`;

CREATE TRIGGER `product_info_middle_gallery_created` BEFORE INSERT ON `product_info_middle_gallery` FOR EACH ROW
    SET NEW.`created` = NOW();


INSERT INTO `product_info_middle` (`id`, `product_id`, `name`, `text`, `seq`, `is_gallery`)
    SELECT `id`, `product_id`, `name`, `text`, `seq`, `is_gallery`
    FROM `product_info`
    WHERE `product_info_location_code` = 'middle';

INSERT INTO `product_info_bottom` (`id`, `product_id`, `name`, `text`, `seq`, `is_gallery`)
    SELECT `id`, `product_id`, `name`, `text`, `seq`, `is_gallery`
    FROM `product_info`
    WHERE `product_info_location_code` = 'bottom';

INSERT INTO `product_info_middle_gallery` (`product_info_middle_id`, `img`, `seq`)
    SELECT pig.`product_info_id`, pig.`img`, pig.`seq`
    FROM `product_info_gallery` pig
        INNER JOIN `product_info` pi ON pig.`product_info_id` = pi.`id`
    WHERE pi.`product_info_location_code` = 'middle';



DROP TRIGGER `product_info_gallery_created`;
DROP TABLE `product_info_gallery`;

DROP TRIGGER `product_info_created`;
DROP TABLE `product_info`;

DROP TRIGGER `product_info_location_created`;
DROP TABLE `product_info_location`;
