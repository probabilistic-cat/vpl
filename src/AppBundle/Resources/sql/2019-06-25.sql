CREATE TABLE IF NOT EXISTS `misc` (
    `id` INT(11) UNSIGNED NOT NULL,
    `design_name` VARCHAR(255) NOT NULL,
    `design_description` TEXT NULL DEFAULT NULL,
    `design_img` TEXT NULL DEFAULT NULL,
    `categories_name` VARCHAR(255) NOT NULL,
    `categories_description` TEXT NULL DEFAULT NULL,
    `contact_address` VARCHAR(255) NULL DEFAULT NULL,
    `contact_map_src` TEXT NULL DEFAULT NULL,
    `modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
)
ENGINE=`InnoDB`
CHARACTER SET `utf8mb4`
COLLATE `utf8mb4_unicode_ci`;


ALTER TABLE `main_page`
    DROP COLUMN `address`,
    DROP COLUMN `map_src`;


START TRANSACTION;

INSERT INTO `misc` (`id`, `design_name`, `design_description`, `design_img`, `categories_name`,
`categories_description`, `contact_address`, `contact_map_src`) VALUES
(1, 'Design', '', 'assets/images/catalog/design.png', 'Produkte',
'Heute können wir Ihnen 30 verschiedene Fenster- und Türsysteme aus PVC, Aluminium und Holz anbieten. Wir erweitern unser Angebot immer weiter. Wir bieten auch Schiebesysteme und Rollläden an. Alles in höchster Qualität, dank Materialien von renommierten Lieferanten aus ganz Europa.',
'VPL GmbH<br>Alexander-Bell-Straße,12<br>53332, Bornheim',
'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13440.382552498673!2d7.019484555640566!3d50.758649166782384!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47bf1fb548317fc9%3A0x3a66a3ed4b2fda52!2sAlexander-Bell-Stra%C3%9Fe+12%2C+53332+Bornheim%2C+Germany!5e0!3m2!1sen!2sru!4v1554368410990!5m2!1sen!2sru');

COMMIT;