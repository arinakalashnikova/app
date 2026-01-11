<?php
namespace App\Service;

use App\Repository\OrderRepositoryInterface;
use RuntimeException;

class CartService implements ServiceInterface
{
    public OrderRepositoryInterface $OrderRepository;
    public function __construct(OrderRepositoryInterface $OrderRepository){
        $this->OrderRepository = $OrderRepository;
    }
    public function hold($sku,$price)
    {
        $id = $this->OrderRepository->hold($sku,$price);
        if (is_null($id)) {
            throw new RuntimeException(UNFOUND); //вместо текста передаем константу, если захотим в этом лучае выводить
                                                         // что-то другое, лезть сюда не придется
        }
    }

    public function confirm($order)
    {
        $id = $this->OrderRepository->confirm($order);
        if (is_null($id)) {
            throw new RuntimeException(UNFOUND);
        }
    }
}