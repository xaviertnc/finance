DROP VIEW IF EXISTS `view_acc_subcategories`;
CREATE VIEW `view_acc_subcategories` AS
SELECT s.id,
s.category_id,
c.description as category,
s.description
FROM acc_subcategories s
LEFT JOIN acc_categories c on c.id = s.category_id;