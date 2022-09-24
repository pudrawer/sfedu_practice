<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\LoginBlock;
use App\Models\Recourse\LoginRecourse;
use App\Models\Session\Session;

class LoginController extends AbstractController
{
    public function execute(): BlockInterface
    {
        if ($this->isGetMethod()) {
            $session = Session::getInstance()->start();
            $loginBlock = new LoginBlock();
            $loginBlock
                ->setHeader(['page' => 'LOGIN'])
                ->render($loginBlock->getActiveLink());
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

        $session = Session::getInstance()->start();

        if (!$emailParam || !$passParam) {
            $session->setError('Invalid email or pass value');

            return false;
        }

        $loginRecourse = new LoginRecourse();
        $userId = $loginRecourse->checkLogin($emailParam, $passParam);
        if (!$userId) {
            $session->setError('Bad email or pass');

            return false;
        }

        $session->setUserId($userId);
        return true;
    }
}
