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


  public function listCategories()
  {
    return DB::query('prod_categories')->getBy('id', 'name');
  }


  public function listSubCategories()
  {
    return DB::query('prod_subcategories')->getBy('id', 'name');
  }
  

  public function listSuppliers()
  {
    return DB::query('suppliers')->getBy('id', 'name');
  }
}