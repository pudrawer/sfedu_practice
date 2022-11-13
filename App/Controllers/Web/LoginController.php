<?php

namespace App\Controllers\Web;

use App\Blocks\BlockInterface;
use App\Blocks\LoginBlock;
use App\Models\Environment;
use App\Models\Randomizer\Randomizer;
use App\Models\Resource\LoginResource;
use App\Models\Session\Session;
use Laminas\Di\Di;

class LoginController extends AbstractController
{
    public function __construct(
        Di $di,
        Environment $env,
        LoginResource $resource,
        LoginBlock $block,
        Session $session,
        Randomizer $randomizer,
        array $params = []
    ) {
        parent::__construct(
            $di,
            $env,
            $params,
            $resource,
            $block,
            null,
            $session,
            $randomizer
        );
    }

    public function execute(): BlockInterface
    {
        if ($this->session->getUserId()) {
            $this->redirectTo('profileInfo');
        }

        if ($this->isGetMethod()) {
            $this->session->setCsrfToken($this->randomizer->generateCsrfToken());

            return $this->block
                ->setHeader(['LOGIN'])
                ->render('main');
        }

        if ($this->login()) {
            $this->redirectTo();
        }
        $this->redirectTo('login');
    }

    public function login(): bool
    {
        $emailParam = htmlspecialchars($this->getPostParam('email'));
        $passParam  = htmlspecialchars($this->getPostParam('pass'));

        $this->checkCsrfToken();

        $this->session->start();

        if (!$emailParam || !$passParam) {
            $this->session->addError('Invalid email or pass value');

            return false;
        }

        $userInfo = $this->resource->checkLogin($emailParam);
        if (!password_verify($passParam, $userInfo['password'])) {
            $this->session->addError('Bad email or pass');

            return false;
        }

        $this->session
            ->setUserId($userInfo['id'])
            ->setCsrfToken($this->randomizer->generateCsrfToken($userInfo['id']));
        return true;
    }
}
