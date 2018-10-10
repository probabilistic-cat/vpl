ALTER TABLE `product`
    ADD COLUMN `seals` SMALLINT(5) UNSIGNED NOT NULL DEFAULT 1 AFTER `img`,
    ADD COLUMN `chambers` SMALLINT(5) UNSIGNED NOT NULL DEFAULT 1 AFTER `seals`;


CREATE TABLE IF NOT EXISTS `product_type` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `product_id` INT(11) UNSIGNED NOT NULL,
    `text` VARCHAR(255) NOT NULL,
    `img` TEXT NULL DEFAULT NULL,
    `created` TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00',
    `modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `ix__product_type__product_id`(`product_id` ASC),
    CONSTRAINT
        FOREIGN KEY `fk__product_type__product__product_id`(`product_id`)
        REFERENCES `product`(`id`)
        ON UPDATE CASCADE ON DELETE NO ACTION
)
ENGINE=`InnoDB`
CHARACTER SET `utf8mb4`
COLLATE `utf8mb4_unicode_ci`;

CREATE TRIGGER `product_type_created` BEFORE INSERT ON `product_type` FOR EACH ROW
    SET NEW.`created` = NOW();

ALTER TABLE `product_type`
    ADD COLUMN `seq` SMALLINT(5) UNSIGNED NOT NULL AFTER `img`;

ALTER TABLE `product_property`
    MODIFY COLUMN `seq` SMALLINT(5) UNSIGNED NOT NULL;

ALTER TABLE `product_info`
    MODIFY COLUMN `seq` SMALLINT(5) UNSIGNED NOT NULL;

ALTER TABLE `product_info_gallery`
    MODIFY COLUMN `seq` SMALLINT(5) UNSIGNED NOT NULL;

ALTER TABLE `category_property`
    MODIFY COLUMN `seq` SMALLINT(5) UNSIGNED NOT NULL;
