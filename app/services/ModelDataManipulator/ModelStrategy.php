<?php

namespace App\Models;

interface ModelStrategy{
    public function query($builder);
}