<?php

namespace App\Models\Service;

abstract class AbstractService
{
    abstract public function getList(): array;
    abstract public function getInfo(int $id): array;
}
