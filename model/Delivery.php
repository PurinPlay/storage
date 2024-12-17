<?php

namespace Model;

require_once ("Connector.php");
require_once ("Staff.php");
require_once ("Product.php");

class Delivery
{
    private Staff $staff;
    private Product $product;
    private \DateTime $date;
    private string $provider;
    private int $count;

    public function __construct(Staff $staff, Product $product, \DateTime $date, string $provider, int $count)
    {
        $this->staff = $staff;
        $this->product = $product;
        $this->date = $date;
        $this->provider = $provider;
        $this->count = $count;
    }

    /**
     * @return Staff
     */
    public function getStaff(): Staff
    {
        return $this->staff;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param Staff $staff
     */
    public function setStaff(Staff $staff): void
    {
        $this->staff = $staff;
    }

    /**
     * @param Product $product
     */
    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @param string $provider
     */
    public function setProvider(string $provider): void
    {
        $this->provider = $provider;
    }

    /**
     * @param int $count
     */
    public function setCount(int $count): void
    {
        $this->count = $count;
    }
    public function toData(bool $extended = true) : array
    {
        return[
            (($extended)?"staff":"staffId") => (($extended)?$this->getStaff()->toData():$this->getStaff()->getId()),
            (($extended)?"product":"productId") => (($extended)?$this->getProduct()->toData():$this->getProduct()->getId()),
            "date" => $this->getDate()->format('Y-m-d H:i:s'),
            "provider" => $this->getProvider(),
            "count" => $this->getCount()
        ];
    }
    /**
     * @return Delivery[]
     */
    public static function findByStaffId(int $id) : array
    {
        $connect = new Connector();
        $temp = $connect->select("select * from delivery where staff_id = $id");
        $out = [];
        foreach ($temp as $row){
            $out[] = new Delivery(Staff::findById($row["staff_id"]), Product::findById($row["product_id"]), new \DateTime($row["delivery_date"]), $row["provider"], $row["count"]);
        }
        $connect->close();
        return $out;
    }

    /**
     * @return Delivery[]
     * @throws \Exception
     */
    public static function findByProductId(int $id) : array
    {
        $connect = new Connector();
        $temp = $connect->select("select * from delivery where product_id = $id");
        $out = [];
        foreach ($temp as $row){
            $out[] = new Delivery(Staff::findById($row["staff_id"]), Product::findById($row["product_id"]), new \DateTime($row["delivery_date"]), $row["provider"], $row["count"]);
        }
        $connect->close();
        return $out;
    }

    /**
     * @return Delivery[]
     * @throws \Exception
     */
    public static function findAll() : array
    {
        $connect = new Connector();
        $temp = $connect->select("select * from delivery");
        $out = [];
        foreach ($temp as $row){
            $out[] = new Delivery(Staff::findById($row["staff_id"]), Product::findById($row["product_id"]), new \DateTime($row["delivery_date"]), $row["provider"], $row["count"]);
        }
        $connect->close();
        return $out;
    }
    public static function insert(Staff $staff, Product $product, string $provider, int $count) : int
    {
        $connect = new Connector();
        $staffId = $staff->getId();
        $productId = $product->getId();
        $connect->query("select make_delivery($staffId,$productId,'$provider',$count)");
        $connect->close();
        return 0;
    }
}