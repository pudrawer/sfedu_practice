<?php

namespace App\Blocks;

use App\Models\AbstractCarModel;

class UserListBlock extends AbstractBlock
{
    protected $fileRender = 'user-list';
    protected $childStylesheetList = [];

    protected $data = [];
}
