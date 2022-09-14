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
        if (REQUEST_METHOD == 'GET') {
            $block = new RegistrationBlock();

            return $block
                ->setHeader(['page' => 'USER REGISTRATION'])
                ->render();
        }

        $this->registerUser();
        $this->redirectTo();
    }

    private function registerUser()
    {
        $inputEmail  = htmlspecialchars($_POST['email']) ?? null;
        $inputPass   = htmlspecialchars($_POST['pass']) ?? null;
        $inputRepass = htmlspecialchars($_POST['repass']) ?? null;

        if (!$inputEmail || !$inputPass || !$inputRepass) {
            throw new Exception();
        }

        if ($inputPass == $inputRepass) {
            $model = new RegistrationRecourse();
            $model->registerUser($inputEmail, $inputPass);
        } else {
            throw new Exception();
        }
    }
}
