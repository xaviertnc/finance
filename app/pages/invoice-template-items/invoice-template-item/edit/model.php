<?php // Invoice Template Item Model

class InvoiceItemModel
{

  public function getInvoiceItem($item_id)
  {
    return DB::first('invoice_template_items', 'WHERE id=?', [$item_id]);
  }


  public function getProduct($prod_id)
  {
    return DB::first('products', 'WHERE id=?', [$prod_id]);
  }


  public function updateInvoiceItem($item_id, $itemData)
  {
    $ok = DB::update('invoice_template_items', 'WHERE id=:id', $itemData, ['id' => $item_id]);
    return $ok;
  }


  public function listProducts()
  {
    return DB::query('products')->getBy('id', 'name');
  }

}