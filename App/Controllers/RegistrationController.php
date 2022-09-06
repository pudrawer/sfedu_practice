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

        $this->userRegistration();

        header("Location: http://localhost:8080/");
        exit;
    }

    private  function userRegistration()
    {
        $inputEmail  = htmlspecialchars($_POST['email']);
        $inputPass   = htmlspecialchars($_POST['pass']);
        $inputRepass = htmlspecialchars($_POST['repass']);

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