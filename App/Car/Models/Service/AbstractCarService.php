<?php

namespace App\Car\Models\Service;

use App\Core\Models\Service\AbstractService;

abstract class AbstractCarService extends AbstractService
{
    abstract public function getList(): array;
    abstract public function getInfo(int $id): array;
}
