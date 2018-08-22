<?php // Clients Model

class ClientsModel
{  

  public function listClients()
  {
    return DB::select('clients ORDER BY acc_no');
  }
  
  
  public function insertClient($clientData)
  {
    $ok = DB::insertInto('clients', $clientData);
    return $ok ? DB::lastInsertId() : 0;
  }
  
  
  public function listChartOfAccounts()
  {
    return DB::query('chart_of_accounts')->getBy('id', 'description');
  }
  
}