DROP VIEW IF EXISTS `view_invoice_template_items`;
CREATE VIEW `view_invoice_template_items` AS
SELECT items.*, 
prod.code,
prod.name,
prod.description, 
prod.supplier_id,
prod.cost_price,
prod.default_price,
supl.name as supplier
FROM invoice_template_items items
LEFT JOIN products prod on prod.id = items.product_id
LEFT JOIN suppliers supl on supl.id = prod.supplier_id;