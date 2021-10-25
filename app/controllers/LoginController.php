<?php

use \App\Models\ModelContext;
use App\Models\ModelContexts\MySqlModel;
use App\Services\Database;

include "../vendor/autoload.php";
require __DIR__ . "/../services/Database.php";
require __DIR__ . "/../services/ModelDataManipulator/modelContexts/MySqlModel.php";
require __DIR__ . "/../services/ModelDataManipulator/ModelContext.php";



session_start();

/**
 * Twig template loader
 * 
 */
$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);  //Add cache folder on production!


//Database conexion instance
$data = new \App\Services\Database();
$data->connect();


//If the petition is not for logging in, the login view is shown
if(empty($_POST)){
  echo $twig->render('login.html.twig',["var"=>"Hello"]);
}
//This block of code executes when the user makes a post to the login form
else{

  $conexion = new Database();
  $conexion->connect();
  $sql_context = new ModelContext(new MySqlModel($conexion));
  $sql_context->setTableName('users');
  echo json_encode($sql_context->getExecutionInstance()->selectAll("users"));
    
}
