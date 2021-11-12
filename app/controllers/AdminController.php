<?php

namespace App\Controllers;


use App\Services\Database;

include "../vendor/autoload.php";
include "../vendor/autoload.php";
require __DIR__ . "/../services/Database.php";
require __DIR__ . "/../services/ModelDataManipulator/modelContexts/MySqlModel.php";
require __DIR__ . "/../services/ModelDataManipulator/ModelContext.php";

session_start();

//Redirect to homepage if user is not logged
if (!isset($_SESSION['logged']) || $_SESSION['logged'] != true || $_SESSION['isAdmin'] != 1) {
    header('location: login');
}

/**
 * Twig template loader
 * 
 */
$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);  //Add cache folder on production!

//Add user picture system

$query_error = "";
$data = null;
$result_string = "";



if (isset($_POST['query']) && $_POST['query'] != null) {

    $database = new Database();
    $connection = $database->connect();

    if ($result = $connection->query($_POST['query'])) {
       if(gettype($result) == 'object'){
       $data = $result->fetch_all(); 
       }
       else{
        $result_string = "Query completada!";
       }
    }
    else{
       $query_error = mysqli_error($connection);
    }
}


echo $twig->render('admin.html.twig', [
    "user_pic_uri" => $_SESSION['user-pic'],
    "username" => $_SESSION['username'],
    "is_admin" => $_SESSION['isAdmin'],
    "taskers" => $_SESSION['taskers'],
    "sql_result" => $data,
    "sql_error" => $query_error,
    "sql_result_string" => $result_string
]);
