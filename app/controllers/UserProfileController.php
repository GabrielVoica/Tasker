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
$builder->where('{id=' . $_SESSION['id'] . '}');
$builder->build();

$data = $model->query($builder);
$data_assoc = mysqli_fetch_assoc($data);


if (isset($_FILES['user-pic']['name'])) {
    $userPic = $_FILES['user-pic']['name'];
    $currentDirectory = getcwd();
    $uploadDirectory = "\\assets\\";

    $errors = []; // Store errors here

    $fileExtensionsAllowed = ['jpeg', 'jpg', 'png']; // These will be the only file extensions allowed 

    $fileName = $_FILES['user-pic']['name'];
    $fileSize = $_FILES['user-pic']['size'];
    $fileTmpName  = $_FILES['user-pic']['tmp_name'];
    $fileType = $_FILES['user-pic']['type'];

    $uploadPath = 'assets/user-pictures/' . basename($fileName);

    if ($fileSize > 4000000) {
        $errors[] = "File exceeds maximum size (4MB)";
    }

    if (empty($errors)) {
        $didUpload = move_uploaded_file($fileTmpName, $uploadPath);
        $_SESSION['user-pic-url'] = basename($fileName);
        $connection->query("UPDATE users SET picture_url = '" . basename($fileName) . "' WHERE id = '" . $_SESSION['id'] . "'");
    } else {
        foreach ($errors as $error) {
            echo $error . "These are the errors" . "\n";
        }
    }
}



if (isset($_POST['data'])) {

    if (strlen($_POST['data']) == 0) {
        $sql = "UPDATE users SET username = 'John Doe' WHERE id = '" . $_SESSION['id'] . "'";
        $connection->query($sql);
        $_SESSION['username'] = 'John Doe';

        echo $twig->render('user.html.twig', [
            "username" => $_SESSION['username'],
            "user_pic_uri" => $_SESSION['user-pic-url'],
            "is_admin" => $_SESSION['isAdmin'],
            "taskers" => $_SESSION['taskers'],
            "user_name" => $_SESSION['username']
        ]);
        exit();
    } else {

        $sql = "UPDATE users SET username = '" . $_POST['data'] . "' WHERE id = '" . $_SESSION['id'] . "'";

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

$positiveBalance = $data_assoc['task_balance'];
$negativeBalance = $data_assoc['negative_balance'];

if ($positiveBalance > 100) {
    $positiveBalance = 100;
} elseif ($positiveBalance < 0) {
    $positiveBalance  = 0;
}

if ($negativeBalance > 100) {
    $negativeBalance = 100;
} elseif ($negativeBalance < 0) {
    $negativeBalance = 0;
}






echo $twig->render('user.html.twig', [
    "username" => $_SESSION['username'],
    "user_pic_uri" => $_SESSION['user-pic-url'],
    "is_admin" => $_SESSION['isAdmin'],
    "taskers" => $_SESSION['taskers'],
    "user_name" => $data_assoc['username'],
    "user_level" => $data_assoc['level'],
    "positive_balance" => $positiveBalance,
    "negative_balance" => $negativeBalance,
    "task_balance" => $positiveBalance - $negativeBalance
]);
