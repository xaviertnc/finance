<?php // Supplier Package Model

class SupplierPackageModel
{

  public function getPackage($package_id)
  {
    return DB::first('supplier_packages', 'WHERE id=?', [$package_id]);
  }


  public function updatePackage($package_id, $packageData)
  {
    $ok = DB::update('supplier_packages', 'WHERE id=:id', $packageData, ['id' => $package_id]);
    return $ok;
  }


  public function listPackageItems($package_id)
  {
    return DB::select('view_supplier_pkg_items WHERE supplier_package_id=?', [$package_id]);
  }


  public function addPackage($package_id, $packageData)
  {
    $ok = DB::insertInto('supplier_packages', ['supplier_package_id' => $package_id] + $packageData);
    return $ok ? DB::lastInsertId() : 0;
  }

}