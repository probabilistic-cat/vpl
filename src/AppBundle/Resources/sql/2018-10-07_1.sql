ALTER TABLE `product`
    ADD COLUMN `description_full` TEXT NULL DEFAULT NULL AFTER `description`;


CREATE TABLE IF NOT EXISTS `category_property` (
    `id` INT(11) UNSIGNED NOT NULL,
    `category_id` INT(11) UNSIGNED NOT NULL,
    `property_id` INT(11) UNSIGNED NOT NULL,
    `seq` INT(11) UNSIGNED NOT NULL,
    `created` TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00',
    `modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `ix__category_property__category_id`(`category_id` ASC),
    CONSTRAINT
        FOREIGN KEY `fk__category_property__category__category_id`(`category_id`)
        REFERENCES `category`(`id`)
        ON UPDATE CASCADE ON DELETE NO ACTION,
    INDEX `ix__category_property__property_id`(`property_id` ASC),
    CONSTRAINT
        FOREIGN KEY `fk__category_property__property__property_id`(`property_id`)
        REFERENCES `property`(`id`)
        ON UPDATE CASCADE ON DELETE NO ACTION
)
ENGINE=`InnoDB`
CHARACTER SET `utf8mb4`
COLLATE `utf8mb4_unicode_ci`;


CREATE TABLE IF NOT EXISTS `product_property` (
    `product_id` INT(11) UNSIGNED NOT NULL,
    `category_property_id` INT(11) UNSIGNED NOT NULL,
    `img` TEXT NOT NULL,
    `seq` INT(11) UNSIGNED NOT NULL,
    `layer` TINYINT(3) UNSIGNED NOT NULL,
    `created` TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00',
    `modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`product_id`, `category_property_id`),
    INDEX `ix__product_property__product_id`(`product_id` ASC),
    CONSTRAINT
        FOREIGN KEY `fk__product_property__product__product_id`(`product_id`)
        REFERENCES `product`(`id`)
        ON UPDATE CASCADE ON DELETE NO ACTION,
    INDEX `ix__product_property__category_property_id`(`category_property_id` ASC),
    CONSTRAINT
        FOREIGN KEY `fk__prod_prop__cat_prop__cat_prop_id`(`category_property_id`)
        REFERENCES `category_property`(`id`)
        ON UPDATE CASCADE ON DELETE NO ACTION
)
ENGINE=`InnoDB`
CHARACTER SET `utf8mb4`
COLLATE `utf8mb4_unicode_ci`;

CREATE TRIGGER `product_property_created` BEFORE INSERT ON `product_property` FOR EACH ROW
    SET NEW.`created` = NOW();


INSERT INTO `product_info_location` (`code`) VALUES ('middle'), ('bottom');
