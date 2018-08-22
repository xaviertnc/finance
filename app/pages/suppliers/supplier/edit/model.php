<?php // Supplier Model

class SupplierModel
{  
  
  public function getSupplier($supplier_id)
  {
    return DB::first('suppliers', 'WHERE id=?', [$supplier_id]);
  }

  
  public function updateSupplier($supplier_id, $supplierData)
  {
    $ok = DB::update('suppliers', 'WHERE id=:id', $supplierData, ['id' => $supplier_id]);
    return $ok;
  }

  
  public function listSupplierTemplates($supplier_id)
  {
    return DB::select('order_templates WHERE supplier_id=?', [$supplier_id]);
  }
  
  
  public function addOrderTemplate($supplier_id, $templateData)
  {
    $ok = DB::insertInto('order_templates', ['supplier_id' => $supplier_id] + $templateData);
    return $ok ? DB::lastInsertId() : 0;
  }  
  
  
  public function listChartOfAccounts()
  {
    return DB::query('chart_of_accounts')->getBy('id', 'description');
  }
  
}