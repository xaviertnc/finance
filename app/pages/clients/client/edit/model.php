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

  
  public function listClientPackages($client_id)
  {
    return DB::select('client_packages WHERE client_id=?', [$client_id]);
  }
  
  
  public function addPackage($client_id, $packageData)
  {
    $ok = DB::insertInto('client_packages', ['client_id' => $client_id] + $packageData);
    return $ok ? DB::lastInsertId() : 0;
  }  
  
}