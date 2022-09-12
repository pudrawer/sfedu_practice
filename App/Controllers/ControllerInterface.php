<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;

interface ControllerInterface
{
    public function execute(): BlockInterface;
}
