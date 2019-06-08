<?php // Entities Model

class EntitiesModel
{

  public function listEntities()
  {
    return DB::select('entities ORDER BY acc_no');
  }


  public function insertEntity($clientData)
  {
    $ok = DB::insertInto('entities', $clientData);
    return $ok ? DB::lastInsertId() : 0;
  }


  public function listEntityGroups()
  {
    return DB::query('entity_groups')->getBy('id', 'description');
  }


  public function listChartOfAccounts()
  {
    return DB::query('chart_of_accounts')->getBy('id', 'description');
  }

}