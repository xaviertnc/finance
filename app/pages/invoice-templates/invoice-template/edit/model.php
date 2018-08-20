<?php // InvoiceTemplate Model

class InvoiceTemplateModel
{  
  
  public function getInvoiceTemplate($template_id)
  {
    return DB::first('invoice_templates', 'WHERE id=?', [$template_id]);
  }

  
  public function updateInvoiceTemplate($template_id, $templateData)
  {
    if (empty($templateData['vat_invoice'])) { $templateData['vat_invoice'] = null; }
    $ok = DB::update('invoice_templates', 'WHERE id=:id', $templateData, ['id' => $template_id]);
    return $ok;
  }

  
  public function listProducts()
  {
    return DB::query('products')->getBy('id', 'name');
  }

  
  public function listBillingPeriods()
  {
    return DB::query('billing_periods')->getBy('id', 'description');
  }

  
  public function listInvoiceTemplateItems($template_id)
  {
    return DB::select('view_invoice_template_items WHERE invoice_template_id=?', [$template_id]);
  }
  
  
  public function addInvoiceTemplateItem($template_id, $packageItemData)
  {
    $ok = DB::insertInto('invoice_template_items', ['invoice_template_id' => $template_id] + $packageItemData);
    return $ok ? DB::lastInsertId() : 0;
  }  
  
}