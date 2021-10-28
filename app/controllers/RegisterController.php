<?php

use \App\Models\ModelContext;
use App\Models\ModelContexts\MySqlModel;
use \App\Models\ModelContexts\Builder;
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


if (empty($_POST)) {
    echo $twig->render('register.html.twig', ["var" => "Hello"]);
} else {

    $username           =  $_POST['username'];
    $email              =  $_POST['mail'];
    $password           =  $_POST['password'];
    $password_confirm   =  $_POST['password_confirm'];

    $username_error = $email_error = $password_error = null;


    /*
      Implement register with returning errors
    */


    $database = new Database();
    $connection = $database->connect();

    $data_context = new ModelContext(new MySqlModel($connection));
    $model = $data_context->getExecutionInstance();

    $builder = new Builder();
    $builder->insert('users');
    $builder->values('{username=jane}{email=jane@example.com}{password=jane}');
    $builder->build();

    $data = $model->query($builder);
}
