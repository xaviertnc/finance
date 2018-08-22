<?php // Suppliers Model

class SuppliersModel
{  

  public function listSuppliers()
  {
    return DB::select('suppliers ORDER BY acc_no');
  }
  
  
  public function insertSupplier($supplierData)
  {
    $ok = DB::insertInto('suppliers', $supplierData);
    return $ok ? DB::lastInsertId() : 0;
  }
  
  
  public function listChartOfAccounts()
  {
    return DB::query('chart_of_accounts')->getBy('id', 'description');
  }
  
}