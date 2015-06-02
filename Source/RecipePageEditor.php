<?php

class RecipePageEditor {

    static function editRecipe(array $oldRecipe, array $newRecipe) {
        $continue = true;
        $errors = '';
        $ingedients = RecipeStd::normalizerForIngredients($newRecipe['/Ingredients/']);
        $name = RecipeStd::normalizerForName($newRecipe['/Name/']);
        foreach ($ingedients as $value) {
            if ($value === '') {
                $continue = false;
                $errors .= "В поле ингредиенты можно вводить только русские символы, пробелы и запятые\n";
                break;
            }
        }
        if ($name === '') {
            $continue = false;
            $errors .= "В поле имя можно вводить только русские символы и пробелы\n";
        }
        if ($continue) {
            $newRecipe['/Name/'] = $name;
            $newRecipe['/Ingredients/'] = RecipeStd::arrayToStringIngredients($ingedients);
            $newRecipe['/Description/'] = RecipeStd::normalizerForDescription($newRecipe['/Description/']);
            SqlConnect::updateRecipeByName($oldRecipe, $newRecipe);
            RecipePageEditor::updateRecipePage($oldRecipe, $newRecipe);
        } 
        return $errors;
    }

    static function updateRecipePage(array $oldRecipe, array $newRecipe) {
        RecipePageRemover::removePage($oldRecipe);
        RecipePageGenerator::createNewPage($newRecipe);
    }

    static function getOldContent(array $oldRecipe) {
        chdir("..\\Pages");
        $recipeFileName = RecipeStd::rusToTranslit($oldRecipe['/Name/']) . ".php";
        $oldRacipePage = fopen($recipeFileName, 'r+');
        $content = file_get_contents($recipeFileName);
        $oldRecipe['/Description/'] = RecipePageEditor::getOnlyDescription($content);
        $oldRecipe['/Igredients/'] = SqlConnect::getIngredientsByName($oldRecipe['/Name/']);
        $oldRecipe['/remote_href/'] = RecipePageEditor::getHrefImage($content);
        preg_match_all("/<div class=\"Auth\">\s*<p>Автор: (.+)<\/p>\s*<\/div>/us", $content, $author);
        $oldRecipe['/Author/'] = $author[1][0];
        fclose($oldRacipePage);
        return $oldRecipe;
    }

    static function getOnlyDescription($content) {
        preg_match_all("/(<div\s*class\s*=\s*\"Desc\"\s*>)\s*(.+)\s*(<\/div>)/usU", $content, $matches);
        return RecipeStd::mbTrim(RecipeStd::brToNl($matches[2][0]));
    }

    static function getHrefImage($content) {
        preg_match_all("/<img\s*src\s*=\s*'(.+?)'.+/us", $content, $matches);
        return $matches[1][0];
    }

}
