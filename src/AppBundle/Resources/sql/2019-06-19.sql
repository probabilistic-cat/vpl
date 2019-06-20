CREATE TABLE IF NOT EXISTS `style` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `seq` SMALLINT(5) UNSIGNED NOT NULL,
    `created` TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00',
    `modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
)
ENGINE=`InnoDB`
CHARACTER SET `utf8mb4`
COLLATE `utf8mb4_unicode_ci`;

CREATE TRIGGER `style_created` BEFORE INSERT ON `style` FOR EACH ROW
    SET NEW.`created` = NOW();


CREATE TABLE IF NOT EXISTS `style_img` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `style_id` INT(11) UNSIGNED NOT NULL,
    `img` TEXT NULL DEFAULT NULL,
    `img_color` TEXT NULL DEFAULT NULL,
    `seq` SMALLINT(5) UNSIGNED NOT NULL,
    `created` TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00',
    `modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `ix__style_img__style_id`(`style_id` ASC),
    CONSTRAINT
        FOREIGN KEY `fk__style_img__style__style_id`(`style_id`)
        REFERENCES `style`(`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
)
ENGINE=`InnoDB`
CHARACTER SET `utf8mb4`
COLLATE `utf8mb4_unicode_ci`;

CREATE TRIGGER `style_img_created` BEFORE INSERT ON `style_img` FOR EACH ROW
    SET NEW.`created` = NOW();


CREATE TABLE IF NOT EXISTS `style_info_bottom` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `style_id` INT(11) UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `text` TEXT NULL DEFAULT NULL,
    `seq` SMALLINT(5) UNSIGNED NOT NULL,
    `created` TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00',
    `modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `ix__style_info_b__style_id`(`style_id` ASC),
    CONSTRAINT
        FOREIGN KEY `fk__style_info_b__style__style_id`(`style_id`)
        REFERENCES `style`(`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
)
ENGINE=`InnoDB`
CHARACTER SET `utf8mb4`
COLLATE `utf8mb4_unicode_ci`;

CREATE TRIGGER `style_info_bottom_created` BEFORE INSERT ON `style_info_bottom` FOR EACH ROW
    SET NEW.`created` = NOW();
