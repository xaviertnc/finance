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