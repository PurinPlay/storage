<?php

namespace Model;

class Connector
{
    private \mysqli|false $connection;
    public function __construct()
    {
        $file = fopen("../connection", "r");
        if($file){
            $login = trim(fgets($file));
            $password = trim(fgets($file));
            $db = trim(fgets($file));
        }else{
            throw new \mysqli_sql_exception("can't open connection file", -1);
        }
        $this->connection = new \mysqli("localhost", $login, $password, $db);
    }

    /**
     * @return false|\mysqli
     */
    public function getConnection(): bool|\mysqli
    {
        return $this->connection;
    }

    public function query(string $query): \mysqli_result|bool
    {
        return $this->connection->query($query);
    }

    public function select(string $query): array
    {
        $result = $this->connection->query($query);
        $out = [];

        while ($row = $result->fetch_assoc()){
            $out[] = $row;
        }
        return $out;
    }
    public function close() : bool
    {
        return $this->connection->close();
    }
}