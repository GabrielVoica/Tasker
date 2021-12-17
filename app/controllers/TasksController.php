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


$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);  //Add cache folder on production!

$database = new Database();
$connection = $database->connect();

if (isset($_POST['deleted_task_id'])) {
    $connection->query('DELETE FROM user_tasks WHERE user_id= ' . $_SESSION['id'] . ' AND task_id = ' . $_POST['deleted_task_id']);

    $tasks = $connection->query('SELECT ut.task_id, t.name, t.description, t.coin_value, t.type, t.level FROM user_tasks AS ut, tasks AS t WHERE ut.task_id = t.id AND ut.user_id = ' . $_SESSION['id']);
    $tasks_data = mysqli_fetch_all($tasks);

    if (mysqli_num_rows($tasks) == 0) {
        $no_tasks = true;
    } else {
        $no_tasks = false;
    }

    echo $twig->render('components/user-tasks.html.twig', [
        "tasks" => $tasks_data,
        "delete" => true,
        "no_tasks" => $no_tasks
    ]);
    exit();
} elseif (isset($_POST['do_task_id'])) {
    $data = $connection->query('SELECT coin_value, type FROM tasks WHERE id=' . $_POST['do_task_id']);
    $task_data = mysqli_fetch_assoc($data);

    if ($task_data['type'] == 1) {

        $taskers = $_SESSION['taskers'] + $task_data['coin_value'];
        $_SESSION['taskers'] = $taskers;
        $connection->query('UPDATE users SET taskers = ' . $taskers . ' WHERE id = ' . $_SESSION['id']);
        echo 'UPDATE users SET taskers = ' . $taskers . ' WHERE id = ' . $_SESSION['id'];


        $tasks = $connection->query('SELECT ut.task_id, t.name, t.description, t.coin_value, t.type, t.level FROM user_tasks AS ut, tasks AS t WHERE ut.task_id = t.id AND ut.user_id = ' . $_SESSION['id']);
        $tasks_data = mysqli_fetch_all($tasks);

        if (mysqli_num_rows($tasks) == 0) {
            $no_tasks = true;
        } else {
            $no_tasks = false;
        }



        exit();
    } elseif ($task_data['type'] == 0) {
        $data = $connection->query('SELECT coin_value, type FROM tasks WHERE id=' . $_POST['do_task_id']);
        $assoc_data = mysqli_fetch_assoc($data);
        if ($_SESSION['taskers'] - $assoc_data['coin_value'] > 0) {
            $connection->query('UPDATE users SET taskers = ' . $_SESSION['taskers'] - $assoc_data['coin_value'] . ' WHERE id = ' . $_SESSION['id']);
            $_SESSION['taskers'] = $_SESSION['taskers'] - $assoc_data['coin_value'];
        }

        exit();
    }


    exit();
}




$tasks = $connection->query('SELECT ut.task_id, t.name, t.description, t.coin_value, t.type, t.level FROM user_tasks AS ut, tasks AS t WHERE ut.task_id = t.id AND ut.user_id = ' . $_SESSION['id']);
$task_data = mysqli_fetch_all($tasks);

if (mysqli_num_rows($tasks) == 0) {
    $no_tasks = true;
} else {
    $no_tasks = false;
}



echo $twig->render('tasks.html.twig', [
    "username" => $_SESSION['username'],
    "user_pic_uri" => $_SESSION['user-pic-url'],
    "is_admin" => $_SESSION['isAdmin'],
    "taskers" => $_SESSION['taskers'],
    "tasks" => $task_data,
    "no_tasks" => $no_tasks
]);
