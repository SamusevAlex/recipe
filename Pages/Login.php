<?php
require_once '../Source/Header.php';
if ($isLogin) {
    header("Location:index.php");
}
foreach ($_POST as $key => $str) {
    $_POST[$key] = RecipeStd::antiInjection($str);
}
if (preg_match("/^\w+$/", $_POST["login"]) and $_POST["password"] !== '') {
    $_POST["login"] = mb_strtolower($_POST["login"]);
    if (SQLConnect::checkUser($_POST["login"], $_POST["password"])) {
        $_SESSION['login'] = $_POST["login"];
        $_POST['remember_me'] = (bool) $_POST['remember_me'];
        if ($_POST['remember_me'] === true) {
            setcookie("login", $_POST["login"]);
            setcookie("password", $_POST["password"]);
        }
        header("Location:{$_SERVER['HTTP_REFERER']}");
    } else {
        echo 'Not valid user';
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <form action="Login.php" method="POST" enctype='multipart/form-data'>
            Логин <input type="text" name="login" required><br>
            Пароль <input type="password" name="password" required><br>
            <input type="checkbox" name="remember_me"> Запомнить меня<br>
            <input type="submit" value="Войти"><br>
        </form>
        <a href="Registration.php">Зарегестрироваться?</a>
    </body>
</html>
