<?php
interface OrderRepositoryInterface {
    function hold($sku, $price);
    function confirm($order);
}