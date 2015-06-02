<?php
/*
 * TESTING ... 80%
 */
require_once '../Source/Header.php';
if ($isLogin) {
    header("Location:index.php");
}
foreach ($_POST as $key => $str) {
    $_POST[$key] = RecipeStd::antiInjection($str);
}
if (!empty($_SESSION['login'])) {
    header("Location:index.php");
}
if ($_POST["submit"] === "true") {
    $registrationSuccessful = true;
    if (!preg_match("/^\w{1,29}$/u", $_POST["login"])) {
        $registrationSuccessful = false;
        $errors .= 'Not valid name<br>';
    }
    if (!preg_match("/^.{6,}$/us", $_POST["password"])) {
        $registrationSuccessful = false;
        $errors .= "Password must be larger than 6 characters<br>";
    }
    if ($_POST["password"] !== $_POST["password_re"]) {
        $registrationSuccessful = false;
        $errors .= "Passwords does not match<br>";
    }
    if ($registrationSuccessful) {
        $_POST["login"] = mb_strtolower($_POST["login"]);
        $result = SQLConnect::addUser($_POST["login"], $_POST["password"], $_POST['email']);
        if ($result === "all done") {
            header("Location:Login.php");
        } else {
            echo $result;
        }
    } else {
        echo $errors;
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
        <form action="Registration.php" method="POST" enctype='multipart/form-data'>
            Логин <input type="text" name="login" value="<?php echo $_POST["login"]; ?>"><br>
            Пароль <input type="password" name="password"><br>
            Повторите пароль <input type="password" name="password_re"><br>
            E-mail <input type="text" name="email" value="<?php echo $_POST["email"]; ?>"><br>
            <input type="hidden" name="submit" value="true"><br>
            <input type="submit" value="Зарегестрироваться"><br>
        </form>
    </body>
</html>
