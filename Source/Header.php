<?php

if (session_id()) {
    
} else {
    session_start();
}
require_once '../Source/RecipeStd.php';
require_once '../Source/SqlConnect.php';
require_once '../Source/RecipePageGenerator.php';
require_once '../Source/RecipePageRemover.php';
require_once '../Source/RecipePageEditor.php';
mb_internal_encoding("UTF-8");
ini_set('mysql.connect_timeout', '2');
if (!@mysql_connect("localhost", "pma", "")) {
    header("Location:dbNoResponse.php");
}
if (!empty($_SESSION['login']) and !empty($_COOKIE['login'])) {
    $useThisSuperGlobalArray = '_COOKIE';
}
if (empty($_SESSION['login']) and !empty($_COOKIE['login'])) {
    $useThisSuperGlobalArray = '_COOKIE';
}
if (!empty($_SESSION['login']) and empty($_COOKIE['login'])) {
    $isLogin = true;
    $useThisSuperGlobalArray = '_SESSION';
}
if (empty($_SESSION['login']) and empty($_COOKIE['login'])) {
    $isLogin = false;
}
if ($useThisSuperGlobalArray === '_COOKIE') {
    if (SqlConnect::checkUser(${$useThisSuperGlobalArray}['login'], ${$useThisSuperGlobalArray}['password'])) {
        $isLogin = true;
    } else {
        $isLogin = false;
    }
}