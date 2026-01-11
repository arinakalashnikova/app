<?php
namespace App\Repository;

interface OrderRepositoryInterface {
    function hold($sku, $price);
    function confirm($order);
}