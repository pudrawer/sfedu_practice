<?php

namespace App\Controllers\Web;

use App\Blocks\BlockInterface;
use App\Blocks\ForbiddenBlock;
use App\Models\Environment;
use Laminas\Di\Di;

class ForbiddenController extends AbstractController
{
    public function __construct(
        Di $di,
        Environment $env,
        ForbiddenBlock $block,
        array $params = []
    ) {
        parent::__construct(
            $di,
            $env,
            $params,
            null,
            $block,
        );
    }

    public function execute(): BlockInterface
    {
        return $this->block
            ->setHeader(['403'])
            ->render('main');
    }
}
