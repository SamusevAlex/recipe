<?php
require_once '../Source/Header.php';
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
        Одно из полей не соответсвует стандарту. Проверьте данные.
        <a href="<?php echo $_GET['recipe'];?>.php">Назад</a>
    </body>
</html>
