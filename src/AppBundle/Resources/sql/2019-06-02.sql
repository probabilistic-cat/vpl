CREATE TABLE IF NOT EXISTS `property_set` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `property_id` INT(11) UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `created` TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00',
    `modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `ix__property_set__property_id`(`property_id` ASC),
    CONSTRAINT
        FOREIGN KEY `fk__property_set__property_id`(`property_id`)
        REFERENCES `property`(`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
)
ENGINE=`InnoDB`
CHARACTER SET `utf8mb4`
COLLATE `utf8mb4_unicode_ci`;

CREATE TRIGGER `property_set_created` BEFORE INSERT ON `property_set` FOR EACH ROW
    SET NEW.`created` = NOW();


CREATE TABLE IF NOT EXISTS `property_item` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `property_set_id` INT(11) UNSIGNED NULL DEFAULT NULL,
    `name` VARCHAR(255) NULL DEFAULT NULL,
    `img` TEXT NOT NULL,
    `seq` SMALLINT(5) UNSIGNED NOT NULL,
    `created` TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00',
    `modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `ix__property_item__property_set_id`(`property_set_id` ASC),
    CONSTRAINT
        FOREIGN KEY `fk__property_item__property_set_id`(`property_set_id`)
        REFERENCES `property_set`(`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
)
ENGINE=`InnoDB`
CHARACTER SET `utf8mb4`
COLLATE `utf8mb4_unicode_ci`;

CREATE TRIGGER `property_item_created` BEFORE INSERT ON `property_item` FOR EACH ROW
    SET NEW.`created` = NOW();


ALTER TABLE `product_property`
    MODIFY COLUMN `img` TEXT NULL DEFAULT NULL,
    ADD COLUMN `property_set_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `category_property_id`,
    ADD INDEX `ix__prod_prop_set__property_set_id`(`property_set_id` ASC),
    ADD CONSTRAINT
        FOREIGN KEY `fk__prod_prop_set__property_set_id`(`property_set_id`)
        REFERENCES `property_set`(`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
