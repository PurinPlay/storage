<?php

namespace Model;

require_once('Connector.php');

class Position
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
    public static function findById(int $id) : Position
    {
        $connect = new Connector();
        $row = $connect->select("select * from position where id_position = $id");
        $connect->close();
        if(!$row){
            return false;
        }
        $row = $row[0];
        return new Position($row["id_position"], $row["position_name"]);
    }
    /**
     * @return Position[]
     */
    public static function findAll() : array
    {
        $connect = new Connector();
        $temp = $connect->select("select * from position");
        $out = [];
        foreach ($temp as $item){
            $out[] = new Position($item["id_position"], $item["position_name"]);
        }
        $connect->close();
        return $out;
    }
    public static function insert(string $name) : void
    {
        $connect = new Connector();
        $connect->query("insert into position(position_name) values('$name')");
        $connect->close();
    }
    public static function update(Position $position) : void
    {
        $connect = new Connector();

        $connect->query("update position set position_name='$position->name' where id_position = $position->id");
        $connect->close();
    }
    public static function delete(int $id) : void
    {
        $connect = new Connector();
        $connect->query("delete from position where id_position = $id");
        $connect->close();
    }
}
