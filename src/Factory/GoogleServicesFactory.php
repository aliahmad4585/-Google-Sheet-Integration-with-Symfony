<?php

namespace App\Factory;

final class GoogleServiceFactory
{
    public function __construct(private array $services)
    {
        $this->services = $services;
    }

    public function create(string $type)
    {
        foreach ($this->services as $service) {
            if ($type === $service) {
                return new $service;
            }
        }

        throw new \InvalidArgumentException('Unknown channel given');
    }
}
