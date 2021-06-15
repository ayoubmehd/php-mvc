<?php

trait Http
{
    public function redirect($path)
    {
        header("Location: " . BASE_URL . $path);
    }
}
