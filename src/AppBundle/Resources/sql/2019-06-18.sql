ALTER TABLE `main_page`
    ADD COLUMN `address` VARCHAR(255) NULL DEFAULT NULL AFTER `mail`,
    ADD COLUMN `map_src` TEXT NULL DEFAULT NULL AFTER `copyright`;


START TRANSACTION;

UPDATE `main_page`
SET `address` = 'VPL GmbH<br>Alexander-Bell-Straße,12<br>53332, Bornheim',
    `map_src` = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13440.382552498673!2d7.019484555640566!3d50.758649166782384!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47bf1fb548317fc9%3A0x3a66a3ed4b2fda52!2sAlexander-Bell-Stra%C3%9Fe+12%2C+53332+Bornheim%2C+Germany!5e0!3m2!1sen!2sru!4v1554368410990!5m2!1sen!2sru'
WHERE `id` = 1;

COMMIT;