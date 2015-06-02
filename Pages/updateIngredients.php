<?php
require_once '../Source/Header.php';
RecipeStd::updateIngredients();
header("Location:userPage.php");