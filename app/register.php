<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<link rel="stylesheet" href="styles/styles.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<body class="register-page">

    <div class="register-page-panel-div-wrapper">
        <div class="register-page-panel-div"></div>
        <div class="register-page-panel-div"></div>
        <div class="register-page-panel-div"></div>
        <div class="register-page-panel-div"></div>
        <div class="register-page-panel-div"></div>
        <div class="register-page-panel-div"></div>
    </div>
    <h1 class="type-writter-container"><span class="type-writter"></span></h1>

    <div class="form-wrapper">
        <form action="" method="post" class="register-form">
             <label for="mail">Usuario</label>
            <input type="mail" name="mail"></input>
            <i class="fas fa-user"></i>
            <label for="mail">Correo</label>
            <input type="mail" name="mail"></input>
            <i class="fas fa-envelope"></i>
            <label for="password">Contraseña</label>
            <input type="password" name="password">
            <i class="fas fa-key"></i>
            <label for="password">Confirmar contraseña</label>
            <input type="password" name="password">
            <i class="fas fa-key"></i>
            <input class="submit" type="submit">
        </form>
        <a href="login.php" class="login-link">Inicia sesión</a>
    </div>

</body>
</html>