<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\RegistrationBlock;
use App\Database\Database;
use App\Exception\Exception;
use App\Models\Randomizer\Randomizer;
use App\Models\Recourse\RegistrationRecourse;
use App\Models\Session\Session;

class RegistrationWebController extends AbstractWebController
{
    public function execute(): BlockInterface
    {
        if (Session::getInstance()->getUserId()) {
            $this->redirectTo('profileInfo');
        }

        if ($this->isGetMethod()) {
            $randomizer = new Randomizer();
            Session::getInstance()->setCsrfToken($randomizer->generateCsrfToken());

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

        $this->checkCsrfToken();

        $hasRequiredData   = $inputEmail && $inputPass && $inputRepass;
        $hasEqualPasswords = $inputPass === $inputRepass;

        if (!$hasRequiredData || !$hasEqualPasswords) {
            throw new Exception();
        }

        $model->registerUser($inputEmail, $inputPass);

        return $this;
    }
}
