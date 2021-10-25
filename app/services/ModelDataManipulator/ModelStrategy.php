<?php

namespace App\Models;

interface ModelStrategy{
    
    public function selectAll($conexion);
    public function selectRow($conexion,$id);
    public function selectRowsWithOffset($conexion,$rows,$rowOffset);
    public function selectRowsWithoutOffset($conexion,$rows);
    public function deleteAllRows($conexion);
}