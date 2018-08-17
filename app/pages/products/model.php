<?php // Products Model

class ProductsModel
{  

  public function listProducts()
  {
    return DB::select('products');
  }
  
  
  public function insertProduct($productData)
  {
    $ok = DB::insertInto('products', $productData);
    return $ok ? DB::lastInsertId() : 0;
  }
  
}