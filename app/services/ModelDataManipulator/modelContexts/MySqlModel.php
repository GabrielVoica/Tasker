<?php

namespace App\Models\ModelContexts;

class MySqlModel{

    private $tableName;
    private $tableData;

    public function __construct($tableName){
        $this->tableName = $tableName;
    }
}