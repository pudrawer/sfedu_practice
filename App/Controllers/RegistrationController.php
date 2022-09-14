<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\RegistrationBlock;
use App\Database\Database;
use App\Exception\Exception;
use App\Models\Resource\RegistrationRecourse;

class RegistrationController extends AbstractController
{
    public function execute(): BlockInterface
    {
        if ($this->isGetMethod()) {
            $block = new RegistrationBlock();

            return $block
                ->setHeader(['page' => 'USER REGISTRATION'])
                ->render($block->getActiveLink());
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

        $hasRequiredData   = $inputEmail && $inputPass && $inputRepass;
        $hasEqualPasswords = $inputPass === $inputRepass;

        if (!$hasRequiredData || !$hasEqualPasswords) {
            throw new Exception();
        }

        $model->registerUser($inputEmail, $inputPass);

        return $this;
    }
}
