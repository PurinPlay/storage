<?php

namespace Model;

require_once ("Connector.php");

class Role
{
    private int $id;
    private string $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
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
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function toData() : array
    {
        return [
            "id" => $this->getId(),
            "name" => $this->getName()
        ];
    }
    public static function dataToRole(array $data) : Role
    {
        return new Role($data["id"], $data["name"]);
    }
    public static function findById(int $id) : Role
    {
        $connect = new Connector();
        $row = $connect->select("select * from roles where id_role = $id");
        $connect->close();
        if(!$row){
            return false;
        }
        $row = $row[0];
        return new Role($row["id_role"], $row["role_name"]);
    }
    /**
     * @return Role[]
     */
    public static function findAll() : array
    {
        $connect = new Connector();
        $temp = $connect->select("select * from roles");
        $out = [];
        foreach ($temp as $item){
            $out[] = new Role($item["id_role"], $item["role_name"]);
        }
        $connect->close();
        return $out;
    }
    public static function insert(string $name) : void
    {
        $connect = new Connector();
        $connect->query("insert into roles(role_name) values('$name')");
        $connect->close();
    }
    public static function update(Role $role) : void
    {
        $connect = new Connector();

        $connect->query("update roles set role_name='$role->name' where id_role = $role->id");
        $connect->close();
    }
    public static function delete(int $id) : void
    {
        $connect = new Connector();
        $connect->query("delete from roles where id_role = $id");
        $connect->close();
    }
}