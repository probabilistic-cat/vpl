START TRANSACTION;


INSERT INTO `category` (`id`, `name`) VALUES (1, 'Fenster'), (2, 'Türen'), (3, 'Schiebefenster'), (4, 'Rollläden');

INSERT INTO `subcategory` (`category_id`, `name`) VALUES
(1, 'PVC-Fenster'), (1, 'Aluminium-Fenster'), (1, 'Holzfenster'), (1, 'Fensterläden'), (1, 'Zubehör Fenster'),
(2, 'PVC-Tür'), (2, 'Aluminium-Tür'), (2, 'Holztür'), (2, 'Zubehör Türen'),
(3, 'PVC-Fenster'), (3, 'Aluminium-Fenster'), (3, 'Zubehör'),
(4, 'Einstellbare Rollläden'), (4, 'Aufsatzrollläden'), (4, 'Unterputzrollläden'),
(4, 'Screen'), (4, 'Fassadenjalousien'), (4, 'Zubehör Rollläden');


COMMIT;
