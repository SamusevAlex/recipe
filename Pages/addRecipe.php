<?php
require_once '../Source/Header.php';
if (!$isLogin) {
    header("Location:Login.php");
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
        <form action="addRecipe.php" method="POST" enctype="multipart/form-data">
            Название рецепта: <input type="text" name="name" value="<?php echo $_POST["name"]; ?>" required><br>
            Ингридиеты рецепта: <input type="text" name="ingredient" size="200" value="<?php echo $_POST["ingredient"]; ?>" required><br>
            Описание рецепта: <textarea id="valueTextArea" rows="10" cols="45" name="description" required><?php echo $_POST["description"]; ?></textarea><br>
            <input type="hidden" name="valueTextArea" >

            Ссылка на изображение блюда: <input type="text" name="remote_href" value="<?php echo $_POST['remote_href']; ?>"><br>
            <input type="submit">
        </form>
        <?php
        if (isset($_POST["name"]) and isset($_POST["ingredient"]) and isset($_POST["description"])) {
            $recipe['/Name/'] = $_POST["name"];
            $recipe['/Ingredients/'] = $_POST["ingredient"];
            $recipe['/Description/'] = $_POST["description"];
            $recipe['/remote_href/'] = $_POST['remote_href'];
            $recipe['/Author/'] = ${$useThisSuperGlobalArray}['login'];
            $errors = RecipePageGenerator::addRecipe($recipe);
            if ($errors === '') {
                echo "<a href='" . RecipeStd::rusToTranslit($recipe['/Name/']) . ".php'>new recipe</a>";
            } else {
                echo nl2br($errors);
            }
        } else {
            echo "<p>Заполните все поля</p>";
        }
        ?>
    </body>
</html>
