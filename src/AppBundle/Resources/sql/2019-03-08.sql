ALTER TABLE `product_property`
    ADD COLUMN `name` VARCHAR(255) NULL DEFAULT NULL AFTER `category_property_id`;
