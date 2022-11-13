<?php

namespace App\Models\Resource;

use App\Database\Database;

class LoginResource extends AbstractResource
{
    public function checkLogin(
        string $email
    ): ?array {
        $stmt = $this->database->getPdo()->prepare('
        SELECT 
            `id`, `password`
        FROM 
            `user`
        WHERE `email` = :email
        LIMIT 1;
        ');

        $stmt = $this->bindParamByMap($stmt, [
            ':email' => $email,
        ]);
        $stmt->execute();

        return $stmt->fetch() ?: null;
    }
}
