<?php

use ViewModel\VMProduct;

require_once ("VMProduct.php");

if(!isset($_GET["mode"])){
    $_GET["mode"] = -1;
}
if (!isset($_GET["id"])){
    $_GET["id"] = -1;
}

switch ($_GET["mode"]){
    case 0:
        VMProduct::getProducts();
        break;
    case 1:
        VMProduct::getProduct($_GET["id"]);
        break;
    case 2:
        VMProduct::getProductList();
        break;
    default:
        echo json_encode(["error"=>-1]);
}
die();