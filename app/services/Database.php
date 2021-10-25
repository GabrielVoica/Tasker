<?php

namespace App\Services;

/**
 * This class provides a database conexion instance
 * 
 */
class Database{

    private $connection = null;
    private $user =       null;
    private $password =   null;
    private $hostname =   null;
    private $database =   null;


    public function __construct(){
        $dotenv = \Dotenv\Dotenv::createImmutable("../");
        $dotenv->load();

        $this->user = getenv('DB_USERNAME');
        $this->password = getenv('DB_PASSWORD');
        $this->hostname = getenv('DB_HOSTNAME');
        $this->database = getenv('DB_DATABASE');

        $this->connect();
    }


    public function connect(){
        $this->connection = mysqli_connect($this->hostname,$this->user,$this->password,$this->database);
        if($this->connection == false){
            echo "Error connecting to the database";
        }
    }

    public function getConnection(){
        return $this->connection;
    }
}