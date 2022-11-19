<?php

namespace App\Core\Controllers\Web;

use App\Core\Blocks\BlockInterface;
use App\Core\Blocks\ForbiddenBlock;
use App\Core\Models\Environment;
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
