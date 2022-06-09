<?php

namespace App\Interface\Service;

interface  GoogleSheetInterface
{
    public function getClient(): object;
    public function getSheetValues($clien): array;
    public function writeDataOnSheet($client, $values);
}
