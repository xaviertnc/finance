DROP VIEW IF EXISTS `view_supplier_pkg_items`;
CREATE VIEW `view_supplier_pkg_items` AS
SELECT items.*, prod.code, prod.description
FROM supplier_package_items items
LEFT JOIN products prod on prod.id = items.product_id;