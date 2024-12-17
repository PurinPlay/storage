<?php

namespace Model;

require_once("Connector.php");
require_once("Role.php");

class User
{
    private int $id;
    private string $login;
    private string $password;
    private Role $role;

    public function __construct(int $id, string $login, string $password, Role $role)
    {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
        $this->role = $role;
    }

    /**
     * @return int
     */

    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLogin() : string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return Role
     */
    public function getRole(): Role
    {
        return $this->role;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    /**
     * @param string $login
     */
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @param Role $role
     */
    public function setRole(Role $role): void
    {
        $this->role = $role;
    }

    public function toData() : array
    {
        return [
            "id" => $this->getId(),
            "login" => $this->getLogin(),
            "password" => $this->getPassword(),
            "role" => $this->getRole()->toData()
        ];
    }
    public static function dataToUser(array $data) : User
    {
        return new User($data["dataToUser"], $data["login"], $data["password"], Role::findById($data["roleId"]));
    }
    public static function findById(int $id) : User | false
    {
        $connect = new Connector();
        $row = $connect->select("select * from users where id_user = $id");
        $connect->close();
        if(!$row){
            return false;
        }
        $row = $row[0];
        return new User($row["id_user"], $row["login"], $row["password"], Role::findById($row["role_id"]));
    }
    /**
     * @return User[]
     */
    public static function findAll() : array
    {
        $connect = new Connector();
        $temp = $connect->select("select * from users");
        $out = [];
        foreach ($temp as $item){
            $out[] = new User($item["id_user"], $item["login"], $item["password"], Role::findById($item["role_id"]));
        }
        $connect->close();
        return $out;
    }
    public static function insert(string $login, string $password, Role $role) : void
    {
        $connect = new Connector();
        $role_id = $role->getId();
        $connect->query("insert into users(login, `password`, role_id) values ('$login','$password',$role_id)");
        $connect->close();
    }
    public static function update(User $user) : void
    {
        $connect = new Connector();
        $role_id = $user->role->getId();
        $connect->query("update users set login='$user->login', `password`='$user->password', role_id = $role_id where id_user = 1");
        $connect->close();
    }
    public static function delete(int $id) : void
    {
        $connect = new Connector();
        $connect->query("delete from users where id_user = $id");
        $connect->close();
    }
}