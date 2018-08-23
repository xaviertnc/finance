<?php // Transaction Model

class TransactionModel
{  
  
  public function getTransaction($acc_shortname, $transaction_id)
  {
    return DB::first('bank_acc_' . $acc_shortname, 'WHERE id=?', [$transaction_id]);
  }

  
  public function updateTransaction($acc_shortname, $transaction_id, $transactionData)
  {
    $ok = DB::update('bank_acc_' . $acc_shortname, 'WHERE id=:id', $transactionData, ['id' => $transaction_id]);
    return $ok;
  }


  public function listEntityGroups()
  {
    return DB::query('entity_groups')->getBy('id', 'description');
  }
  

  public function listEntities()
  {
    return DB::query('entities')->getBy('id', 'name');
  }
  
  
  public function listChartOfAccounts()
  {
    return DB::query('chart_of_accounts')->getBy('id', 'description');
  }
  
}