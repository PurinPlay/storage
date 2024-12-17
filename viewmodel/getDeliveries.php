<?php

use ViewModel\VMDelivery;

require_once("VMDelivery.php");

if (!isset($_GET["mode"]))
    $_GET["mode"] = -1;
if (!isset($_GET["staffId"]))
    $_GET["staffId"] = 0;
if (!isset($_GET["productId"])){
    $_GET["productId"] = 0;
}
switch ($_GET["mode"]){
    case 0:
        VMDelivery::getDeliveries();
        break;
    case 1:
        VMDelivery::getDeliveries(false);
        break;
    case 2:
        VMDelivery::getDeliveriesByStaff($_GET["staffId"]);
        break;
    case 3:
        VMDelivery::getDeliveriesByStaff($_GET["staffId"], false);
        break;
    case 4:
        VMDelivery::getDeliveriesByProduct($_GET["productId"]);
        break;
    case 5:
        VMDelivery::getDeliveriesByProduct($_GET["productId"], false);
        break;
    default:
        echo json_encode(["error"=>-1]);
}
die();
