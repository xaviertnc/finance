<?php // Product Model

class ProductModel
{  
  
  public function getProduct($product_id)
  {
    return DB::first('products', 'WHERE id=?', [$product_id]);
  }

  
  public function updateProduct($product_id, $productData)
  {
    $ok = DB::update('products', 'WHERE id=:id', $productData, ['id' => $product_id]);
    return $ok;
  }
  
}