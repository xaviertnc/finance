<?php // Suppliers Model

class SuppliersModel
{  

  public function listSuppliers()
  {
    return DB::select('suppliers');
  }
  
  
  public function insertSupplier($supplierData)
  {
    $ok = DB::insertInto('suppliers', $supplierData);
    return $ok ? DB::lastInsertId() : 0;
  }
  
}