<?php

namespace App\Controllers\Web;

use App\Blocks\AbstractBlock;
use App\Blocks\BlockInterface;
use App\Blocks\HomepageBlock;
use App\Models\Environment;
use App\Models\Randomizer\Randomizer;
use App\Models\Resource\AbstractResource;
use App\Models\Service\AbstractService;
use App\Models\Session\Session;
use App\Models\Validator\Validator;
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
