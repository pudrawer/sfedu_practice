<?php

namespace App\Core\Controllers\Web;

use App\Core\Blocks\BlockInterface;
use App\Core\Blocks\WrongBlock;
use App\Core\Models\Environment;
use Laminas\Di\Di;

class WrongController extends AbstractController
{
    public function __construct(
        Di $di,
        Environment $env,
        WrongBlock $block,
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
            ->setHeader(['500'])
            ->render('main');
    }
}
