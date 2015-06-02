<?php
require_once '../Source/Header.php';
if (!$isLogin) {
    header("Location:login.php");
}
foreach ($_POST as $key => $str) {
    $_POST[$key] = RecipeStd::antiInjection($str);
}
mb_internal_encoding("UTF-8");
if (empty($_POST)) {
    header("Location:changeUserData.php");
}

if (empty($_POST['email'])) {
    $row = SqlConnect::getUserData(${$useThisSuperGlobalArray}['login']);
    $_POST['email'] = $row[0][1];
}
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        RecipeStd::helloHeader($isLogin, ${$useThisSuperGlobalArray}['login']);
        ?> 
        <?php
        if ($_POST["password"] !== '') {
            if (SQLConnect::checkUser(${$useThisSuperGlobalArray}['login'], $_POST["password"])) {
                $changeData = true;
                if (!preg_match("/^.{6,}$/us", $_POST["password_new"])) {
                    $changeData = false;
                    $errors .= "Password must be larger than 6 characters<br>";
                }
                if ($_POST["password_new"] !== $_POST["password_new_re"]) {
                    $changeData = false;
                    $errors .= "Passwords does not match<br>";
                }
                if ($changeData) {
                    if (SqlConnect::setUserData(${$useThisSuperGlobalArray}['login'], $_POST["password_new"], $_POST["email"])) {
                        echo 'all done';
                    } else {
                        echo 'Ошибка, что-то пошло не так<br>', "<a href='changeUserData.php'>Back</a>";
                    }
                } else {
                    echo $errors, "<a href='changeUserData.php'>Back</a>";
                }
            } else {
                echo 'Не верный пароль';
            }
        } else {
            echo 'Не верный пароль';
        }
        ?>
        <br><a href="index.php">На главную</a>
    </body>
</html>
