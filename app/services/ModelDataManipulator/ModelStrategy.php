<?php

namespace App\Models;

interface ModelStrategy{
    
    public function selectAll();
    public function selectRow($id);
    public function selectRows($rows,$rowOffset=null);
    public function deleteAll();

}