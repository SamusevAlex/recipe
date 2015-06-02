<?php
require_once '../Source/Header.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Recipe::Результат</title>
    </head>
    <body>
        <?php
        RecipeStd::helloHeader($isLogin, ${$useThisSuperGlobalArray}['login']);
        ?> 
        <?php
        echo "\n";
        $ingredientsSplitByCommaAndSort = RecipeStd::normalizerForIngredients($_POST['ingredients']);
        $continue = true;
        foreach ($ingredientsSplitByCommaAndSort as $value) {
            if ($value === '') {
                $continue = false;
            }
        }
        if ($continue) {
            $recipeFullIngredients = RecipeStd::recipeFullIngredientsRegExpGenerator($ingredientsSplitByCommaAndSort);
            $recipeSomeIngredients = RecipeStd::recipeSomeIngredientsRegExpGenerator($ingredientsSplitByCommaAndSort);
            $recipeSomeAndOtherIngredients = RecipeStd::recipeSomeAndOtherIngredients($ingredientsSplitByCommaAndSort);
            echo 'результат по запросу 1<br>';
            echo RecipeStd::arrayToRecipeHref(SqlConnect::recipeQuery($recipeFullIngredients));
            echo 'результат по запросу 2<br>';
            echo RecipeStd::arrayToRecipeHref(SqlConnect::recipeQuery($recipeSomeIngredients));
            echo 'результат по запросу 3<br>';
            echo RecipeStd::arrayToRecipeHref(SqlConnect::recipeQuery($recipeSomeAndOtherIngredients));
        } else {
            echo 'В поле ингредиенты можно вводить только русские символы, пробелы и запятые.<br><a href="index.php">Назад</a>';
        }
        ?>
    </body>
</html>
