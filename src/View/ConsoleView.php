<?php
namespace App\View;

class ConsoleView implements ViewInterface
{
    public function success($message)
    {
        echo $message . PHP_EOL;
    }

    public function error($message)
    {
        echo $message . PHP_EOL;
    }
}