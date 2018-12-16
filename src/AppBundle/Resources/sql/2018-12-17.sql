CREATE TABLE IF NOT EXISTS `user` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `password` CHAR(60) NOT NULL,
    `mail` VARCHAR(255) NOT NULL,
    `role` VARCHAR(255) NOT NULL,
    `active` TINYINT(1) NOT NULL DEFAULT 0,
    `created` TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00',
    `modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
)
ENGINE=`InnoDB`
CHARACTER SET `utf8mb4`
COLLATE `utf8mb4_unicode_ci`;

CREATE TRIGGER `user_created` BEFORE INSERT ON `user` FOR EACH ROW
    SET NEW.`created` = NOW();

ALTER TABLE `user`
    ADD UNIQUE `iu__user__name`(`name` ASC);

ALTER TABLE `user`
    ADD UNIQUE `iu__user__mail`(`mail` ASC);
