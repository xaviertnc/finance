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

  
  public function listSupplierPackages($supplier_id)
  {
    return DB::select('supplier_packages WHERE supplier_id=?', [$supplier_id]);
  }
  
  
  public function addPackage($supplier_id, $packageData)
  {
    $ok = DB::insertInto('supplier_packages', ['supplier_id' => $supplier_id] + $packageData);
    return $ok ? DB::lastInsertId() : 0;
  }  
  
}