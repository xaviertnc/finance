<?php // Invoices Model

class InvoicesModel
{

  public function listInvoices($fromDate = null, $toDate = null,
    $invNoTerm = null, $descTerm = null, $page = null, $rpp = null)
  {
    return DB::query('invoices')
      ->where('date'        , 'BETWEEN', [$fromDate, $toDate]               , ['ignore' => null])
      ->where('invoice_no'  , 'LIKE'   , $invNoTerm ? "%$invNoTerm%" : null , ['ignore' => null])
      ->where('title'       , 'LIKE'   , $descTerm  ? "%$descTerm%"  : null , ['ignore' => null])
      ->orderBy('date desc')
      ->paginate($page?:1, $rpp?:15);
  }


  public function insertInvoice($invoiceData)
  {
    $ok = DB::insertInto('invoices', $invoiceData);
    return $ok ? DB::lastInsertId() : 0;
  }

}