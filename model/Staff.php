<?php

namespace Model;

require_once ("Connector.php");
require_once ("Position.php");
require_once ("User.php");

class Staff
{
    private int $id;
    private string $firstName;
    private string $lastName;
    private string $middleName;
    private Position $position;
    private User $user;
    private string $address;
    private string $phoneNumber;
    #i have web programming
    public function __construct(int $id, string $firstName, string $lastName, ?string $middleName, Position $position, User $user, string $address, ?string $phoneNumber)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->middleName = ($middleName)?$middleName:"";
        $this->position = $position;
        $this->user = $user;
        $this->address = $address;
        $this->phoneNumber = ($phoneNumber)?$phoneNumber:"";
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
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getMiddleName(): string
    {
        return $this->middleName;
    }

    /**
     * @return Position
     */
    public function getPosition(): Position
    {
        return $this->position;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @param string $middleName
     */
    public function setMiddleName(string $middleName): void
    {
        $this->middleName = $middleName;
    }

    /**
     * @param Position $position
     */
    public function setPosition(Position $position): void
    {
        $this->position = $position;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }
    public function toData(bool $noUserData = true) : array
    {
        return [
            "id" => $this->getId(),
            "firstName" => $this->getFirstName(),
            "lastName" => $this->getLastName(),
            "middleName" => $this->getMiddleName(),
            "position" => $this->getPosition()->toData(),
            (($noUserData)?"userId":"user") => (($noUserData)?$this->getUser()->getId():$this->getUser()->toData()),
            "address" => $this->getAddress(),
            "phoneNumber" => $this->getPhoneNumber(),
        ];
    }
    public function dataToStaff(array $data) : Staff
    {
        return new Staff($data["id"], $data["firstName"], $data["lastName"],$data["middleName"],Position::findById($data["positionID"]),User::findById($data["userId"]), $data["address"], $data["phoneNumber"]);
    }

    public static function findById(int $id) : Staff | false
    {
        $connect = new Connector();
        $row = $connect->select("select * from staff where id_staff = $id");
        $connect->close();
        if(!$row){
            return false;
        }
        $row = $row[0];
        return new Staff($row["id_staff"], $row["first_name"], $row["last_name"], $row["middle_name"],Position::findById($row["position_id"]), User::findById($row["user_id"]), $row["address"], $row["phone_number"]);
    }
    /**
     * @return Staff[]
     */
    public static function findAll() : array
    {
        $connect = new Connector();
        $temp = $connect->select("select * from staff");
        $out = [];
        foreach ($temp as $item){
            $out[] = new Staff($item["id_staff"], $item["first_name"], $item["last_name"], $item["middle_name"],Position::findById($item["position_id"]), User::findById($item["user_id"]), $item["address"], $item["phone_number"]);
        }
        $connect->close();
        return $out;
    }
    public static function insert(string $firstName, string $lastName, string $middleName, Position $position, User $user, string $address, string $phoneNumber) : void
    {
        $connect = new Connector();
        $positionId = $position->getId();
        $userId = $user->getId();
        $connect->query("insert into staff(first_name, last_name, middle_name, position_id, user_id, address, phone_number) values('$firstName','$lastName','$middleName', $positionId , $userId ,'$address','$phoneNumber')");
        $connect->close();
    }
    public static function update(Staff $staff) : void
    {
        $connect = new Connector();
        $positionId = $staff->position->getId();
        $userId = $staff->user->getId();
        $connect->query("update staff set first_name='$staff->firstName', last_name='$staff->lastName', middle_name='$staff->middleName', position_id=$positionId, user_id=$userId, address='$staff->address', phone_number='$staff->phoneNumber' where id_staff = $staff->id");
        $connect->close();
    }
    public static function delete(int $id) : void
    {
        $connect = new Connector();
        $connect->query("delete from staff where id_staff = $id");
        $connect->close();
    }
}
#echo json_encode(Staff::findById(1)->toData());