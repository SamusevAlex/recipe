<?php

/*
 * Generate new page with defined name and input data from $recipe to page by tags. 
 * Генерация новой страницы с определенным именем и вставка данных из $recipe на страницу по тэгам.
 * $recipe must containt: Name, Ingredients, Description
 */

class RecipePageGenerator {

    static $availableHosts = array('radikal', 'images.vfl', 'vk.me');

    static function addRecipe(array $recipe) {
        $continue = true;
        $errors = '';
        $ingedients = RecipeStd::normalizerForIngredients($recipe['/Ingredients/']);
        $name = RecipeStd::normalizerForName($recipe['/Name/']);
        if (SqlConnect::isExistsName($recipe)) {
            $errors .= "Рецепт с таким именем уже существует\n";
        }
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
            $recipe['/Name/'] = $name;
            $recipe['/Ingredients/'] = RecipeStd::arrayToStringIngredients($ingedients);
            $recipe['/Description/'] = RecipeStd::normalizerForDescription($recipe['/Description/']);
            if (SqlConnect::insertNewRecipe($recipe)) {
                RecipePageGenerator::createNewPage($recipe);
            }
        }
        return $errors;
    }

    static function createNewPage($recipe) {

        $resultFileName = RecipeStd::rusToTranslit($recipe['/Name/']) . ".php";

        $templateFileName = "template.php";
        chdir("..\\Source");
        $filetemplate = fopen($templateFileName, 'r');
        chdir("..\\Pages");
        RecipePageGenerator::createNewFile($resultFileName);
        $fileresult = fopen($resultFileName, "w");
        chdir("..\\Source");
        $recipe['/remote_href/'] = RecipePageGenerator::imageResier($recipe);
        $templatecontent = RecipePageGenerator::dataInsert($recipe, file_get_contents($templateFileName));
        chdir("..\\Pages");
        file_put_contents($resultFileName, $templatecontent);

        fclose($filetemplate);
        fclose($fileresult);
    }

    private static function createNewFile($resultFileName) {
        fclose(fopen($resultFileName, "w"));
    }

    private static function dataInsert($recipe, $templatecontent) {
        foreach ($recipe as $key => $value) {
            $templatecontent = preg_replace($key, $value, $templatecontent);
        }
        return $templatecontent;
    }

    private static function imageResier($recipe) {
        $href = $recipe['/remote_href/'];
        if (!self::isAvailableHost($href)) {
            return '';
        }
        $imgSize = @getimagesize($href);
        if ($imgSize === false) {
            return '';
        }
        $wigth = $imgSize[0];
        $ratio = $wigth / 260;
        $height = $imgSize[1] / $ratio;
        $wigth = $imgSize[0] / $ratio;
        return "<img src='$href' alt='К сожалению изображения нет' width='$wigth' height='$height'>";
    }

    private static function isAvailableHost($host) {
        foreach (self::$availableHosts as $availableHost) {
            if (preg_match("/$availableHost/us", $host)) {
                return true;
            }
        }
        return false;
    }

}
