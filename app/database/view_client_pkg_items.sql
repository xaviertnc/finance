DROP VIEW IF EXISTS `view_client_pkg_items`;
CREATE VIEW `view_client_pkg_items` AS
SELECT items.*, prod.code, prod.description, supl.name
FROM client_package_items items
LEFT JOIN products prod on prod.id = items.product_id
LEFT JOIN suppliers supl on supl.id = prod.supplier_id;