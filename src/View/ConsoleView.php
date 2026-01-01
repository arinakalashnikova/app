<?php
require_once __DIR__ . '/ViewInterface.php';
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