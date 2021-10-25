<?php

namespace App\Models;

use App\Models\ModelStrategy;
use App\Models\ModelContexts\MySqlModel;

/**
 * This class serves as the context for the strategy model implementation
 * Once the strategy has been instantiated you can use the instance to execute the query
 * 
 */
class ModelContext{

    /**
     * Strategy instance, can change depending on the used database
     * 
     * The client uses the instance to make the queries 
     * 
     */
    private $modelStrategy;
    private $tableName;

    public function __construct(ModelStrategy $strategy){
        $this->modelStrategy = $strategy;
    }

    public function setTableName($tableName){
        $this->tableName = $tableName;
    }

    public function getExecutionInstance(){
        return $this->modelStrategy;
    }
}