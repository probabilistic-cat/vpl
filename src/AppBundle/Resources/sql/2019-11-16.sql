ALTER TABLE `product`
    ADD COLUMN `seq` SMALLINT(5) UNSIGNED NULL AFTER `chambers_name`;

UPDATE `product`
SET `seq` = 1;

ALTER TABLE `product`
    MODIFY COLUMN `seq` SMALLINT(5) UNSIGNED NOT NULL;
