<?php

namespace App\Control;

interface ControllerInterface
{
    public function holdAction($sku, $price);

    public function confirmAction($order);
}