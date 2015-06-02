<?php

/*
 * UNTESTED
 */

class RecipePageRemover {

    static function removeRecipe($recipeIn) {
        $recipe['/Name/'] = RecipeStd::normalizerForName($recipeIn);
        if ($recipe['/Name/'] !== '') {
            if (SqlConnect::isExistsName($recipe)) {
                $dbRemove = SqlConnect::deleteRecipeByName($recipe);
                $fileRemove = RecipePageRemover::removePage($recipe);
                if (!$fileRemove) {
                    $errors .= "File don't Remove\n";
                }
                if (!$dbRemove) {
                    $errors .= "Cortege don't Remove\n";
                }
                if ($dbRemove and $fileRemove) {
                    $errors .= "No errors";
                }
            } else {
                $errors .= "Рецепт с таким именем не существует\n";
            }
        } else {
            $errors .= "В поле имя можно вводить только русские символы и пробелы\n";
        }
        return $errors;
    }

    static function removePage($recipe) {
        $fileName = RecipeStd::rusToTranslit(RecipeStd::normalizerForName($recipe['/Name/'])) . "." . php;
        return unlink($fileName);
    }

}
