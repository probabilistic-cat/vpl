ALTER TABLE `product_property`
    DROP COLUMN `layer`;

ALTER TABLE `category_property`
    ADD COLUMN `layer` SMALLINT(5) UNSIGNED NOT NULL DEFAULT 1 AFTER `seq`;
