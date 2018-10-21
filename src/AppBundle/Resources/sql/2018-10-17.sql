ALTER TABLE `product_property`
    DROP FOREIGN KEY `fk__prod_prop__cat_prop__cat_prop_id`;


ALTER TABLE `category_property`
    MODIFY COLUMN `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    ADD COLUMN `active` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 AFTER `seq`;


ALTER TABLE `product_property`
    ADD CONSTRAINT
        FOREIGN KEY `fk__prod_prop__cat_prop__cat_prop_id`(`category_property_id`)
        REFERENCES `category_property`(`id`)
        ON UPDATE CASCADE ON DELETE NO ACTION;


ALTER TABLE `product_property`
    DROP PRIMARY KEY,
    ADD COLUMN `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT FIRST,
    ADD PRIMARY KEY (`id`);


INSERT INTO `property` (`id`, `name`) VALUES
(1, 'Beschreibung'), (2, 'Farbepalette'), (3, 'Model'), (4, 'Farbe'), (5, 'Glas'), (6, 'Griff');

INSERT INTO `category_property` (`id`, `category_id`, `property_id`, `seq`, `active`) VALUES
(1, 1, 1, 1, 1), (2, 1, 2, 2, 1),
(3, 2, 1, 1, 1), (4, 2, 3, 2, 1), (5, 2, 4, 3, 1), (6, 2, 5, 4, 1), (7, 2, 6, 5, 1),
(8, 3, 1, 1, 1), (9, 3, 2, 2, 1),
(10, 4, 1, 1, 1), (11, 4, 3, 2, 1), (12, 4, 4, 3, 1), (13, 4, 5, 4, 1), (14, 4, 6, 5, 1);
