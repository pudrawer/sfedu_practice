<?php

namespace App\Models\Resource;

use App\Database\Database;
use App\Exception\Exception;

class RegistrationRecourse extends AbstractResource
{
    public function registerUser(
        string $email,
        string $pass
    ): bool {
        $connection = Database::getInstance();
        $stmt = $connection->prepare('
        INSERT INTO 
            `user` (`email`, `password`) 
        VALUES (:email, :pass);
        ');
        $stmt->bindParam(
            ":email",
            $email,
            \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT
        );
        $stmt->bindParam(
            ":pass",
            $pass,
            \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT
        );

        if (!$stmt->execute()) {
            throw new Exception('Bad query' . PHP_EOL);
        }

        return true;
    }
}
