<?php

use ViewModel\VMProfile;

require_once ("./VMProfile.php");

if (!isset($_POST["mode"]))
    $_POST["mode"] = -1;
if (!isset($_POST["userId"]))
    $_POST["userId"] = 0;
if (!isset($_POST["staffId"])){
    $_POST["staffId"] = 0;
}
switch ($_POST["mode"]){
    case 0:
        VMProfile::getRoles();
        break;
    case 1:
        VMProfile::getUsers();
        break;
    case 2:
        VMProfile::getUser($_POST["userId"]);
        break;
    case 3:
        VMProfile::getPositions();
        break;
    case 4:
        VMProfile::getStaff();
        break;
    case 5:
        VMProfile::getStaff(false);
        break;
    case 6:
        VMProfile::getStaffMember($_POST["staffId"]);
        break;
    case 7:
        VMProfile::getStaffMember($_POST["staffId"], false);
        break;
    default:
        echo json_encode(["error"=>-1]);
}