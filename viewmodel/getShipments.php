<?php

use ViewModel\VMShipment;

require_once("./VMShipment.php");

if (!isset($_GET["mode"]))
    $_GET["mode"] = -1;
if (!isset($_GET["staffId"]))
    $_GET["staffId"] = 0;
if (!isset($_GET["productId"])){
    $_GET["productId"] = 0;
}
switch ($_GET["mode"]){
    case 0:
        VMShipment::getShipments();
        break;
    case 1:
        VMShipment::getShipments(false);
        break;
    case 2:
        VMShipment::getShipmentsByStaff($_GET["staffId"]);
        break;
    case 3:
        VMShipment::getShipmentsByStaff($_GET["staffId"], false);
        break;
    case 4:
        VMShipment::getShipmentsByProduct($_GET["productId"]);
        break;
    case 5:
        VMShipment::getShipmentsByProduct($_GET["productId"], false);
        break;
    default:
      echo json_encode(["error"=>-1]);
}
die();


