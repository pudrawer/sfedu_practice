<?php

namespace App\Mail\Controllers\Web;

use App\Account\Models\Resource\UserResource;
use App\Account\Models\User;
use App\Core\Controllers\Web\AbstractController;
use App\Core\Exception\Exception;
use App\Core\Exception\ResourceException;
use App\Core\Models\Environment;
use App\Mail\Models\Service\MailService;
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
