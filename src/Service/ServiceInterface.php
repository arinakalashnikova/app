<?php
namespace App\Service;

interface ServiceInterface{
    public function hold($sku, $price);
    public function confirm($sku);
}