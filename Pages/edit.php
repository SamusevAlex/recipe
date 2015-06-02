<?php
require_once '../Source/Header.php';
if (!$isLogin) {
    header("Location:Login.php");
}
if (empty($_POST["Old_recipe"])) {
    header("Location:userPage.php");
}
$oldRecipe['/Name/'] = $_POST["Old_recipe"];
$oldRecipe = RecipePageEditor::getOldContent($oldRecipe);
$userRights = SqlConnect::userRights(${$useThisSuperGlobalArray}['login']);
if ($oldRecipe['/Author/'] == ${$useThisSuperGlobalArray}['login'] or $userRights == 'm' or $userRights == 'a') {
    if ($_POST['submit'] === 'true') {
        $newRecipe['/Name/'] = $_POST["name"];
        $newRecipe['/Ingredients/'] = $_POST["ingredient"];
        $newRecipe['/Description/'] = $_POST["description"];
        $newRecipe['/remote_href/'] = $_POST['remote_href'];
        $newRecipe['/Author/'] = ${$useThisSuperGlobalArray}['login'];
        $errors = RecipePageEditor::editRecipe($oldRecipe, $newRecipe);
        if ($errors === '') {
            header("Location:" . RecipeStd::rusToTranslit($_POST['name']) . ".php");
        } else {
            echo nl2br($errors);
        }
    }
} else {
    header("Location:" . RecipeStd::rusToTranslit($_POST['Old_recipe']) . ".php");
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
            Название рецепта: <input type="text" name="name" value="<?php echo $_POST["Old_recipe"]; ?>" required><br>
            Ингридиеты рецепта: <input type="text" name="ingredient" size="200" value="<?php echo $oldRecipe['/Igredients/']; ?>" required><br>
            Описание рецепта: <textarea id="valueTextArea" rows="10" cols="45" name="description" required><?php echo $oldRecipe['/Description/'] ?></textarea><br>
            Ссылка на изображение блюда: <input type="text" name="remote_href" value="<?php echo $oldRecipe['/remote_href/']; ?>"><br>
            <input type='hidden' name='Old_recipe' value="<?php echo $_POST["Old_recipe"]; ?>">
            <input type='hidden' name='submit' value="true">
            <input type="submit" name="edit">
        </form>
        <?php ?>
    </body>
</html>
