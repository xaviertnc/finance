<?php // Bank Model

class BankModel
{

  public function getClient($client_id)
  {
    return DB::first('clients', 'WHERE id = ?', [$client_id]);
  }

  public function getInvoice($invoice_no)
  {
    return DB::first('invoices', 'WHERE invoice_no = ?', [$invoice_no]);
  }

  public function getClientByAccNo($acc_no)
  {
    return DB::first('clients', 'WHERE acc_no = ?', [$acc_no]);
  }


  public function listTransactions($accShortName, $fromDate = null, $toDate = null)
  {
    return DB::select("bank_acc_$accShortName WHERE date >= ? AND date < ?", [$fromDate, $toDate]);
  }


  public function listClients()
  {
    return DB::query('clients')->getBy('id');
  }


  public function listSuppliers()
  {
    return DB::query('suppliers')->getBy('id');
  }


  public function listStatuses()
  {
    return DB::query('trx_statuses')->getBy('id', 'description');
  }


  public function listTransferEntities()
  {
    return DB::select('entities WHERE group_id=7');
  }


  public function listOtherEntities()
  {
    return DB::select('entities WHERE NOT group_id=7');
  }


  public function listEntities()
  {
    return DB::query('entities')->getBy('id', 'name');
  }


  public function listBankAccountNames()
  {
    return DB::query('bank_accounts')->getBy('short', 'description');
  }


  public function listAccSubCategories()
  {
    return DB::query('acc_subcategories')->getBy('id', 'description');
  }


  public function listChartOfAccounts()
  {
    return DB::query('chart_of_accounts')->getBy('id');
  }


  public function updateBankTrx($accShortName, $trx_id, $trxData)
  {
    $ok = DB::update("bank_acc_$accShortName", 'WHERE id=:id', $trxData, ['id' => $trx_id]);
    return $ok;
  }

}