<?php // Clients Model

class ClientsModel
{  

  public function listClients()
  {
    return DB::select('clients');
  }
  
  
  public function insertClient($clientData)
  {
    $ok = DB::insertInto('clients', $clientData);
    return $ok ? DB::lastInsertId() : 0;
  }
  
}