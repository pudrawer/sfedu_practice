<?php

namespace App\Models\Modification;

use App\Models\AbstractCarModel;

interface InterfaceModification
{
    public function modifyProperties(AbstractCarModel $model): bool;

    public function prepareParamMap(AbstractCarModel $model): array;
}
