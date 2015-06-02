<?php
require_once '../Source/Header.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Recipe::Name</title>
    </head>
    <body>
        <?php
        RecipeStd::helloHeader($isLogin, ${$useThisSuperGlobalArray}['login']);
        if (${$useThisSuperGlobalArray}['login'] == preg_replace("/<a href='userPage.php'>(.+)<\/a>/us", "\\1", "Author") or SqlConnect::userRights(${$useThisSuperGlobalArray}['login']) === 'm' or SqlConnect::userRights(${$useThisSuperGlobalArray}['login']) === 'a') {
            echo "<form action='edit.php' enctype='multipart/form-data' method='POST'>
            <input type='hidden' name='Old_recipe' value='Name'>
            <input type='submit' value='Edit'>
        </form>";
        }
        ?> 
        <div class="Image">
            remote_href<br>
        </div>
        <p>To prepare:</p>
        <div class="Recipe">
            Name<br>
        </div>
        <p>You need:</p>
        <div class="Ingred">
            Ingredients<br>
        </div>
        <p>Let's cook:</p>
        <div class="Desc">
            Description <br>
        </div>
        <div class="Auth">
            <p>Автор: Author</p>
        </div>
    </body>
</html>