<?php // Client Package Model

class ClientPackageModel
{  
  
  public function getPackage($package_id)
  {
    return DB::first('client_packages', 'WHERE id=?', [$package_id]);
  }

  
  public function updatePackage($package_id, $packageData)
  {
    $ok = DB::update('client_packages', 'WHERE id=:id', $packageData, ['id' => $package_id]);
    return $ok;
  }

  
  public function listPackageItems($package_id)
  {
    return DB::select('view_client_pkg_items WHERE client_package_id=?', [$package_id]);
  }
  
  
  public function addPackage($package_id, $packageData)
  {
    $ok = DB::insertInto('client_packages', ['client_package_id' => $package_id] + $packageData);
    return $ok ? DB::lastInsertId() : 0;
  }  
  
}