<?php

namespace ViewModel;

use Model\Product;

require_once ("../model/Product.php");

final class VMProduct
{
    public static function getProductList() : int | false
    {
        $out = [];
        foreach (Product::findAll() as $item){
            $out[] = [
                "id" => $item->getId(),
                "title" => $item->getTitle()
            ];
        }
        echo json_encode($out);
        return (count($out)!=0)?count($out):false;
    }
    public static function getProducts() : int | false
    {
        $out = [];
        foreach (Product::findAll() as $product)
        {
            $out[] = $product->toData();
        }
        echo json_encode($out);
        return (count($out)!=0)?count($out):false;
    }
    public static function getProduct(int $id) : Product | false
    {
        $product = Product::findById($id);
        if ($product){
            $out = $product->toData();
            echo json_encode($out);
        }
        return $product;
    }
    #maybe some day I will fix it, now it's 22:20... Also I hate web programming because it's ####### boring!
    public function addProduct(array $data) : Product | bool
    {
        Product::insert($data["title"], $data["shelf"], $data["row"], $data["column"]);
        return true;
    }
    public static function changeProduct(Product $product) : bool
    {
        if (!Product::findById($product->getId())){
            return false;
        }
        Product::update($product);
        return true;
    }
}