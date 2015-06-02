<?php
require_once '../Source/Header.php';
if (!$isLogin) {
    header("Location:Login.php");
}
$userRights = SqlConnect::userRights(${$useThisSuperGlobalArray}['login']);
if ($userRights !== 'a' and $userRights !== 'm') {
    header("Location:index.php");
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
            Название рецепта на удаление<br> <input type="text" name="recipe_for_delete">
            <input type="hidden" name="submit" value="true">
            <br> <input type="submit" value="submit">
        </form> 
        <?php
        if ($_POST['submit'] === 'true') {
            echo nl2br(RecipePageRemover::removeRecipe($_POST['recipe_for_delete']));
        }
        ?>
    </body>
</html>
