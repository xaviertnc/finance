DROP VIEW IF EXISTS `view_order_template_items`;
CREATE VIEW `view_order_template_items` AS
SELECT items.*, prod.code, prod.name, prod.description
FROM order_template_items items
LEFT JOIN products prod on prod.id = items.product_id;