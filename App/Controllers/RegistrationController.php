<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\RegistrationBlock;
use App\Database\Database;

class RegistrationController extends AbstractController
{
    public function execute(): BlockInterface
    {
        if (REQUEST_METHOD == 'GET') {
            $block = new RegistrationBlock();

            return $block->render();
        }

        $this->registerUser();
        $this->redirectTo();
    }

    private  function registerUser()
    {
        $inputEmail  = htmlspecialchars($_POST['email']) ?? null;
        $inputPass   = htmlspecialchars($_POST['pass']) ?? null;
        $inputRepass = htmlspecialchars($_POST['repass']) ?? null;

        if (!$inputEmail || !$inputPass || !$inputRepass) {
            $this->redirectTo('registration');
        }

        if ($inputPass == $inputRepass) {
            $connection = Database::getConnection();

            $stmt = $connection->prepare('
            INSERT INTO 
                `user` (`email`, `password`) 
            VALUES (?, ?);
            ');
            $stmt->bindParam(
                1,
                $inputEmail,
                \PDO::PARAM_INT|\PDO::PARAM_INPUT_OUTPUT
            );
            $stmt->bindParam(
                2,
                $inputPass,
                \PDO::PARAM_INT|\PDO::PARAM_INPUT_OUTPUT
            );
            $stmt->execute();
        }
    }
}