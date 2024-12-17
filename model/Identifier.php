<?php

namespace Model;

require_once ("Connector.php");
require_once ("User.php");

class Identifier
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    public function getLogin() : string
    {
        return $this->getLogin();
    }
    public function getRole() : Role
    {
        return $this->getRole();
    }
    public static function login(string $login, string $password) : false| Identifier
    {
        $connection = new Connector();
        $id = @$connection->select("select id_user from users where login='$login' and `password`='$password'")[0]["id_user"];
        $connection->close();
        if($id){
            return new Identifier(User::findById($id));
        }else{
            return false;
        }
    }
}
/**
$ID = Identifier::login("admin", "1234");
if($ID===false){
    echo "no no no. NO";
}else{
    echo "yes yes yes. YES";
}
 */
