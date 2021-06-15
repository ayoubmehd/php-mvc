<?php

class HomeController extends AbstructController
{
    public function index()
    {
        $this->view("home");
    }
}
