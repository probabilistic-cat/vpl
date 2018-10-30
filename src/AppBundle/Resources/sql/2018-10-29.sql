ALTER TABLE `subcategory`
    DROP FOREIGN KEY `fk__subcategory__category__category_id`;

ALTER TABLE `subcategory`
    ADD CONSTRAINT
        FOREIGN KEY `fk__subcategory__category__category_id`(`category_id`)
        REFERENCES `category`(`id`)
        ON UPDATE CASCADE ON DELETE CASCADE;


ALTER TABLE `product`
    DROP FOREIGN KEY `fk__product__subcategory__subcategory_id`;

ALTER TABLE `product`
    ADD CONSTRAINT
        FOREIGN KEY `fk__product__subcategory__subcategory_id`(`subcategory_id`)
        REFERENCES `subcategory`(`id`)
        ON UPDATE CASCADE ON DELETE CASCADE;


ALTER TABLE `product_info`
    DROP FOREIGN KEY `fk__product_info__product__product_id`,
    DROP FOREIGN KEY `fk__prod_inf__prod_inf_loc__prod_inf_loc_code`;

ALTER TABLE `product_info`
    ADD CONSTRAINT
        FOREIGN KEY `fk__product_info__product__product_id`(`product_id`)
        REFERENCES `product`(`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    ADD CONSTRAINT
        FOREIGN KEY `fk__prod_inf__prod_inf_loc__prod_inf_loc_code`(`product_info_location_code`)
        REFERENCES `product_info_location`(`code`)
        ON UPDATE CASCADE ON DELETE CASCADE;


ALTER TABLE `product_info_gallery`
    DROP FOREIGN KEY `fk__prod_inf_gal__prod_inf__prod_inf_id`;

ALTER TABLE `product_info_gallery`
    ADD CONSTRAINT
        FOREIGN KEY `fk__prod_inf_gal__prod_inf__prod_inf_id`(`product_info_id`)
        REFERENCES `product_info`(`id`)
        ON UPDATE CASCADE ON DELETE CASCADE;


ALTER TABLE `category_property`
    DROP FOREIGN KEY `fk__category_property__category__category_id`,
    DROP FOREIGN KEY `fk__category_property__property__property_id`;

ALTER TABLE `category_property`
    ADD CONSTRAINT
        FOREIGN KEY `fk__category_property__category__category_id`(`category_id`)
        REFERENCES `category`(`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    ADD CONSTRAINT
        FOREIGN KEY `fk__category_property__property__property_id`(`property_id`)
        REFERENCES `property`(`id`)
        ON UPDATE CASCADE ON DELETE CASCADE;


ALTER TABLE `product_property`
    DROP FOREIGN KEY `fk__product_property__product__product_id`,
    DROP FOREIGN KEY `fk__prod_prop__cat_prop__cat_prop_id`;

ALTER TABLE `product_property`
    ADD CONSTRAINT
        FOREIGN KEY `fk__product_property__product__product_id`(`product_id`)
        REFERENCES `product`(`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    ADD CONSTRAINT
        FOREIGN KEY `fk__prod_prop__cat_prop__cat_prop_id`(`category_property_id`)
        REFERENCES `category_property`(`id`)
        ON UPDATE CASCADE ON DELETE CASCADE;


ALTER TABLE `product_type`
    DROP FOREIGN KEY `fk__product_type__product__product_id`;

ALTER TABLE `product_type`
    ADD CONSTRAINT
        FOREIGN KEY `fk__product_type__product__product_id`(`product_id`)
        REFERENCES `product`(`id`)
        ON UPDATE CASCADE ON DELETE CASCADE;
