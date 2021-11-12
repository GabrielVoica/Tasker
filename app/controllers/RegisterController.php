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

  $database = new Database();
  $connection = $database->connect();

  $data_context = new ModelContext(new MySqlModel($connection));
  $model = $data_context->getExecutionInstance();

  $builder = new Builder();


  if ($username == null) {
    $username_error = "The username is a required field";
  } else if (!preg_match('/^[\w\-\_]*$/', $username)) {
    $username_error = 'The username can only contain numbers, letters and underscores';
  } else if (strlen($username) < 5) {
    $username_error = "The minimum username langth is 5 characters";
  } else {
    $builder->select('users');
    $builder->where('{username=' . $username . '}');
    $builder->build();
    $data_username = $model->query($builder);
  }


  if ($email == null) {
    $email_error  = "The email is a required field";
  } else if (!preg_match('/^[\w\-\_\@\.]*$/', $email)) {
    $email_error = 'The email format is not correct or contains special characters';
  } else {
    $builder->select('users');
    $builder->where('{email=' . $email . '}');
    $builder->build();
    $data_mail = $model->query($builder);
  }


  if ($password == null || $password_confirm == null) {
    $password_error = "Missing password field";
  } else if (strcmp($password, $password_confirm) !== 0 && !isset($password_error)) {
    $password_error = "The passwords don't match";
  }


  if (isset($data_mail) && mysqli_num_rows($data_mail) > 0) {
    $email_error = "The email is already registered";
  }


  if (isset($data_username) && mysqli_num_rows($data_username) > 0) {
    $username_error = "The username is already taken";
  }


  if (!isset($username_error) && !isset($email_error) && !isset($password_error)) {

    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $builder->insert('users');
    $builder->values('{username=' . $username . '}{email=' . $email . '}{passw=' . $password_hash . '}{isAdmin=false}{taskers=0}');
    $builder->build();

    $data = $model->query($builder);

    echo $twig->render('register.html.twig', [
      "registered" => "Account created!"
    ]);
  } else {
    echo $twig->render('register.html.twig', [
      "username_error" => $username_error,
      "email_error" => $email_error,
      "password_error" => $password_error
    ]);
  }
}
