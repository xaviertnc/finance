<?php // Client Model

class ClientModel
{  
  
  public function getClient($client_id)
  {
    return DB::first('clients', 'WHERE id=?', [$client_id]);
  }

  
  public function updateClient($client_id, $clientData)
  {
    $ok = DB::update('clients', 'WHERE id=:id', $clientData, ['id' => $client_id]);
    return $ok;
  }

  
  public function listBillingPeriods()
  {
    return DB::query('billing_periods')->getBy('id', 'description');
  }
  
  
  public function listInvoiceTemplates($client_id)
  {
    return DB::select('invoice_templates WHERE client_id=?', [$client_id]);
  }
  
  
  public function addInvoiceTemplate($client_id, $packageData)
  {
    $ok = DB::insertInto('invoice_templates', ['client_id' => $client_id] + $packageData);
    return $ok ? DB::lastInsertId() : 0;
  }  

  
  public function listInvoiceTemplateItems($template_id)
  {
    return DB::select('view_invoice_template_items WHERE invoice_template_id=?', [$template_id]);
  }  
}