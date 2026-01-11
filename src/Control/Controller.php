<?php

namespace App\Control;

use App\Service\ServiceInterface;
use App\View\ViewInterface;
use Throwable;


class Controller{
    private ServiceInterface $service;
    private ViewInterface $view;

    public function __construct(ViewInterface $view,ServiceInterface $service)
    {
        //теперь используем интерфейсы а не классы,
        // если добавим HTMLView или кдругой сервис тут ничего переписывать не придется
        $this->service = $service;
        $this->view = $view;
    }

    public function holdAction($sku, $price)
    {
        try {
            $this->service->hold($sku, $price);
            $this->view->success(SUCCESS); //вместо текста передаем константу, если захотим в случае успеха выводить
                                            // что-то другое, лезть в Controller не придется.
        } catch (Throwable $e) {
            $this->view->error($e->getMessage());
        }
    }

    public function confirmAction($order)
    {
        try {
            $this->service->confirm($order);
            $this->view->success(SUCCESS);
        } catch (Throwable $e) {
            $this->view->error($e->getMessage());
        }
    }
}