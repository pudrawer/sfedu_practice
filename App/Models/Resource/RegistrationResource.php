<?php

namespace App\Models\Resource;

use App\Database\Database;
use App\Exception\Exception;

class RegistrationResource extends AbstractResource
{
    public function registerUser(
        string $email,
        string $pass
    ): bool {
        $stmt = $this->database->getPdo()->prepare('
        INSERT INTO 
            `user` (`email`, `password`) 
        VALUES (:email, :pass);
        ');

        $paramMap = [
            ':email' => $email,
            ':pass'  => $pass,
        ];
        foreach ($paramMap as $alias => &$value) {
            $stmt->bindParam(
                $alias,
                $value,
                \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT
            );
        }

        if (!$stmt->execute()) {
            throw new Exception('Bad query' . PHP_EOL);
        }

        return true;
    }

    public function checkRegistration(string $email): bool
    {
        $stmt = $this->database->getPdo()->prepare('
        SELECT 
            `id` 
        FROM `user` 
        WHERE `email` = :email LIMIT 1;
        ');
        $stmt = $this->bindParamByMap($stmt, [
            ':email' => $email,
        ]);
        $stmt->execute();

        return (bool) $stmt->fetch();
    }
}
