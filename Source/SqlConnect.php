<?php

class SqlConnect {

    static function checkUser($user, $password) {
        $sql = "Select Password From logins Where Login = \"$user\"";
        $result = SqlConnect::SQLQuery($sql);
        $row = mysql_fetch_row($result);
        if (sha1("c3.:7№EVf1Y5Dr#№" . $password) === $row[0]) {
            return true;
        } else {
            return false;
        }
    }

    static function addUser($user, $password, $email) {
        $registrationSuccessful = true;
        if (SqlConnect::isUserExist($user)) {
            $registrationSuccessful = false;
            $errors .= "User Exist<br>";
        }
        if (SqlConnect::isMailUses($email)) {
            $registrationSuccessful = false;
            $errors .= "Mail Uses<br>";
        }
        if ($registrationSuccessful) {
            $password = sha1("c3.:7№EVf1Y5Dr#№" . $password);
            $sql = "Insert Into logins (Login, Password, Email, Rights) Value (\"$user\", \"$password\", \"$email\", 'u')";
            SqlConnect::SQLQuery($sql);
            return "all done";
        } else {
            return $errors;
        }
    }

    static function isUserExist($user) {
        $sql = "Select Password From logins Where Login = \"$user\"";
        $result = SqlConnect::SQLQuery($sql);
        $row = mysql_fetch_row($result);
        if (empty($row)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    static function isMailUses($email) {
        $sql = "Select Password From logins Where Email = \"$email\"";
        $result = SqlConnect::SQLQuery($sql);
        $row = mysql_fetch_row($result);
        if (empty($row)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    static function userRecipes($user) {
        $sql = "SELECT Name FROM recipes where Autor = '$user'";
        $result = SqlConnect::sqlQuery($sql);
        for ($i = 0; $i < mysql_num_rows($result); $i++) {
            $row[] = mysql_fetch_row($result);
        }
        return $row;
    }

    static function getUserData($user) {
        $sql = "SELECT Login, Email FROM logins where Login = '$user'";
        $results = SqlConnect::sqlQuery($sql);
        for ($i = 0; $i < mysql_num_rows($results); $i++) {
            $row[] = mysql_fetch_row($results);
        }
        return $row;
    }

    static function setUserData($user, $password, $email) {
        $password = sha1("c3.:7№EVf1Y5Dr#№" . $password);
        $sql = "UPDATE `recipes`.`logins` SET `Password` = '$password', `Email` =  '$email' WHERE `logins`.`Login` = '$user'";
        return SqlConnect::sqlQuery($sql);
    }

    static function allIngredients() {
        $row = array();
        $sql = 'SELECT Ingredients FROM recipes';
        $results = SqlConnect::sqlQuery($sql);
        for ($i = 0; $i < mysql_num_rows($results); $i++) {
            $row[] = mysql_fetch_row($results);
        }
        return $row;
    }

    static function recipeQuery($pattern) {
        $row = array();
        $sql = 'SELECT Name FROM recipes where Ingredients REGexp \'' . $pattern . '\'';
        $result = SqlConnect::sqlQuery($sql);
        for ($i = 0; $i < mysql_num_rows($result); $i++) {
            $row[] = mysql_fetch_row($result);
        }
        return $row;
    }

    /*
     * Searching inputed recipe name in DB
     * Prarmetr $recipe - array of recipe info where $recipe[/Name/] is name
     * returns true if exists
     */

    static function userRights($user) {
        $sql = "SELECT Rights FROM logins where Login = '$user'";
        $result = SqlConnect::sqlQuery($sql);
        for ($i = 0; $i < mysql_num_rows($result); $i++) {
            $row[] = mysql_fetch_row($result);
        }
        return $row[0][0];
    }

    static function isExistsName(array $recipe) {
        $sql = 'SELECT Name FROM recipes';
        $result = SqlConnect::sqlQuery($sql);
        while ($row = mysql_fetch_array($result)) {
            if ($row['Name'] == $recipe['/Name/']) {
                return true;
            }
        }
        return false;
    }

    /*
     * Insert new recipe in the DB
     * Prarmetr $recipe - array of recipe info
     * returns true if success
     * Добавление нового рецепта в БД
     */

    static function insertNewRecipe(array $recipe) {
        $sql = "INSERT INTO recipes (Name, Ingredients, Autor) values('" . $recipe['/Name/'] . "', '" . $recipe['/Ingredients/'] . "', '" . $recipe['/Author/'] . "')";
        return SqlConnect::sqlQuery($sql);
    }

    static function insertIngredient($ingredient) {
        $sql = "insert into ingredients (Ingredient) values ('$ingredient')";
        SqlConnect::sqlQuery($sql);
    }

    static function deleteIngredients() {
        $sql = "DELETE FROM ingredients";
        return SqlConnect::sqlQuery($sql);
    }

    /*
     * UNTESTED
     * Принимает массив, возвращает true если удаление удачно
     */

    static function deleteRecipeByName(array $recipe) {
        $sql = "DELETE FROM recipes WHERE Name = '" . $recipe['/Name/'] . "'";
        return SqlConnect::sqlQuery($sql);
    }

    static function updateRecipeByName(array $oldRecipe, array $newRecipe) {
        $sql = "UPDATE recipes "
                . "SET Name = '" . $newRecipe['/Name/'] . "'"
                . ", Ingredients='" . $newRecipe['/Ingredients/'] . "'"
                . "WHERE Name = '" . $oldRecipe['/Name/'] . "'";
        return SqlConnect::sqlQuery($sql);
    }

    /*
     *
     */

    static function getIngredientsByName($name) {
        $sql = "SELECT Ingredients FROM `recipes` WHERE Name = '" . $name . "'";
        $result = SqlConnect::sqlQuery($sql);
        for ($i = 0; $i < mysql_num_rows($result); $i++) {
            $row[] = mysql_fetch_row($result);
        }
        return $row[0][0];
    }

    static function getRecipeByDate($limit) {
        $sql = "SELECT Name FROM `recipes` ORDER BY `recipes`.`Date_add` DESC LIMIT 0, $limit ";
        $result = SqlConnect::sqlQuery($sql);
        for ($i = 0; $i < mysql_num_rows($result); $i++) {
            $row[] = mysql_fetch_row($result);
        }
        return $row;
    }

    static private function sqlQuery($sql) {
        $connect = @mysql_connect("localhost", "pma", "");
        if (!$connect) {
            throw new Exception("Can't connect to DB, try later");
        }
        mysql_select_db("recipes", $connect);
        mysql_query("SET NAMES utf8");
        $result = mysql_query($sql);
        if ($result === false) {
            throw new Exception("Can't execute query");
        }
        return $result;
    }

}
