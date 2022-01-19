<?php

namespace App\Controllers;

include "../vendor/autoload.php";


session_start();
session_destroy();
header('location: login');
