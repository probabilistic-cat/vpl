CREATE TABLE IF NOT EXISTS `main_page_images` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `img` TEXT NULL DEFAULT NULL,
    `header` VARCHAR(255) NULL DEFAULT NULL,
    `text` TEXT NULL DEFAULT NULL,
    `seq` SMALLINT(5) UNSIGNED NOT NULL,
    `created` TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00',
    `modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
)
ENGINE=`InnoDB`
CHARACTER SET `utf8mb4`
COLLATE `utf8mb4_unicode_ci`;


CREATE TABLE IF NOT EXISTS `main_page` (
    `id` INT(11) UNSIGNED NOT NULL,
    `phone` VARCHAR(32) NULL DEFAULT NULL,
    `mail` VARCHAR(255) NULL DEFAULT NULL,
    `facebook` VARCHAR(255) NULL DEFAULT NULL,
    `copyright` VARCHAR(255) NULL DEFAULT NULL,
    `second_line_1` INT(11) UNSIGNED NULL DEFAULT NULL,
    `second_line_2_img` TEXT NULL DEFAULT NULL,
    `second_line_3_header` VARCHAR(255) NULL DEFAULT NULL,
    `second_line_3_text` TEXT NULL DEFAULT NULL,
    `third_line_1` INT(11) UNSIGNED NULL DEFAULT NULL,
    `fourth_line_1_header` VARCHAR(255) NULL DEFAULT NULL,
    `fourth_line_1_text` TEXT NULL DEFAULT NULL,
    `fourth_line_2_img` TEXT NULL DEFAULT NULL,
    `fourth_line_2_header` VARCHAR(255) NULL DEFAULT NULL,
    `fourth_line_2_text` TEXT NULL DEFAULT NULL,
    `fourth_line_3_img` TEXT NULL DEFAULT NULL,
    `fourth_line_3_header` VARCHAR(255) NULL DEFAULT NULL,
    `fourth_line_3_text` TEXT NULL DEFAULT NULL,
    `modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `ix__main_page__second_line_1`(`second_line_1` ASC),
    CONSTRAINT
        FOREIGN KEY `fk__main_page__product__second_line_1`(`second_line_1`)
        REFERENCES `product`(`id`)
        ON UPDATE CASCADE ON DELETE NO ACTION,
    INDEX `ix__main_page__third_line_1`(`third_line_1` ASC),
    CONSTRAINT
        FOREIGN KEY `fk__main_page__product__third_line_1`(`third_line_1`)
        REFERENCES `product`(`id`)
        ON UPDATE CASCADE ON DELETE NO ACTION
)
ENGINE=`InnoDB`
CHARACTER SET `utf8mb4`
COLLATE `utf8mb4_unicode_ci`;


INSERT INTO `main_page` (`id`, `phone`, `mail`, `facebook`, `copyright`, `second_line_1`, `second_line_2_img`, `second_line_3_header`, `second_line_3_text`, `third_line_1`, `fourth_line_1_header`, `fourth_line_1_text`, `fourth_line_2_img`, `fourth_line_2_header`, `fourth_line_2_text`, `fourth_line_3_img`, `fourth_line_3_header`, `fourth_line_3_text`) VALUES
(1,
'+88 159 85 9253',
'vpl-corporation@vpl-bau.de',
'https://www.facebook.com/vpl.gmbh',
'© 2018. VPL GmbH',
15,
'img/main_page/second_line_2_img.jpg',
'Minimalism',
'<p>The elegant, clean-cut flat roof is a perfect fit for the minimalist architecture of contemporary living concepts. On request, the flat roof can be combined with a glass dome or an inset glass flat roof to let in even more light.</p><p>The VPL® thus achieves outstanding energy values at the passive house level. Along with low energy use, this also provides a pleasant living atmosphere at all times of the year.</p>',
16,
'Zusätze',
'<p>Ein wesentlicher Bestandteil der Alufenster, der Funktionalität und Ästhetik beeinflusst, sind zweifellos Zusätze.</p><p>Wir bieten ein breites Spektrum von Zusätzen, u. a. Fenstergriffe, Handgriffe, Oberlichtschließer an.</p>',
'img/main_page/fourth_line_2_img.jpg',
'lackierte Fenstergriffe',
'Um die Optik des Fensters noch vorteilhafter erscheinen zu lassen, bieten wir Ihnen lackierte Fenstergriffe an.', 'img/main_page/fourth_line_3_img.jpg',
'Verbund-Sicherheitsglas',
'Die sichere Benutzung wird durch Verbund-Sicherheitsglas (VSG) gewährleistet.');


INSERT INTO `main_page_images` (`img`, `header`, `text`, `seq`) VALUES
('img/main_page/first_line_1_img_1.jpg',
null,
'VPL uses Schüco-brand top-quality thermally isolated aluminium profile systems for its windows and doors. The high-quality window and door elements impress with their elegant details in a slimline, beautiful look.',
1),
('img/main_page/first_line_1_img_2.jpg',
'Some other lead',
'List key advantages of your product here',
2);
