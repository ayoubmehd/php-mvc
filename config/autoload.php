<?php
spl_autoload_register("autoload");

function autoload($class)
{
    $models_path = BASE_PATH . "/models/$class.php";
    $api_path = BASE_PATH . "/api/$class.php";
    if (file_exists($models_path)) {
        require_once $models_path;
    }
}
