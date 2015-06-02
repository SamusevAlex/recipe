<?php
require_once '../Source/Header.php';
if (!$isLogin) {
    header("Location:login.php");
}
$row = SqlConnect::getUserData(${$useThisSuperGlobalArray}['login']);
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
        <form action="changeUserDataExecute.php" method="POST" enctype='multipart/form-data'>
            Текущий пароль <input type="password" name="password" value=""><br>
            Новый пароль <input type="password" name="password_new"><br>
            Повторите пароль <input type="password" name="password_new_re"><br>
            E-mail <input type="text" size="40" name="email" value="<?php echo($row[0][1]); ?>"><br>
            <input type="submit" value="Изменить данные"><br>
        </form>
    </body>
</html>
