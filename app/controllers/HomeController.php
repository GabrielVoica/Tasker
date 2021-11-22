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

if ($result = $connection->query('SELECT DISTINCT categories.id, categories.name, categories.icon_name, categories.icon_color, categories.category_file_name FROM categories, tasks WHERE tasks.category = categories.id ORDER BY tasks.users_using DESC')) {
    $trendy_categories = $result->fetch_all();
}



if ($result = $connection->query('SELECT * FROM tasks INNER JOIN categories ON categories.id = tasks.category')) {
    $task_list = $result->fetch_all();
    $num_of_tasks = mysqli_num_rows($result);
}


if (isset($_POST['data'])) {

    if (strlen($_POST['data']) <= 0) {
        echo $twig->render('components/home.content.html.twig', [
            "task_list" => $task_list,
            "search" => true,
            "num_of_tasks" => count($task_list)
        ]);
    } else {

        $filtered_tasks = [];
        strtolower($_POST['data']);

        foreach ($task_list as $task) {
            if (strpos(strtolower($task[1]), strtolower($_POST['data'])) !== false) {
                array_push($filtered_tasks, $task);
            }
        }

        if (count($filtered_tasks) == 0) {
            echo $twig->render('components/home.content.html.twig', [
                "task_list" => null,
                "search" => true,
                "num_of_tasks" => 0,
                "trendy_categories" => $trendy_categories
            ]);
        } else {
            echo $twig->render('components/home.content.html.twig', [
                "task_list" => $filtered_tasks,
                "search" => true,
                "num_of_tasks" => count($filtered_tasks),
                "trendy_categories" => $trendy_categories,
            ]);
        }
    }
    exit();
}

echo $twig->render('home.html.twig', [
    "username" => $_SESSION['username'],
    "user_pic_uri" => $_SESSION['user-pic'],
    "is_admin" => $_SESSION['isAdmin'],
    "taskers" => $_SESSION['taskers'],
    "trendy_categories" => $trendy_categories,
    "task_list" => $task_list,
    "num_of_tasks" => count($task_list) -1
]);
