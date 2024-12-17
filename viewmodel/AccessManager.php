<?php

namespace ViewModel;

use Model\Identifier;

require_once ("../model/Identifier.php");

require_once("VMProduct.php");
require_once("VMProfile.php");
require_once("VMShipment.php");
require_once("VMDelivery.php");

class AccessManager
{
    private int $accessLevel;

    public function __construct(Identifier $identifier)
    {
        $this->accessLevel = $identifier->getRole()->getId();
    }

    /**
     * @return int
     */
    public function getAccessLevel(): int
    {
        return $this->accessLevel;
    }
    public function check(int $level) : bool
    {
        if($level <= $this->accessLevel){
            return true;
        }
        return false;
    }
    public function redirect(string $url, $statusCode = 303) : void
    {
        header("Location: $url", true, $statusCode);
        die();
    }
    public static function navigator()
    {
        exit();
    }
}