<?php

namespace Model;

require_once("Connector.php");

class Product
{
    private int $id;
    private string $title;
    private int $count;
    private int $shelf;
    private int $row;
    private int $column;

    public function __construct(int $id, string $title, int $count, int $shelf, int $row, $column)
    {
        $this->id = $id;
        $this->title = $title;
        $this->count = $count;
        $this->shelf = $shelf;
        $this->row = $row;
        $this->column = $column;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @return int
     */
    public function getShelf(): int
    {
        return $this->shelf;
    }

    /**
     * @return int
     */
    public function getRow(): int
    {
        return $this->row;
    }

    /**
     * @return int
     */
    public function getColumn(): int
    {
        return $this->column;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param int $count
     */
    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    /**
     * @param int $shelf
     */
    public function setShelf(int $shelf): void
    {
        $this->shelf = $shelf;
    }

    /**
     * @param int $row
     */
    public function setRow(int $row): void
    {
        $this->row = $row;
    }

    /**
     * @param int $column
     */
    public function setColumn(int $column): void
    {
        $this->column = $column;
    }
    public function toData() : array
    {
        return [
            "id" => $this->getId(),
            "title" => $this->getTitle(),
            "count" => $this->getCount(),
            "shelf" => $this->getShelf(),
            "row" => $this->getRow(),
            "column" => $this->getColumn()
        ];
    }
    public static function dataToProduct(array $data) : Product
    {
        return new Product($data["id"], $data["title"], $data["count"], $data["shelf"], $data["row"], $data["column"]);
    }
    public static function findById(int $id) : Product | false
    {
        $connect = new Connector();
        $row = $connect->select("select * from products where id_product = $id");
        $connect->close();
        if(!$row){
            return false;
        }
        $row = $row[0];
        return new Product($row["id_product"], $row["title"], $row["count"], $row["shelf"], $row["row"], $row["column"]);
    }

    /**
     * @return Product[]
     */
    public static function findAll() : array
    {
        $connect = new Connector();
        $temp = $connect->select("select * from products");
        $out = [];
        foreach ($temp as $item){
            $out[] = new Product($item["id_product"], $item["title"], $item["count"], $item["shelf"], $item["row"], $item["column"]);
        }
        $connect->close();
        return $out;
    }
    public static function insert(string $title, int $shelf, int $row, int $column) : void
    {
        $connect = new Connector();
        $connect->query("insert into products(title, shelf, `row`, `column`) values('$title',$shelf,$row,$column)");
        $connect->close();
    }
    public static function update(Product $product) : void
    {
        $connect = new Connector();
        $connect->query("update products set title='$product->title', count=$product->count, shelf=$product->shelf, row=$product->row, column=$product->column where id_product = $product->id");
        $connect->close();
    }
    public static function delete(int $id) : void
    {
        $connect = new Connector();
        $connect->query("delete from products where id_product = $id");
        $connect->close();
    }
}