<?php
namespace App\View;

interface ViewInterface{
    public function success($message);

    public function error($message);
}

