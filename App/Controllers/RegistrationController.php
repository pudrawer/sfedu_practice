<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\RegistrationBlock;
use App\Database\Database;
use App\Exception\Exception;
use App\Models\Recourse\RegistrationRecourse;
use App\Models\Session\Session;

class RegistrationController extends AbstractController
{
    public function execute(): BlockInterface
    {
        if ($this->isGetMethod()) {
            if (Session::getInstance()->getUserId()) {
                $this->redirectTo('profileInfo');
            }

            $rand = random_int(
                PHP_INT_MIN + 1,
                PHP_INT_MAX - 1
            );

            Session::getInstance()->setCsrfToken($rand);
            $block = new RegistrationBlock();

            return $block
                ->setHeader(['USER REGISTRATION'])
                ->render('main');
        }

        $this
            ->registerUser($this->alreadyRegister())
            ->redirectTo();


        throw new Exception('Already registered' . PHP_EOL);
    }

    private function alreadyRegister(): RegistrationRecourse
    {
        $inputEmail = htmlspecialchars($this->getPostParam('email'));

        if (!$inputEmail) {
            throw new Exception();
        }

        $model = new RegistrationRecourse();

        if ($model->checkRegistration($inputEmail)) {
            throw new Exception();
        }

        return $model;
    }

    private function registerUser(RegistrationRecourse $model): self
    {
        $inputEmail  = htmlspecialchars($this->getPostParam('email'));
        $inputPass   = htmlspecialchars($this->getPostParam('pass'));
        $inputRepass = htmlspecialchars($this->getPostParam('repass'));

        $csrf = $this->getPostParam('csrfToken');
        $csrf = $csrf == Session::getInstance()->getCsrfToken();

        $hasRequiredData   = $inputEmail && $inputPass && $inputRepass;
        $hasEqualPasswords = $inputPass === $inputRepass;

        if (!$hasRequiredData || !$hasEqualPasswords || !$csrf) {
            throw new Exception();
        }

        $model->registerUser($inputEmail, $inputPass);

        return $this;
    }
}
