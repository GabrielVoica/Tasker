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


$database = new Database();
$connection = $database->connect();


$categories = $connection->query('SELECT id,name FROM categories');
$categories = $categories->fetch_all();



if (isset($_POST['task-name'])) {

    $connection->query('INSERT INTO tasks (name,description,coin_value,type,users_using,level,category,created_by)
     VALUES ( "' . $_POST['task-name']  . '", "' . $_POST['task-description'] . '",
      ' . $_POST['task-taskies'] . ',' . $_POST['task-type'] . ','
        . 1 . ',' . 1 . ',' . $_POST['task-category'] . ',' . $_SESSION['id'] . ')');

    header('location: /');
}


echo $twig->render('task.form.html.twig', [
    "username" => $_SESSION['username'],
    "user_pic_uri" => $_SESSION['user-pic-url'],
    "is_admin" => $_SESSION['isAdmin'],
    "taskers" => $_SESSION['taskers'],
    "categories" => $categories
]);
