<?php
require_once '../Source/Header.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Recipe::Главная</title>
    </head>
    <body>
        <?php
        RecipeStd::helloHeader($isLogin, ${$useThisSuperGlobalArray}['login']);
        ?> 
        <form action="RezultRecipe.php" method="POST" enctype="multipart/form-data">
            Введите ингридиенты через запятую:<br> <input type="text" name="ingredients" size="150">
            <br> <input type="submit" value="submit">
        </form> 
        <a href="test.php">Test</a><br>
        <a href="addRecipe.php">add Recipe</a><br>
        <a href="Login.php">Залогиниться</a><br>
        <a href="Registration.php">Зарегестрироваться</a><br>
        <a href="userPage.php">Моя сраница</a><br>
        <a href="changeUserData.php">Редактировать мои данные</a><br>
        <a href="RemoveRecipe.php">Удалить рецепт</a><br>
        <a href="Monitoring.php">Последние рецепты</a><br>
    </body>
</html>
