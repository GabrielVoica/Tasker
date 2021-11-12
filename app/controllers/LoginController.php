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

if (isset($_SESSION['logged']) && $_SESSION['logged'] = true) {
  header('location: /');
}

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
if (empty($_POST)) {
  echo $twig->render('login.html.twig', ["var" => "Hello"]);
}
//This block of code executes when the user makes a post to the login form
else {
  $mail = $_POST['mail'];
  $password = $_POST['password'];
  $mail_error = $password_error = null;

  $database = new Database();
  $connection = $database->connect();
  $data_context = new ModelContext(new MySqlModel($connection));
  $model = $data_context->getExecutionInstance();


  if ($mail != null && $password != null) {
    $builder = new Builder();
    $builder->select('users');
    $builder->where('{email=' . $mail . '}');
    $builder->build();

    $data = $model->query($builder);
    $data_assoc = mysqli_fetch_assoc($data);

    if (mysqli_num_rows($data) == 0) {
      $mail_error = "No account found";
    } else if (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $mail)) {
      $mail_error = "The email format is not correct";
    } else if (!password_verify($password, $data_assoc['passw'])) {
      $password_error = "Incorrect password";
    }
  } 
  else {
    $mail_error = "Missing fields";
  }

  if (!isset($mail_error) && !isset($password_error)) {

    $_SESSION['logged'] = true;
    $_SESSION['username'] = $data_assoc['username'];
    $_SESSION['isAdmin'] = $data_assoc['isAdmin'];
    $_SESSION['taskers'] = $data_assoc['taskers'];

    header('location: /');
  } else {
    echo $twig->render('login.html.twig', [
      "mail_error" => $mail_error,
      "password_error" => $password_error
    ]);
  }
}
