<?php

namespace App\Controllers;

use \App\Models\ModelContext;
use App\Models\ModelContexts\MySqlModel;
use \App\Models\ModelContexts\Builder;
use App\Services\Database;

include "../vendor/autoload.php";
require __DIR__ . "/../services/Database.php";
require __DIR__ . "/../services/ModelDataManipulator/modelContexts/MySqlModel.php";
require __DIR__ . "/../services/ModelDataManipulator/ModelContext.php";



session_start();

//Redirect to homepage if user is not logged
if (!isset($_SESSION['logged']) || $_SESSION['logged'] != true) {
    header('location: login');
}


/**
 * Twig template loader
 * 
 */
$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);  //Add cache folder on production!

//Add user picture system
$_SESSION['user-pic'] = null;



//Trendy categories
$trendy_categories = [];

$database = new Database();
$connection = $database->connect();
$data_context = new ModelContext(new MySqlModel($connection));
$model = $data_context->getExecutionInstance();

if($result = $connection->query('SELECT categories.id, categories.name, categories.icon_name, categories.icon_color FROM categories, tasks WHERE tasks.category = categories.id ORDER BY tasks.users_using DESC LIMIT 6')){
    $trendy_categories = $result->fetch_all();
}


if($result = $connection->query('SELECT * FROM tasks INNER JOIN categories ON categories.id = tasks.category')){
    $task_list = $result->fetch_all();
}



echo $twig->render('home.html.twig', [
    "username" => $_SESSION['username'],
    "user_pic_uri" => $_SESSION['user-pic'],
    "is_admin" => $_SESSION['isAdmin'],
    "taskers" => $_SESSION['taskers'],
    "trendy_categories" => $trendy_categories,
    "task_list" => $task_list
]);
