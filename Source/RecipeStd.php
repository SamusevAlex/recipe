<?php

class RecipeStd {

    static function rusToTranslit($string) {
        $converter = array(
            'а' => 'a', 'б' => 'b', 'в' => 'v',
            'г' => 'g', 'д' => 'd', 'е' => 'e',
            'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
            'и' => 'i', 'й' => 'y', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u',
            'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
            'ь' => "`", 'ы' => 'y', 'ъ' => "`",
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
            'А' => 'A', 'Б' => 'B', 'В' => 'V',
            'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
            'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
            'И' => 'I', 'Й' => 'Y', 'К' => 'K',
            'Л' => 'L', 'М' => 'M', 'Н' => 'N',
            'О' => 'O', 'П' => 'P', 'Р' => 'R',
            'С' => 'S', 'Т' => 'T', 'У' => 'U',
            'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',
            'Ь' => "`", 'Ы' => 'Y', 'Ъ' => "`",
            'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya', ' ' => '_'
        );
        return strtr($string, $converter);
    }

    /*
     * Returns trimed by whitespace string 
     */

    static function mbTrim($strForTrim) {
        return preg_replace("/(^\s+)|(\s+$)/us", "", $strForTrim);
    }

    static function updateIngredients() {
        foreach (SqlConnect::allIngredients() as $row) {
            foreach ($row as $ingredients) {
                $allIngredients .= $ingredients;
                $allIngredients .= ', ';
            }
        }
        $allIngredients = RecipeStd::normalizerForIngredients($allIngredients);
        if (SqlConnect::deleteIngredients()) {
            foreach ($allIngredients as $ingredient) {
                SqlConnect::insertIngredient($ingredient);
            }
        }
    }

    static function arrayToRecipeHref($dbArray) {
        if (is_array($dbArray)) {
            foreach ($dbArray as $row) {
                foreach ($row as $recipe) {
                    $strForReturn .= "<a href=" . RecipeStd::rusToTranslit($recipe) . ".php>$recipe</a><br>";
                }
            }
        } else {
            return $strForReturn = 'No Recipe';
        }
        return $strForReturn;
    }

    /*
     * Returns splited and trimed array from string
     * Принимает сроку, возвращает массив
     */

    static function splitByComma($ingredients) {
        $rowOfIngredients = preg_split("/[,]/", $ingredients);
        foreach ($rowOfIngredients as $ingredient) {
            $rowForReturn[] = RecipeStd::sortByWordIngredient(mb_strtolower(RecipeStd::checkCyrillic(RecipeStd::deleteSpecialSymbol(RecipeStd::mbTrim($ingredient)))));
        }
        return $rowForReturn;
    }

    /*
     * Возвращает массив, отсортированный и без повторов
     * Принимает строку, Возвращает массив
     */

    static function normalizerForIngredients($ingredients) {
        $ingredients = array_unique(RecipeStd::splitByComma(RecipeStd::antiInjection(RecipeStd::removeUnnecessaryComma($ingredients))));
        sort($ingredients);
        $rowForReturn = array_values($ingredients);
        return $rowForReturn;
    }

    /*
     * Сортирует по словам многословные ингридиенты (растительное масло - масло растительное)      
     * принимает строку, возвращает строку
     */

    static function checkCyrillic($ingredient) {
        if (!preg_match("/^[А-я\s]*$/us", $ingredient)) {
            return '';
        } else {
            return $ingredient;
        }
    }

    /*
     * Принимает строку, возвращает строку
     */

    static function normalizerForName($name) {
        $name = mb_strtolower(RecipeStd::antiInjection(RecipeStd::checkCyrillic($name)));
        return $name;
    }

    static function removeUnnecessaryComma($ingredients) {
        return preg_replace("/,\s*$/us", "", preg_replace("/,\s*,/us", ',', $ingredients));
    }

    static function normalizerForDescription($description) {
        return preg_replace("/\r\n/us", '<br />', $description);
    }

    /*
     * Преобразует массив ингидиентов в строку
     * Принимает массив, возвращает строку      
     */

    static function arrayToStringIngredients(array $ingredients) {
        foreach ($ingredients as $key => $ingredient) {
            $strForReturn .= $ingredient;
            if ($key !== sizeof($ingredients) - 1) {
                $strForReturn .= ", ";
            }
        }
        return $strForReturn;
    }

    static function brToNl($string) {
        return preg_replace('/\<br(\s*)?\/?\>/ius', "\n", $string);
    }

    static function sortByWordIngredient($ingredient) {
        $byWords = preg_split("/\s+/us", $ingredient);
        if (sizeof($byWords) > 1) {
            sort($byWords);
            foreach ($byWords as $key => $WordsOfIngredient) {
                $strForReturn .= $WordsOfIngredient;
                if ($key !== sizeof($byWords) - 1) {
                    $strForReturn .= ' ';
                }
            }
            return $strForReturn;
        } else {
            $strForReturn = $byWords[0];
            return $strForReturn;
        }
    }

    /*
     * Pattern Generator for query №1
     * Принимает массив, возвращает строку
     */

    static function recipeFullIngredientsRegExpGenerator(array $ingedients) {
        $strForReturn = "^";
        foreach ($ingedients as $key => $value) {
            $strForReturn .= $value;
            if ($key !== sizeof($ingedients) - 1) {
                $strForReturn .= ", ";
            }
        }
        $strForReturn .= '$';
        return $strForReturn;
    }

    /*
     * Pattern Generator for query №2
     * Принимает массив, возвращает строку
     */

    static function recipeSomeIngredientsRegExpGenerator(array $ingedients) {
        $strForReturn .= "^";
        foreach ($ingedients as $key => $value) {
            $strForReturn .= "(";
            $strForReturn .= $value;
            if ($key !== sizeof($ingedients) - 1) {
                $strForReturn .= ", )?";
            } else {
                $strForReturn .= ')?$';
            }
        }
        return $strForReturn;
    }

    /*
     * Pattern Generator for query №3
     * Принимает массив, возвращает строку
     */

    static function recipeSomeAndOtherIngredients(array $ingedients) {
        $strForReturn .= "(";
        foreach ($ingedients as $key => $value) {
            $strForReturn .= $value;
            if ($key !== sizeof($ingedients) - 1) {
                $strForReturn .= "|";
            } else {
                $strForReturn .= ")";
            }
        }
        return $strForReturn;
    }

    /*
     * Убирает лишние символы: "[]?!,\.«»{}();+\/*\[\]<>—"
     * Принимает строку, возвращает сроку      
     */

    static function deleteSpecialSymbol($ingredients) {
        return preg_replace("/[?!.«»{}\"\"()\\\;+\/*\[\]<>—–]/us", "", $ingredients);
    }

    /*
     * UNTESTED
     * Убирает HTML тэги и экранирует SQL запросы
     * Принимает строку, возвращает сроку      
     */

    static function antiInjection($str) {
        return mysql_real_escape_string(strip_tags($str));
    }

    static function antiInjectionForDescription($str) {
        return strip_tags($str);
    }

    static function helloHeader($isLogin, $user) {
        if ($isLogin) {
            echo 'Hello, ' . ucfirst($user);
            echo '<br><a href="logout.php">Выйти</a><br>';
        } else {
            echo '<br><a href="login.php">Войти</a>';
            echo '<br><a href="Registration.php">зарегистрироваться</a><br>';
        }
    }

}
