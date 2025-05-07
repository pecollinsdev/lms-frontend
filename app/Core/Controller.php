<?php

namespace App\Core;

class Controller
{
    protected function view(string $view, array $data = [])
    {
        extract($data);
        require_once "../app/Views/{$view}.php";
    }
}
