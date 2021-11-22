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
$data_context = new ModelContext(new MySqlModel($connection));
$model = $data_context->getExecutionInstance();

$builder = new Builder();
$builder->select('users');
$builder->where('{username=' . $_SESSION['username'] . '}');
$builder->build();

$data = $model->query($builder);
$data_assoc = mysqli_fetch_assoc($data);


if (isset($_POST['data'])) {

    if (strlen($_POST['data']) == 0) {
        $sql = "UPDATE users SET username = 'John Doe' WHERE username = '" . $_SESSION['username'] . "'";
        $connection->query($sql);
        $_SESSION['username'] = 'John Doe';

        echo $twig->render('user.html.twig', [
            "username" => $_SESSION['username'],
            "user_pic_uri" => $_SESSION['user-pic'],
            "is_admin" => $_SESSION['isAdmin'],
            "taskers" => $_SESSION['taskers'],
            "user_name" => $data_assoc['username']
        ]);

        exit();
    } else {

        $sql = "UPDATE users SET username = '" . $_POST['data'] . "' WHERE username = '" . $_SESSION['username'] . "'";
        $connection->query($sql);
        $_SESSION['username'] = $_POST['data'];

        echo $twig->render('user.html.twig', [
            "username" => $_SESSION['username'],
            "user_pic_uri" => $_SESSION['user-pic'],
            "is_admin" => $_SESSION['isAdmin'],
            "taskers" => $_SESSION['taskers'],
            "user_name" => $data_assoc['username']
        ]);

        exit();
    }
}

echo $twig->render('user.html.twig', [
    "username" => $_SESSION['username'],
    "user_pic_uri" => $_SESSION['user-pic'],
    "is_admin" => $_SESSION['isAdmin'],
    "taskers" => $_SESSION['taskers'],
    "user_name" => $data_assoc['username']
]);
