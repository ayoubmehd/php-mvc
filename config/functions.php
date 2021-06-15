<?php
function require_trait($file)
{
    $path = BASE_PATH . "/traits/" . $file . ".trait.php";
    if (file_exists($path)) {
        require_once $path;
    }
}
