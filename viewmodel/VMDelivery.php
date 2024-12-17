<?php

namespace ViewModel;

use Model\Product;
use Model\Delivery;
use Model\Staff;

require_once ("../model/Delivery.php");
require_once ("../model/Staff.php");
require_once ("../model/Product.php");

class VMDelivery
{
    public static function getDeliveries(bool $extended =  true) : int | false
    {
        $out = [];
        foreach (Delivery::findAll() as $delivery){
            $out[] = $delivery->toData($extended);
        }
        echo json_encode($out);
        return (count($out)!=0)?count($out):false;
    }
    public static function getDeliveriesByStaff(int $id, bool $extended =  true) : int | false
    {
        $out = [];
        foreach (Delivery::findByStaffId($id) as $delivery){
            $out[] = $delivery->toData($extended);
        }
        echo json_encode($out);
        return (count($out)!=0)?count($out):false;
    }
    public static function getDeliveriesByProduct(int $id, bool $extended =  true) : int | false
    {
        $out = [];
        foreach (Delivery::findByProductId($id) as $delivery){
            $out[] = $delivery->toData($extended);
        }
        echo json_encode($out);
        return (count($out)!=0)?count($out):false;
    }
    public static function addDelivery(array $data) : int
    {
        return Delivery::insert(Staff::findById($data["staffId"]), Product::findById($data["productId"]),$data["provider"], $data["count"]);
    }
}
/*
$data = [
    "staffId"=>1,
    "productId"=>2,
    "provider"=>"Aboltus",
    "count"=>1
];

foreach ($data as $key => $value){
    echo "$key => $value\n";
}
*/
#VMDelivery::addDelivery($data);