<?php

namespace App\Container;

class Container
{
    private array $services = [];
    private array $factories = [];

    public function set(string $id, callable $factory): void
    {
        $this->factories[$id] = $factory;
    }

    public function get(string $id): object
    {
        if (isset($this->services[$id])) {
            return $this->services[$id];
        }

        if (!isset($this->factories[$id])) {
            throw new \RuntimeException("Сервиса {$id} не существует");
        }

        $service = ($this->factories[$id])($this);
        $this->services[$id] = $service;

        return $service;
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]) || isset($this->factories[$id]);
    }
}