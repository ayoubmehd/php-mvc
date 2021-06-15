<?php

require_trait("http");
abstract class AbstructController
{

    use Http;


    // User info
    protected function userid()
    {
        return isset($_SESSION["user"]) ? $_SESSION["user"]["user_id"] : NULL;
    }

    protected function fullname()
    {
        return isset($_SESSION["user"]) ? $_SESSION["user"]["fullname"] : NULL;
    }

    protected function email()
    {
        return isset($_SESSION["user"]) ? $_SESSION["user"]["email"] : NULL;
    }

    protected function ensegniant_id()
    {
        return isset($_SESSION["user"]) ? $_SESSION["user"]["Ensegniant_id"] : NULL;
    }

    // Bool auth helper functions
    protected function isAdmin()
    {
        return $this->isLoggedIn() && $_SESSION["user"]["role"] === "admin";
    }

    protected function isLoggedIn()
    {
        return isset($_SESSION["user"]) && $_SESSION["user"]["loged_in"];
    }

    protected function view(string $file, $data = [])
    {
        extract($data);
        $path = BASE_PATH . "views/" . $file . ".php";
        if (file_exists($path)) {
            require_once $path;
        }
    }
    protected function trait(string $file)
    {
        $path = BASE_PATH . "/traits/" . $file;
        if (file_exists($path)) {
            require_once $path;
        }
    }
}
