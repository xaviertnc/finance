DROP VIEW IF EXISTS `view_chart_of_accounts`;
CREATE VIEW `view_chart_of_accounts` AS
SELECT a.id,
a.category_id,
c.description as category,
a.subcategory_id,
s.description as subcategory,
a.description
FROM chart_of_accounts a
LEFT JOIN acc_categories c on c.id = a.category_id
LEFT JOIN acc_subcategories s on s.id = a.subcategory_id;