<?php

namespace App\Models\ModelContexts;

use App\Models\ModelStrategy;
use mysqli;

include __DIR__ . "/../ModelStrategy.php";

class MySqlModel implements ModelStrategy{

    private $tableName;
    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    public function selectAll($table){
        $query = "SELECT * FROM ". $table;
        return mysqli_query($this->conexion, $query);
    }

    public function selectRow($table,$primary_key){
        $query = "SELECT * FROM ". $this->tableName . " WHERE id='" . $primary_key . "';"; 
        return mysqli_query($this->conexion,$query);
    }

    public function selectRowsWithOffset($table,$rows,$rowOffset){
        $query = "SELECT * FROM " . $this->tableName . " LIMIT " . $rowOffset . ", " . $rows . ";";
        return mysqli_query($this->conexion,$query);
    }

    public function selectRowsWithoutOffset($table,$rows){
        $query = "SELECT * FROM " . $this->tableName . " LIMIT " . $rows . ";";
        return mysqli_query($this->conexion,$query);
    }

    public function deleteAllRows($table){
        $query = "DELETE FROM " . $this->tableName . ";";
        return mysqli_query($this->conexion,$query);
    }
}