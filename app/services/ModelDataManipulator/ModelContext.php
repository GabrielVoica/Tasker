<?php

namespace App\Models;

use App\Models\ModelStrategy;

/**
 * This class serves as the context for the strategy model implementation
 * Once the strategy has been instantiated you can use the instance to execute the query
 * 
 */
class ModelContext{

    /**
     * Strategy instance, can change depending on the used database
     * 
     */
    private $modelStrategy;

    public function __construct(ModelStrategy $strategy){
        $this->modelStrategy = $strategy;
    }
}