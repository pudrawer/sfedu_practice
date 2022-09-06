<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;

interface ControllerInterface
{
    public function __construct(array $getParam);

    public function execute(): BlockInterface;
}
