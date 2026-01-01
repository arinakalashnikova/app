<?php
interface ServiceInterface{
    public function hold($sku, $price);
    public function confirm($sku);
}