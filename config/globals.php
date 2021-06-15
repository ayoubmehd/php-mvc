<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "constant.php";
require_once "functions.php";
require_once "autoload.php";
require_once __DIR__ . "/../controllers/Abstruct.controller.php";
require_once __DIR__ . "/../models/Abstruct.php";
