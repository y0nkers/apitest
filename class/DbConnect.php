<?php

class DbConnect
{
    private PDO $_connection;

    function __construct(string $username, string $password)
    {
        $host = "localhost";
        $dbname = "apitest";
        $charset = "utf8mb4";
        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

        try {
            $this->_connection = new PDO($dsn, $username, $password);
            $this->_connection->exec("set names utf8mb4");
            $this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $error) {
            die("Error connecting to database: " . $error->getMessage());
        }
    }

    public function closeConnection(): void
    {
        unset($this->_connection);
    }

    public function getPDO(): PDO
    {
        return $this->_connection;
    }

}
