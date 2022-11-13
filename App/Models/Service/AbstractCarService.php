<?php

namespace App\Models\Service;

abstract class AbstractCarService extends AbstractService
{
    abstract public function getList(): array;
    abstract public function getInfo(int $id): array;
}
