<?php
require_once '../Source/Header.php';
if (!$isLogin) {
    header("Location:login.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        RecipeStd::helloHeader($isLogin, ${$useThisSuperGlobalArray}['login']);
        ?> <br>
        Ваши рецепты:<br>
        <?php
        echo "\n";
        $dbArray = SqlConnect::userRecipes(${$useThisSuperGlobalArray}['login']);
        echo RecipeStd::arrayToRecipeHref($dbArray);
        ?>
        <br>Ваши данные:<br>
        <?php
        echo "\n";
        foreach (SqlConnect::getUserData(${$useThisSuperGlobalArray}['login']) as $row) {
            foreach ($row as $data) {
                echo "$data<br>";
            }
        }
        if (SqlConnect::userRights(${$useThisSuperGlobalArray}['login']) === 'a') {
            echo '<br><a href="updateIngredients.php">Обновить рецепты</a><br>';
        }
        ?><br>
        <a href="changeUserData.php">Редактировать мои данные</a>
    </body>
</html>
