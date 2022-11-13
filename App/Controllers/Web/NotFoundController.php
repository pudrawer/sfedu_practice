<?php

namespace App\Controllers\Web;

use App\Blocks\BlockInterface;
use App\Blocks\NotFoundBlock;
use App\Models\Environment;
use Laminas\Di\Di;

class NotFoundController extends AbstractController
{
    public function __construct(
        Di $di,
        Environment $env,
        NotFoundBlock $block,
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
            ->setHeader(['404'])
            ->render('main');
    }
}
