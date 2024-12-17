<?php

namespace ViewModel;

use Model\Product;
use Model\Shipment;
use Model\Staff;

require_once ("../model/Shipment.php");
require_once ("../model/Staff.php");
require_once ("../model/Product.php");

class VMShipment
{
    public static function getShipments(bool $extended =  true) : int | false
    {
        $out = [];
        foreach (Shipment::findAll() as $shipment){
            $out[] = $shipment->toData($extended);
        }
        echo json_encode($out);
        return (count($out)!=0)?count($out):false;
    }
    public static function getShipmentsByStaff(int $id, bool $extended =  true) : int | false
    {
        $out = [];
        foreach (Shipment::findByStaffId($id) as $shipment){
            $out[] = $shipment->toData($extended);
        }
        echo json_encode($out);
        return (count($out)!=0)?count($out):false;
    }
    public static function getShipmentsByProduct(int $id, bool $extended =  true) : int | false
    {
        $out = [];
        foreach (Shipment::findByProductId($id) as $shipment){
            $out[] = $shipment->toData($extended);
        }
        echo json_encode($out);
        return (count($out)!=0)?count($out):false;
    }
    #todo NOTHING BECAUSE I AM NOT GONNA FIX IT AT 0:19 AM
    #UPD oh nvm it was easy
    public static function addShipment(array $data) : int
    {
        return Shipment::insert(Staff::findById($data["staffId"]), Product::findById($data["productId"]),$data["provider"], $data["count"]);
    }
}
#VMShipment::getShipments();
