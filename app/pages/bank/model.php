<?php // Bank Model

class BankModel
{  
  
  public function listTransactions($accShortName, $fromDate = null, $toDate = null)
  {
    return DB::select("bank_acc_$accShortName WHERE date >= ? AND date <= ?", [$fromDate, $toDate]);
  }

  
  public function listAccountNames()
  {
    return DB::query('bank_accounts')->getBy('short', 'description');
  }

  
  public function listStatuses()
  {
    return DB::query('statuses')->getBy('id', 'description');
  }
  
}