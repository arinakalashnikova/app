<?php

namespace App\Router;

interface RouterInterface {
    public function call(array $args);
}
