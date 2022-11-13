<?php

namespace App\Controllers\Web;

use App\Blocks\LoginBlock;
use App\Exception\Exception;
use App\Exception\ResourceException;
use App\Models\Environment;
use App\Models\Randomizer\Randomizer;
use App\Models\Resource\LoginResource;
use App\Models\Resource\UserResource;
use App\Models\Service\MailService;
use App\Models\Session\Session;
use App\Models\User;
use Laminas\Di\Di;

class MailController extends AbstractController
{
    public function __construct(
        Di $di,
        Environment $env,
        UserResource $resource,
        MailService $service,
        array $params = []
    ) {
        parent::__construct(
            $di,
            $env,
            $params,
            $resource,
            null,
            null,
            null,
            null,
            $service
        );
    }

    public function execute()
    {
        $userId = $this->getId();

        if (!$userId) {
            throw new Exception('Bad get param' . PHP_EOL);
        }

        $user = $this->di->get(User::class);
        try {
            $user = $this->resource->getInfo($user->setId($userId));
        } catch (ResourceException $e) {
            throw new Exception($e->getMessage() . PHP_EOL);
        }

        $this->service
            ->prepareMail($user)
            ->checkSendMail();

        $this->redirectTo('userList');
    }
}
