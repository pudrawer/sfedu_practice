<?php

namespace App\Core\Controllers\Web;

use App\Core\Blocks\BlockInterface;
use App\Core\Blocks\HomepageBlock;
use App\Core\Models\Environment;
use Laminas\Di\Di;

class HomepageController extends AbstractController
{
    public function __construct(
        Di $di,
        Environment $env,
        HomepageBlock $block,
        array $params = []
    ) {
        parent::__construct($di, $env, $params, null, $block);
    }

    public function execute(): BlockInterface
    {
        return $this->block
            ->setHeader(['MAIN'])
            ->render('main');
    }
}
