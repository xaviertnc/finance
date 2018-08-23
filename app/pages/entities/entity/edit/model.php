<?php // Entity Model

class EntityModel
{  
  
  public function getEntity($entity_id)
  {
    return DB::first('entities', 'WHERE id=?', [$entity_id]);
  }

  
  public function updateEntity($entity_id, $entityData)
  {
    $ok = DB::update('entities', 'WHERE id=:id', $entityData, ['id' => $entity_id]);
    return $ok;
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