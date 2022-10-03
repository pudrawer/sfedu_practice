<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\LoginBlock;
use App\Exception\Exception;
use App\Models\Randomizer\Randomizer;
use App\Models\Recourse\LoginRecourse;
use App\Models\Session\Session;

class LoginController extends AbstractController
{
    public function execute(): BlockInterface
    {
        if (Session::getInstance()->getUserId()) {
            $this->redirectTo('profileInfo');
        }

        if ($this->isGetMethod()) {
            $randomizer = new Randomizer();
            Session::getInstance()->setCsrfToken($randomizer->generateCsrfToken());

            $loginBlock = new LoginBlock();
            return $loginBlock
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

        $session = Session::getInstance()->start();

        if (!$emailParam || !$passParam) {
            $session->addError('Invalid email or pass value');

            return false;
        }

        $loginRecourse = new LoginRecourse();
        $userInfo = $loginRecourse->checkLogin($emailParam);
        if (!password_verify($passParam, $userInfo['password'])) {
            $session->addError('Bad email or pass');

            return false;
        }

        $randomizer = new Randomizer();
        $session
            ->setUserId($userInfo['id'])
            ->setCsrfToken($randomizer->generateCsrfToken($userInfo['id']));
        return true;
    }
}
