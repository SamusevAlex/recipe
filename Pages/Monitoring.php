<?php
require_once '../Source/Header.php';
foreach ($_POST as $key => $str) {
    $_POST[$key] = RecipeStd::antiInjection($str);
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
        <form method="POST" enctype="multipart/form-data">
            Вывести последние <input type="number" name="number_of_recept" min="1"> рецептов
            <input type="hidden" name="submit" value="true">
            <br> <input type="submit" value="submit">
        </form> 
            <?php
            if ($_POST['submit'] === 'true') {
               $dbArray = SqlConnect::getRecipeByDate($_POST['number_of_recept']);
               echo RecipeStd::arrayToRecipeHref($dbArray);
            }
            ?>
    </body>
</html>
